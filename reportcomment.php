<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Sprawdzenie, czy przesłano poprawnie formularz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie danych zgłoszenia
    $reportContent = $_POST['report_content'];
    $commentID = $_POST['comment_id'];
    $topicID = $_POST['topic_id'];

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
    $userName = $_SESSION['username'];
    $sql = "SELECT user_id FROM users WHERE username = '$userName'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $userID = $row['user_id'];

    // Sprawdzenie, czy zgłoszenie już istnieje w bazie danych
    $sql = "SELECT * FROM reports WHERE comment_id = $commentID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Zgłoszenie już istnieje, wyświetlenie odpowiedniego komunikatu
        echo "To zgłoszenie już istnieje.";
    } else {
        // Dodanie zgłoszenia do bazy danych
        $datetime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO reports (report_content, report_date, user_id, comment_id, topic_id)
                VALUES ('$reportContent', '$datetime', $userID, $commentID, $topicID)";

        if ($conn->query($sql) === TRUE) {
            echo "Zgłoszenie zostało wysłane.";
        } else {
            echo "Błąd podczas wysyłania zgłoszenia: " . $conn->error;
        }
    }

    $conn->close();
} else {
    // Przekierowanie w przypadku braku przesłania formularza
    header("Location: topic.php");
    exit();
}
?>
<a href="topic.php?topic_id=<?php echo $topicID; ?>">Powrót do tematu</a>
