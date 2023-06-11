<?php
session_start();

// Zniszczenie sesji
session_destroy();

// Przekierowanie do strony logowania
header("Location: index.php");
exit();
?>
