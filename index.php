<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ForumPHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="form-container">

        <h2>Logowanie</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label>Nazwa użytkownika:</label>
            <input type="text" name="login_username" required><br>

            <label>Hasło:</label>
            <input type="password" name="login_password" required><br>

            <input type="submit" value="Zaloguj">
        </form>
    </div>

    <div class="form-container">
        <h2>Rejestracja użytkownika</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label>Nazwa użytkownika:</label>
            <input type="text" name="register_username" required><br>

            <label>Hasło:</label>
            <input type="password" name="register_password" required><br>

            <input type="submit" value="Zarejestruj">
        </form>
    </div>
</div>
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

echo "Połączono z bazą danych.";

// Obsługa formularza logowania
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login_username"]) && isset($_POST["login_password"])) {
        $loginUsername = $_POST["login_username"];
        $loginPassword = $_POST["login_password"];

        // Walidacja danych logowania (dodaj odpowiednie warunki)
        if (empty($loginUsername) || empty($loginPassword)) {
            echo "Proszę wypełnić wszystkie pola logowania.";
        } else {
            // Funkcja do uwierzytelniania użytkownika
            function authenticateUser($username, $password, $conn)
            {
                // Sprawdzenie, czy użytkownik o podanej nazwie istnieje w bazie danych
                $query = "SELECT * FROM users WHERE username = '$username'";
                $result = $conn->query($query);
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $hashedPassword = $row['password'];
                    $accountType = $row['account_type']; // Odczytanie pola account_type

                    // Sprawdzenie poprawności hasła
                    if (password_verify($password, $hashedPassword)) {
                        $_SESSION['username'] = $username;
                        $_SESSION['account_type'] = $accountType; // Zapisanie wartości account_type w sesji
                        header("Location: panel.php");
                        exit();
                    } else {
                        return "Nieprawidłowe hasło.";
                    }
                } else {
                    return "Nieprawidłowa nazwa użytkownika.";
                }
            }

            // Wywołanie funkcji authenticateUser()
            $loginResult = authenticateUser($loginUsername, $loginPassword, $conn);

            echo $loginResult;
        }
    }

    // Obsługa formularza rejestracji
    if (isset($_POST["register_username"]) && isset($_POST["register_password"])) {
        $registerUsername = $_POST["register_username"];
        $registerPassword = $_POST["register_password"];

        // Walidacja danych rejestracji (dodaj odpowiednie warunki)
        if (empty($registerUsername) || empty($registerPassword)) {
            echo "Proszę wypełnić wszystkie pola rejestracji.";
        } else {
            // Funkcja do rejestracji użytkownika
            function registerUser($username, $password, $conn)
            {
                // Sprawdzenie, czy użytkownik o podanej nazwie już istnieje
                $query = "SELECT * FROM users WHERE username = '$username'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    return "Nazwa użytkownika jest już zajęta";
                }

                // Haszowanie hasła
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Dodanie użytkownika do bazy danych
                $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
                if ($conn->query($query) === TRUE) {
                    return "Rejestracja zakończona sukcesem";
                } else {
                    return "Wystąpił problem podczas rejestracji";
                }
            }

            // Wywołanie funkcji registerUser()
            $registrationResult = registerUser($registerUsername, $registerPassword, $conn);

            echo $registrationResult;
        }
    }
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>

<script src="script.js"></script>
</body>
</html>
