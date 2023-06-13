<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php
    session_start();

    // Sprawdzenie, czy użytkownik jest zalogowany jako admin
    if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'admin') {
        header("Location: index.php");
        exit();
    }

    // Połączenie z bazą danych
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "forum";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    // Pobranie ID komentarza przekazanego w parametrze URL
    $commentID = $_POST['comment_id'];

    // Usunięcie zgłoszeń dotyczących tego komentarza
    $sql = "DELETE FROM Reports WHERE comment_id = '$commentID'";
    $conn->query($sql);

    // Usunięcie komentarza z bazy danych
    $sql = "DELETE FROM Comments WHERE comment_id = '$commentID'";
    if ($conn->query($sql) === TRUE) {
        echo "Komentarz został usunięty.";
    } else {
        echo "Błąd podczas usuwania komentarza: " . $conn->error;
    }


    $conn->close();
    ?>

    <a href="zgloszenia.php">Powrót do zgłoszeń</a>

</div>
</body>
</html>