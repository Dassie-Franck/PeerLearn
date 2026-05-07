<?php
require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/models/notification_model.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'non_connecte']);
    exit;
}

$user_id = $_SESSION['user_id'];
$last_count = (int)($_GET['last_count'] ?? 0);
$current_count = compter_notifications_non_lues($user_id);

echo json_encode([
    'nb_non_lus' => $current_count,
    'has_new' => $current_count > $last_count
]);