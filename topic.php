<?php
session_start();

// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie ID tematu przekazanego w parametrze URL
$topicID = $_GET['topic_id'];

// Pobranie danych tematu
$sql = "SELECT t.topic_title, t.topic_content, t.topic_date, u.username
        FROM topics t
        JOIN users u ON t.user_id = u.user_id
        WHERE t.topic_id = $topicID";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $topicTitle = $row['topic_title'];
    $topicContent = $row['topic_content'];
    $topicDate = $row['topic_date'];
    $username = $row['username'];
} else {
    echo "Nie znaleziono tematu.";
    exit();
}

// Pobranie komentarzy dla danego tematu
$sql = "SELECT c.comment_content, c.comment_date, u.username
        FROM comments c
        JOIN users u ON c.user_id = u.user_id
        WHERE c.topic_id = $topicID";

$comments = $conn->query($sql);

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Temat: <?php echo $topicTitle; ?></title>
</head>
<body>
<h2>Temat: <?php echo $topicTitle; ?></h2>
<p><strong>Autor:</strong> <?php echo $username; ?></p>
<p><strong>Data:</strong> <?php echo $topicDate; ?></p>
<p><?php echo $topicContent; ?></p>

<hr>

<h3>Komentarze</h3>

<?php
if ($comments->num_rows > 0) {
    while ($comment = $comments->fetch_assoc()) {
        $commentContent = $comment['comment_content'];
        $commentDate = $comment['comment_date'];
        $commentAuthor = $comment['username'];

        echo "<p><strong>Autor:</strong> $commentAuthor</p>";
        echo "<p><strong>Data:</strong> $commentDate</p>";
        echo "<p>$commentContent</p>";
        echo "<hr>";
    }
} else {
    echo "Brak komentarzy.";
}
?>

<!-- Formularz do dodawania komentarzy -->
<form method="POST" action="addcomment.php">
    <input type="hidden" name="topic_id" value="<?php echo $topicID; ?>">
    <label>Treść komentarza:</label><br>
    <textarea name="comment_content" required></textarea><br>
    <input type="submit" value="Dodaj komentarz">
</form>

</body>
</html>