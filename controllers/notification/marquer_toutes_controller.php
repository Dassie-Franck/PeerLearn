<?php
require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/models/notification_model.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$user_id = $_SESSION['user_id'];
$notification_id = (int)($_POST['notification_id'] ?? 0);

if ($notification_id) {
    marquer_notification_lue($notification_id, $user_id);
}

echo json_encode(['success' => true]);