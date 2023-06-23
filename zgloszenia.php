<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>adminzgloszenia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php
    session_start();
    if ($_SESSION['account_type'] !== 'admin') {
        // Użytkownik nie ma uprawnień administratora, przekierowanie lub wyświetlenie komunikatu
        header('Location: adminaccessdenied.php');
        exit;
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

    // Pobranie zgłoszeń
    $sql = "SELECT t.topic_id, t.topic_title, c.comment_id, c.comment_content, c.comment_date, c.user_id, r.report_id, r.report_content, r.report_date
        FROM topics t
        JOIN comments c ON t.topic_id = c.topic_id
        JOIN reports r ON c.comment_id = r.comment_id";

    $result = $conn->query($sql);

    $conn->close();
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Zgłoszenia</title>
    </head>
    <body>
    <h2>Zgłoszenia</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topicID = $row['topic_id'];
            $topicTitle = $row['topic_title'];
            $commentID = $row['comment_id'];
            $commentContent = $row['comment_content'];
            $commentDate = $row['comment_date'];
            $userID = $row['user_id'];
            $reportID = $row['report_id'];
            $reportContent = $row['report_content'];
            $reportDate = $row['report_date'];

            echo "<h3>Nazwa tematu: $topicTitle</h3>";
            echo "<p><strong>Link do tematu:</strong> <a href='topic.php?topic_id=$topicID'>topic.php?id=$topicID</a></p>";
            echo "<p><strong>Treść zgłoszenia:</strong> $reportContent</p>";
            echo "<p><strong>Autor zgłoszenia:</strong> $userID</p>";
            echo "<p><strong>Data zgłoszenia:</strong> $reportDate</p>";
            echo "<p><strong>Treść komentarza:</strong> $commentContent</p>";
            echo "<p><strong>Autor komentarza:</strong> $userID</p>";
            echo "<p><strong>Data dodania komentarza:</strong> $commentDate</p>";
            echo "<form method='POST' action='removecomment.php'>";
            echo "<input type='hidden' name='comment_id' value='$commentID'>";
            echo "<input type='submit' value='Usuń post'>";
            echo "</form>";
            echo "<form method='POST' action='rejectreport.php'>";
            echo "<input type='hidden' name='comment_id' value='$commentID'>";
            echo "<input type='hidden' name='report_id' value='$reportID'>";
            echo "<input type='submit' value='Odrzuć zgłoszenie'>";
            echo "</form>";
            echo "<hr>";
        }
    } else {
        echo "Brak zgłoszeń.";
    }
    ?>

    <a href="admin.php">Powrót do panelu administratora</a>
    </body>
    </html>

</div>
</body>
</html>