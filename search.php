<?php
// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Sprawdzenie, czy formularz został przesłany
if (isset($_GET['search'])) {
    $searchQuery = $_GET['searchQuery'];
    $searchType = $_GET['searchType'];

    // Zależnie od wybranego typu wyszukiwania wykonaj odpowiednie zapytanie
    switch ($searchType) {
        case 'users':
            $sql = "SELECT * FROM users WHERE username LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania użytkowników
            break;
        case 'user_topics':
            $sql = "SELECT t.*, u.username FROM topics t JOIN users u ON t.user_id = u.user_id WHERE u.username LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania tematów użytkownika
            break;
        case 'user_posts':
            $sql = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE u.username LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania postów użytkownika
            break;
        case 'topic_content':
            $sql = "SELECT * FROM topics WHERE topic_content LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania frazy w tematach
            break;
        default:
            echo "Nieprawidłowy typ wyszukiwania.";
            exit();
    }

    // Przekierowanie na stronę z wynikami wyszukiwania
    header("Location: search_results.php?searchQuery=$searchQuery&searchType=$searchType");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wyszukiwanie</title>
</head>
<body>
<h2>Wyszukiwanie</h2>

<form method="GET" action="search_results.php">
    <input type="text" name="searchQuery" required>
    <br>
    <input type="radio" name="searchType" value="users" required>Szukaj użytkownika<br>
    <input type="radio" name="searchType" value="user_topics">Szukaj tematów użytkownika<br>
    <input type="radio" name="searchType" value="user_posts">Szukaj postów użytkownika<br>
    <input type="radio" name="searchType" value="topic_content">Szukaj frazy w treści tematów<br>
    <br>
    <input type="submit" name="search" value="Szukaj">
</form>

</body>
</html>
