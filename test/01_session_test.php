<?php
require_once 'bootstrap_test.php';

echo "<h2>Test Session</h2>";

if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session démarrée correctement";
} else {
    echo "❌ Session non active";
}