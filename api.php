<?php
header('Content-Type: application/json');

// Configuration
$UPLOAD_DIR = __DIR__ . '/uploads/';
$MAX_FILE_SIZE = 5 * 1024 * 1024;
$ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

try {
    // Nettoyage
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'clean' && !empty($_POST['filename'])) {
            $file = $UPLOAD_DIR . basename($_POST['filename']);
            if (file_exists($file)) {
                unlink($file);
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }

    if (empty($_FILES['image']['tmp_name'])) {
        throw new Exception('Aucune image reçue');
    }

    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($fileinfo, $_FILES['image']['tmp_name']);

    if (!in_array($mime, $ALLOWED_TYPES)) {
        throw new Exception('Type de fichier non supporté');
    }

    if ($_FILES['image']['size'] > $MAX_FILE_SIZE) {
        throw new Exception('Fichier trop volumineux (>5MB)');
    }

    if (!is_dir($UPLOAD_DIR)) {
        mkdir($UPLOAD_DIR, 0755, true);
    }

    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '.' . $extension;
    $filepath = $UPLOAD_DIR . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
        throw new Exception('Échec de l\'enregistrement');
    }

    // Appel script Python
    $python_script = escapeshellarg(__DIR__ . '/recognize.py');
    $image_path = escapeshellarg($filepath);
    $command = "python $python_script $image_path 2>&1";

    $output = [];
    $return_code = 0;
    exec($command, $output, $return_code);

    file_put_contents($UPLOAD_DIR . 'raw_output.log', implode("\n", $output));

    if ($return_code !== 0) {
        file_put_contents($UPLOAD_DIR . 'python_error.log', implode("\n", $output));
        throw new Exception("Erreur lors de l'analyse (code $return_code)");
    }

    // Récupérer la dernière ligne JSON propre
    $json_line = '';
    foreach (array_reverse($output) as $line) {
        $decoded = json_decode($line, true);
        if ($decoded && isset($decoded['text'])) {
            $json_line = $line;
            break;
        }
    }

    if (!$json_line) {
        throw new Exception('Champ "text" manquant dans la réponse Python');
    }

    $response = json_decode($json_line, true);
    $response['filename'] = $filename;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>
