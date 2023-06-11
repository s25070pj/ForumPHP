<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>1</title>
</head>
<body>
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

// Pobranie ID kategorii przekazanej w parametrze URL
if (isset($_GET['category_id'])) {
    $categoryID = $_GET['category_id'];
    // Reszta kodu obsługującego kategorię o ID $categoryID
} else {
    echo "Nie przekazano ID kategorii.";
}



// Pobranie nazwy kategorii
$categorySql = "SELECT category_name FROM categories WHERE category_id = $categoryID";
$categoryResult = $conn->query($categorySql);

if ($categoryResult->num_rows > 0) {
    $categoryRow = $categoryResult->fetch_assoc();
    $categoryName = $categoryRow['category_name'];

    echo "<h2>Tematy z kategorii: $categoryName</h2>";

    // Pobranie tematów dla danej kategorii
    $topicsSql = "SELECT t.topic_id, t.topic_title, t.topic_date, u.username
                  FROM topics t
                  JOIN users u ON t.user_id = u.user_id
                  WHERE t.category_id = $categoryID";

    $topicsResult = $conn->query($topicsSql);

    if ($topicsResult->num_rows > 0) {
        while ($topicRow = $topicsResult->fetch_assoc()) {
            $topicID = $topicRow['topic_id'];
            $topicTitle = $topicRow['topic_title'];
            $topicDate = $topicRow['topic_date'];
            $username = $topicRow['username'];

            echo "<h3>Temat: $topicTitle</h3>";
            echo "<p><strong>Autor:</strong> $username</p>";
            echo "<p><strong>Data:</strong> $topicDate</p>";
            echo "<p><a href='topic.php?topic_id=$topicID'>Zobacz temat</a></p>";
            echo "<hr>";
        }
    } else {
        echo "<p>Brak tematów w tej kategorii.</p>";
    }
} else {
    echo "Nie znaleziono kategorii.";
}

$conn->close();
?>

</body>
</html>