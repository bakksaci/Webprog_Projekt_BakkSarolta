<?php
$servername = "localhost";
$username = "root"; // Az adatbázis felhasználóneve
$password = ""; // Az adatbázis jelszava
$dbname = "konyvtar";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);