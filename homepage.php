<?php
session_start();
include("connect.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arabic Handwritten Recognition</title>
  <link rel="stylesheet" href="style1.css" />
</head>
<style>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    display: flex;
    position: relative;
    overflow: hidden;
    font-family: sans-serif;
    background-image: url(3.jpg);
  }
    /* Conteneur principal */
    .container {
    background: rgba(255, 255, 255, 0.85);
    padding: 20px;
    border-radius: 10px;
    z-index: 1;
    text-align: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    max-width: 600px;
  }
  </style>
<body>
  <div class="container">
    <h1>✨ Arabic Handwritten Recognition ✨</h1>

    <!-- Custom File Upload Button -->
    <div class="upload-section">
      <label>Select an image:</label><br>
      <button id="fileButton" type="button">Upload File</button>
      <span id="fileName">No file selected</span>
      <input type="file" id="imageUpload" accept="image/*" style="display: none;" />
      <img id="uploadedImage" style="display:none;" />
    </div>

    <!-- Canvas Section -->
    <div class="canvas-section">
        <label for="">Or Draw a Word : </label><br>
      <canvas id="drawingCanvas" width="400" height="200"></canvas>
      <div class="buttons">
        <button onclick="clearCanvas()">Clean</button>
        <button onclick="recognizeDrawing()">recognize</button>
      </div>
    </div>

    <!-- Result -->
    <div id="result"> recognition result : ............ </div>
  </div>

 <!-- script src="https://unpkg.com/tesseract.js@v5.1.0/dist/tesseract.min.js"--><!--/script-->
  <script src="script1.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const fileInput = document.getElementById('imageUpload');
      const fileButton = document.getElementById('fileButton');
      const fileNameSpan = document.getElementById('fileName');
      const uploadedImage = document.getElementById('uploadedImage');
      const canvas = document.getElementById('drawingCanvas');
      const ctx = canvas.getContext('2d');
      const resultDiv = document.getElementById('result');

      let isDrawing = false;
      let lastX = 0;
      let lastY = 0;

      canvas.addEventListener('mousedown', startDrawing);
      canvas.addEventListener('mousemove', draw);
      canvas.addEventListener('mouseup', stopDrawing);
      canvas.addEventListener('mouseout', stopDrawing);

      function startDrawing(e) {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
      }

      function draw(e) {
        if (!isDrawing) return;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
      }

      function stopDrawing() {
        isDrawing = false;
      }

      fileButton.addEventListener('click', () => {
        fileInput.click();
      });

      fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        fileNameSpan.textContent = file ? file.name : "No file selected";

        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            uploadedImage.onload = function() {
              ctx.clearRect(0, 0, canvas.width, canvas.height);

              const ratio = Math.min(canvas.width / uploadedImage.width, canvas.height / uploadedImage.height);
              const newWidth = uploadedImage.width * ratio;
              const newHeight = uploadedImage.height * ratio;
              const x = (canvas.width - newWidth) / 2;
              const y = (canvas.height - newHeight) / 2;

              ctx.drawImage(uploadedImage, x, y, newWidth, newHeight);
            };
            uploadedImage.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    });

    function clearCanvas() {
      const canvas = document.getElementById('drawingCanvas');
      const ctx = canvas.getContext('2d');
      const resultDiv = document.getElementById('result');

      ctx.clearRect(0, 0, canvas.width, canvas.height);
      resultDiv.textContent = "Recognition result: ............";
    }

    async function recognizeDrawing() {
      const canvas = document.getElementById('drawingCanvas');
      const resultDiv = document.getElementById('result');

      try {
        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png', 1.0));
        const formData = new FormData();
        formData.append('image', blob, 'drawing.png');

        resultDiv.textContent = "Analyzing...";

        const response = await fetch('api.php', {
          method: 'POST',
          body: formData
        });

        const text = await response.text();
        const data = JSON.parse(text);

        resultDiv.textContent = data.error 
          ? `Error: ${data.error}` 
          : `Result: ${data.text}`;

      } catch (error) {
        console.error('Error:', error);
        resultDiv.textContent = 'Technical error';
      }
    }
  </script>


</body>
</html>