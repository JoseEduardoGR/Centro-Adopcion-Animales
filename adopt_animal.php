<?php
require_once 'config/session.php';
require_once 'config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_id = (int)$_POST['animal_id'];
    
    if (empty($animal_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de animal inválido']);
        exit();
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if animal is available
    $query = "SELECT id, adoption_status FROM animals WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$animal_id]);
    $animal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$animal) {
        echo json_encode(['success' => false, 'message' => 'Animal no encontrado']);
        exit();
    }
    
    if ($animal['adoption_status'] != 'Available') {
        echo json_encode(['success' => false, 'message' => 'Animal no disponible para adopción']);
        exit();
    }
    
    // Check if user already has a pending adoption for this animal
    $query = "SELECT id FROM adoptions WHERE user_id = ? AND animal_id = ? AND status = 'Pending'";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id'], $animal_id]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ya tienes una solicitud pendiente para este animal']);
        exit();
    }
    
    // Create adoption request
    $query = "INSERT INTO adoptions (user_id, animal_id, adoption_date) VALUES (?, ?, CURDATE())";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$_SESSION['user_id'], $animal_id])) {
        // Update animal status to pending
        $query = "UPDATE animals SET adoption_status = 'Pending' WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$animal_id]);
        
        echo json_encode(['success' => true, 'message' => 'Solicitud de adopción enviada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al procesar solicitud']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
exit();
?>
