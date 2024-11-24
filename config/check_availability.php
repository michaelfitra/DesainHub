<?php
// check_availability.php - File baru untuk menghandle AJAX request
require 'config.php';

header('Content-Type: application/json');

if (isset($_POST['type']) && isset($_POST['value'])) {
    $type = $_POST['type'];
    $value = $_POST['value'];
    
    $field = ($type === 'email') ? 'email' : 'username';
    $sql = "SELECT COUNT(*) as count FROM users WHERE $field = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    echo json_encode(['exists' => $count > 0]);
    $stmt->close();
}
$conn->close();
?>
