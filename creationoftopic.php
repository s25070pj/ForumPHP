<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tworzenie nowego tematu</title>
    <link rel="stylesheet" href="style.css">
</head>
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
                } ?>
            </li>
        </ul>
        <form method="POST" action="logout.php">
            <input type="submit" value="Wyloguj">

        </form>
    </nav>
</header>
<div class="container">
    <body>
    <h2>Tworzenie nowego tematu</h2>
    <form method="POST" action="createtopic.php">
        <label>Tytuł:</label>
        <input type="text" name="title" required><br>

        <label>Treść:</label>
        <textarea name="content" required></textarea><br>

        <label>Kategoria:</label>
        <select name="category" required>
            <?php
            // Połączenie z bazą danych
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "forum";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Błąd połączenia: " . $conn->connect_error);
            }

            // Pobranie listy kategorii z bazy danych
            $sql = "SELECT * FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['category_name'] . '">' . $row['category_name'] . '</option>';
                }
            }

            $conn->close();
            ?>
        </select><br>

        <input type="submit" value="Utwórz">
    </form>
</div>

</body>
</html>
