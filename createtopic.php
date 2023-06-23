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
$servername = "5.39.83.70";
$username = "adi";
$password = "superHaslo1$";
$dbname = "adi_db";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie ID użytkownika na podstawie nazwy użytkownika
$sql = "SELECT user_id FROM users WHERE username = '$userName'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userID = $row['user_id'];

// pobieranie id kategorii na podstawie jego nazwy
$sql = "SELECT category_id FROM categories WHERE category_name = '$category'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$categoryID = $row['category_id'];

// Dodawanie tematu do bazy danych
$datetime = date("Y-m-d H:i:s");

$sql = "INSERT INTO topics (topic_title, topic_content, topic_date, user_id, category_id)
            VALUES ('$title', '$content', NOW(), $userID, $categoryID)";

if ($conn->query($sql) === TRUE) {
    // Pobranie ID właśnie utworzonego tematu
    $topicID = $conn->insert_id;

    // Przekierowanie użytkownika do strony tematu
    header("Location: topic.php?topic_id=$topicID");
    exit();
} else {
    echo "Błąd podczas dodawania tematu: " . $conn->error;
}

$conn->close();
?>
