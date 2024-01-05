<?php
require_once ("conn.php");
global $conn;

session_start();

// Ellenőrizzük, hogy a felhasználó már be van-e jelentkezve
if (isset($_SESSION['userid'])) {
    header("Location: index.php"); // Átirányítás a főoldalra, ha már be van jelentkezve
    exit();
}

// Ellenőrizzük, hogy a POST kérés beküldték-e (az űrlap elküldése)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ellenőrizze a felhasználónevet és a jelszót
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Kérdezzük le az adatbázisból a user-t az email alapján
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        // Sikeres bejelentkezés, beállítjuk a session változót
        $_SESSION['userid'] = $user['id'];
        $_SESSION['userName'] = $user['name'];
        header("Location: index.php"); // Átirányítás a főoldalra
        exit();
    }
    else {
        $error = "Hibás email vagy jelszó";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h2>Login</h2>

<?php if (isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>

<form method="post" action="">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Jelszó:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Bejelentkezés">
</form>

<p> Nem vagy még ovlasó? <a href="register.php">Regisztrálj! </a></p>
</body>
</html>