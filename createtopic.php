<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Pobranie danych z formularza
$title = $_POST['title'];
$content = $_POST['content'];
$category = $_POST['category'];


// Pobranie ID użytkownika
$userName = $_SESSION['username'];



// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie ID użytkownika na podstawie nazwy użytkownika
$sql = ("SELECT user_id FROM users WHERE username = '$userName'");
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userID = $row['user_id'];

// pobieranie id kategorii na podstawie jego nazwy
$sql = "SELECT category_id FROM Categories WHERE category_name = '$category'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$categoryID = $row['category_id'];



// Dodawanie tematu do bazy danych
$datetime = date("Y-m-d H:i:s");

$sql = "INSERT INTO Topics (topic_title, topic_content, topic_date, user_id, category_id)
            VALUES ('$title', '$content', NOW(), $userID, $categoryID)";


//bede musial dorobic kategorie

if ($conn->query($sql) === TRUE) {
    echo "Temat został dodany pomyślnie.";
} else {
    echo "Błąd podczas dodawania tematu: " . $conn->error;
}

$conn->close();
?>
