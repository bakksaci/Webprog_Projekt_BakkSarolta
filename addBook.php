<?php
    require_once ("conn.php");
    include ("index.php");

    global $conn;

    $sql = "SELECT admin FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

// Check if the user is an admin
if ($user["admin"]) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form data processing for adding a new book
        $title = $_POST["title"];
        $author = $_POST["author"];
        $category = $_POST["category"];
        $img = $_POST["img"];

        $sql = "INSERT INTO books (title, author, category, img) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $author, $category, $img);

        if ($stmt->execute()) {
            echo "Book added successfully!";
        } else {
            echo "Error adding book: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Új könyv</title>
    </head>
    <body>
    <h2>Új könyv felvétele</h2>
    <form method="post" action="">
        <label for="title">Cím:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="author">Szerző:</label>
        <input type="text" id="author" name="author" required><br>

        <label for="category">Kategória:</label>
        <input type="text" id="category" name="category"><br>

        <label for="img">Kép url:</label>
        <input type="text" id="img" name="img"><br>

        <input type="submit" value="Add Book">
    </form>
    </body>
    </html>
    <?php
} else {
    header("Location: index.php");
}
?>
