<?php
session_start();
if ($_SESSION['account_type'] !== 'admin') {
    // Użytkownik nie ma uprawnień administratora, przekierowanie lub wyświetlenie komunikatu
    header('Location: adminaccessdenied.php');
    exit;
}
?>
<a href="panel.php">Powrót do panelu głownego</a>

