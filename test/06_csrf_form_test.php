<?php
require_once 'bootstrap_test.php';
?>

<h2>Test CSRF Form</h2>

<form method="POST" action="07_csrf_verification_test.php">
    <?= csrfField() ?>
    <button type="submit">Tester CSRF</button>
</form>