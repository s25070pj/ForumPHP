<?php
// Połączenie z bazą danych
$servername = "5.39.83.70";
$username = "adi";
$password = "superHaslo1$";
$dbname = "adi_db";


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
    <link rel="stylesheet" href="style.css">

    <title>Wyszukiwanie</title>
</head>

<body>
<header>
    <nav>
        <ul class="navigation">
            <li><a href="panel.php">Strona główna</a></li>
            <li><a href="search.php">Wyszukiwanie</a></li>
            <li><a href="creationoftopic.php">Utwórz nowy temat</a></li>
            <li class="logout-button"><?php
                session_start();
                if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'admin') {
                    echo '<a href="admin.php">Panel administratora</a>';
                }?>
            </li>
        </ul>
        <form method="POST" action="logout.php">
            <input type="submit" value="Wyloguj">

        </form>
    </nav>
</header>

<div class="container">



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
</div>

</body>
</html>
