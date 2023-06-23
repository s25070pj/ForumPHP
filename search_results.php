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

// Sprawdzenie, czy przekazano parametry wyszukiwania
if (isset($_GET['searchQuery']) && isset($_GET['searchType'])) {
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
            $sql = "SELECT c.*, u.username, t.topic_content 
                    FROM comments c 
                    JOIN users u ON c.user_id = u.user_id 
                    JOIN topics t ON c.topic_id = t.topic_id 
                    WHERE u.username LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania postów użytkownika
            break;
        case 'topic_content':
            $sql = "SELECT t.*, u.username
            FROM topics t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.topic_content LIKE '%$searchQuery%'
            OR t.topic_title LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            // Obsługa wyników wyszukiwania frazy w tematach
            break;


        default:
            echo "Nieprawidłowy typ wyszukiwania.";
            exit();
    }

    // Przekształcenie wyników do tablicy
    $resultsArray = [];
    while ($row = $result->fetch_assoc()) {
        $resultsArray[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wyniki wyszukiwania</title>
    <link rel="stylesheet" href="style.css">
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
<h2>Wyniki wyszukiwania</h2>

<?php if (isset($resultsArray) && !empty($resultsArray)) : ?>
    <?php foreach ($resultsArray as $row) : ?>
    <hr>
        <?php if ($searchType === 'users') : ?>
            <h3>Użytkownik: <?php echo $row['username']; ?></h3>
            <p>ID użytkownika: <?php echo $row['user_id']; ?></p>
            <p>Typ konta: <?php echo $row['account_type']; ?></p>
            <p>Ścieżka do zdjęcia profilowego: <?php echo $row['profile_image']; ?></p>
            <!-- Wyświetlanie innych informacji o użytkowniku -->
        <?php elseif ($searchType === 'user_topics') : ?>
            <h3>Temat</h3>
            <p>Autor: <?php echo $row['username']; ?></p>
            <p>Treść: <?php echo $row['topic_content']; ?></p>
            <!-- Wyświetlanie innych informacji o temacie -->
        <?php elseif ($searchType === 'user_posts') : ?>
            <h3>Post</h3>
            <p>Autor: <?php echo $row['username']; ?></p>
            <p>Treść: <?php echo $row['comment_content']; ?></p>
            <p>Temat: <?php echo $row['topic_content']; ?></p>
            <!-- Wyświetlanie innych informacji o poście -->
        <?php elseif ($searchType === 'topic_content') : ?>
            <h3>Temat</h3>
            <p>Autor: <?php echo $row['username']; ?></p>
            <p>Tytuł tematu: <?php echo $row['topic_title']; ?></p>
            <p>Treść: <?php echo $row['topic_content']; ?></p>
            <p>Data dodania: <?php echo $row['topic_date']; ?></p>
            <!-- Wyświetlanie innych informacji o temacie -->
        <?php endif; ?>

    <?php endforeach; ?>
<?php else : ?>
    <p>Brak wyników wyszukiwania.</p>
<?php endif; ?>
</div>
</body>
</html>
