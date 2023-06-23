<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Połączenie z bazą danych
$servername = "5.39.83.70";
$username = "adi";
$password = "superHaslo1$";
$dbname = "adi_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie danych z formularza
$topicID = $_POST['topic_id'];
$commentContent = $_POST['comment_content'];

// Pobranie ID użytkownika
$userName = $_SESSION['username'];

// Pobranie ID użytkownika na podstawie nazwy użytkownika
$sql = "SELECT user_id FROM users WHERE username = '$userName'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userID = $row['user_id'];

// Dodawanie komentarza do bazy danych
$datetime = date("Y-m-d H:i:s");

$sql = "INSERT INTO comments (comment_content, comment_date, user_id, topic_id)
        VALUES ('$commentContent', NOW(), $userID, $topicID)";

if ($conn->query($sql) === TRUE) {
    $conn->close();
    header("Location: topic.php?topic_id=$topicID");
    exit();
} else {
    echo "Błąd podczas dodawania komentarza: " . $conn->error;
}

$conn->close();
?>
