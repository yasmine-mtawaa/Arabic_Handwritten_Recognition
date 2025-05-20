import os
import sys
import json
import traceback
from datetime import datetime

DEBUG_LOG = "D:\\xampp\\htdocs\\web\\python_debug.log"

def log(message):
    with open(DEBUG_LOG, 'a',encoding='utf-8') as f:
        f.write(f"[{datetime.now()}] {message}\n")

try:
    log("=== Démarrage du script ===")
    log(f"Arguments: {sys.argv}")
    
    # Import différé pour mieux tracer les erreurs
    log("Importation des bibliothèques...")
    import cv2
    import numpy as np
    from tensorflow.keras.models import load_model
    import pickle
    
    # Vérification des arguments
    if len(sys.argv) < 2:
        raise ValueError("Chemin d'image manquant")
    
    image_path = sys.argv[1]
    log(f"Traitement de l'image: {image_path}")
    
    if not os.path.exists(image_path):
        raise FileNotFoundError(f"Fichier introuvable: {image_path}")
    
    # Lecture de l'image
    log("Lecture de l'image...")
    with open(image_path, 'rb') as f:
        img_data = np.frombuffer(f.read(), np.uint8)
        img = cv2.imdecode(img_data, cv2.IMREAD_GRAYSCALE)
        if img is None:
            raise ValueError("Échec du décodage de l'image")
    
    # Prétraitement
    log("Prétraitement...")
    img = cv2.resize(img, (128, 64))
    img = img.astype('float32') / 255.0
    img = np.expand_dims(img, axis=(0, -1))
    
    # Chargement du modèle
    log("Chargement du modèle...")
    model = load_model('saved_model.h5', compile=False)
    with open('label_encoder.pkl', 'rb') as f:
        label_encoder = pickle.load(f)
    
    # Prédiction
    log("Prédiction...")
    prediction = model.predict(img, verbose=0)
    predicted_word = label_encoder.inverse_transform([np.argmax(prediction)])[0]
    
    result = {"text": predicted_word}
    log(f"Résultat: {result}")
    log(f"Texte prédit: {predicted_word} ({type(predicted_word)})")
    print(json.dumps(result))
    sys.exit(0)


except Exception as e:
    error_msg = f"ERREUR: {type(e).__name__}: {str(e)}\n{traceback.format_exc()}"
    log(error_msg)
    print(json.dumps({"error": error_msg}))
    sys.exit(1)

