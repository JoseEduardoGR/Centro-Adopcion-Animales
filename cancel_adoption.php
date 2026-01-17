<?php
require_once 'config/session.php';
require_once 'config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adoption_id = (int)$_POST['adoption_id'];
    
    if (empty($adoption_id)) {
        echo json_encode(['success' => false, 'message' => 'ID de adopción inválido']);
        exit();
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Get adoption details
    $query = "SELECT animal_id FROM adoptions WHERE id = ? AND user_id = ? AND status = 'Pending'";
    $stmt = $db->prepare($query);
    $stmt->execute([$adoption_id, $_SESSION['user_id']]);
    $adoption = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$adoption) {
        echo json_encode(['success' => false, 'message' => 'Adopción no encontrada o no se puede cancelar']);
        exit();
    }
    
    // Delete adoption request
    $query = "DELETE FROM adoptions WHERE id = ?";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$adoption_id])) {
        // Update animal status back to available
        $query = "UPDATE animals SET adoption_status = 'Available' WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$adoption['animal_id']]);
        
        echo json_encode(['success' => true, 'message' => 'Solicitud de adopción cancelada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al cancelar solicitud']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
exit();
?>
