<?php
//$servername = "5.39.83.70";
//$username = "adi";
//$password = "superHaslo1$";
//$dbname = "adi_db";
//
//
//// Funkcja do rejestracji użytkownika
//function registerUser($username, $password, $conn) {
//    // Sprawdzenie, czy użytkownik o podanej nazwie już istnieje
//    $query = "SELECT * FROM users WHERE username = '$username'";
//    $result = $conn->query($query);
//    if ($result->num_rows > 0) {
//        return "Nazwa użytkownika jest już zajęta";
//    }
//
//    // Haszowanie hasła
//    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//
//    // Dodanie użytkownika do bazy danych
//    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
//    if ($conn->query($query) === TRUE) {
//        return "Rejestracja zakończona sukcesem";
//    } else {
//        return "Wystąpił problem podczas rejestracji";
//    }
//}
