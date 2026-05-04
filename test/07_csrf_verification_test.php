<?php
require_once 'bootstrap_test.php';

echo "<h2>CSRF Verification</h2>";

requireCsrf();

echo "✅ CSRF valide";
