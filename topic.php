<?php
session_start();


// Połączenie z bazą danych
$servername = "5.39.83.70";
$username = "adi";
$password = "superHaslo1$";
$dbname = "adi_db";


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
$sql = "SELECT c.comment_id, c.comment_content, c.comment_date, u.username
        FROM comments c
        JOIN users u ON c.user_id = u.user_id
        WHERE c.topic_id = $topicID";

$comments = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temat: <?php echo $topicTitle; ?></title>
    <link rel="stylesheet" href="style.css">

    <script>
        function showReportForm(commentId) {
            var reportForm = document.getElementById('report-form-' + commentId);
            reportForm.style.display = 'block';
        }
    </script>
</head>
<body>
<header>
    <nav>
        <ul class="navigation">
            <li><a href="panel.php">Strona główna</a></li>
            <li><a href="search.php">Wyszukiwanie</a></li>
            <li><a href="creationoftopic.php">Utwórz nowy temat</a></li>
            <li><?php
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
    <h2>Temat: <?php echo $topicTitle; ?></h2>
    <p><strong>Autor:</strong> <?php echo $username; ?></p>
    <p><strong>Data:</strong> <?php echo $topicDate; ?></p>
    <p><?php echo $topicContent; ?></p>

    <hr>

    <h3>Komentarze</h3>

    <?php
    if ($comments->num_rows > 0) {
        while ($comment = $comments->fetch_assoc()) {
            $commentID = $comment['comment_id'];
            $commentContent = $comment['comment_content'];
            $commentDate = $comment['comment_date'];
            $commentAuthor = $comment['username'];
            ?>

            <div class="comment">
                <p><strong>Autor:</strong> <?php echo $commentAuthor; ?></p>
                <p><strong>Data:</strong> <?php echo $commentDate; ?></p>
                <p><?php echo $commentContent; ?></p>

                <div class="report-button" onclick="showReportForm(<?php echo $commentID; ?>)">Zgłoś</div>

                <form id="report-form-<?php echo $commentID; ?>" class="report-form" method="POST" action="reportcomment.php">
                    <input type="hidden" name="comment_id" value="<?php echo $commentID; ?>">
                    <input type="hidden" name="topic_id" value="<?php echo $topicID; ?>">
                    <label>Treść zgłoszenia:</label><br>
                    <textarea name="report_content" required></textarea><br>
                    <input type="submit" value="Zgłoś">
                </form>
            </div>

            <hr>

            <?php
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

</div>

<footer>
    <p>Forum &copy; 2023. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>
