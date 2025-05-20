# Start from a base image with PHP and Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    python3 \
    python3-pip \
    python3-venv \
    python3-dev \
    default-libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql

# Create a Python virtual environment and install packages
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Now we can use pip within the virtual environment
RUN pip3 install --upgrade pip && \
    pip3 install --no-cache-dir numpy tensorflow keras opencv-python-headless pillow h5py

# Set working directory
WORKDIR /var/www/html

# Copy your application files
COPY . /var/www/html/

# Make uploads directory writable
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

# Ensure Python scripts are executable
RUN if [ -f /var/www/html/recognize ]; then chmod +x /var/www/html/recognize; fi

# If you have a Python script, create a wrapper to use the virtual environment
RUN if [ -f /var/www/html/recognize ]; then \
    echo '#!/bin/bash' > /var/www/html/run_recognize.sh && \
    echo 'source /opt/venv/bin/activate' >> /var/www/html/run_recognize.sh && \
    echo 'python3 /var/www/html/recognize "$@"' >> /var/www/html/run_recognize.sh && \
    chmod +x /var/www/html/run_recognize.sh; \
    fi

# Configure Apache
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]