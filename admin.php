<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>adminpanel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php
    session_start();
    if ($_SESSION['account_type'] !== 'admin') {
        // Użytkownik nie ma uprawnień administratora, przekierowanie lub wyświetlenie komunikatu
        header('Location: adminaccessdenied.php');
        exit;
    }
    ?>
    <a href="panel.php">Powrót do panelu głownego</a>
    <a href="zgloszenia.php">Zgłoszenia</a>


</div>
</body>
</html>