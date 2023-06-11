<a href="search.php">Wyszukaj tematy</a>
<a href="creationoftopic.php">Utwórz nowy temat</a>

<?php
session_start();
if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'admin') {
    echo '<a href="admin.php">Panel administratora</a>';
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

// Pobranie danych kategorii
$sql = "SELECT c.category_id, c.category_name, t.topic_id, t.topic_title, t.topic_date, u.username
        FROM categories c
        LEFT JOIN topics t ON t.category_id = c.category_id
        LEFT JOIN users u ON t.user_id = u.user_id
        WHERE t.topic_date = (
            SELECT MAX(topic_date)
            FROM topics
            WHERE category_id = c.category_id
        )
        ORDER BY c.category_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryID = $row['category_id'];
        $categoryName = $row['category_name'];
        $topicID = $row['topic_id'];
        $topicTitle = $row['topic_title'];
        $topicDate = $row['topic_date'];
        $username = $row['username'];

        echo "<h2><a href='category.php?category_id=$categoryID'>$categoryName</a></h2>";

        if ($topicID) {
            echo "<h3>Temat: $topicTitle</h3>";
            echo "<p><strong>Autor:</strong> $username</p>";
            echo "<p><strong>Data:</strong> $topicDate</p>";
            echo "<p><a href='topic.php?topic_id=$topicID'>Zobacz temat</a></p>";
        } else {
            echo "<p>Brak tematów w tej kategorii.</p>";
        }

        echo "<hr>";
    }
} else {
    echo "Brak kategorii.";
}

$conn->close();
?>

<form method="POST" action="logout.php">
    <input type="submit" value="Wyloguj">
</form>