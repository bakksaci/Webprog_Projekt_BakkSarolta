<?php
include ("index.php");
require_once ("conn.php");
global $conn;


if (isset($_POST['bookId']) && isset($_POST['userId'])) {
    $bookId = $_POST['bookId'];
    $userId = $_POST['userId'];

    if (isset($_POST['from']) && isset($_POST['to'])) {
        echo "borrowing";
        $from = $_POST['from'];
        $to = $_POST['to'];

        $sqlInsert = "INSERT INTO borrows (book_id, user_id, borrow_from, borrow_to) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("iiss", $bookId, $userId, $from, $to);
        $stmtInsert->execute();

        $sqlUpdate = "UPDATE books SET status = 0 WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $bookId);
        $stmtUpdate->execute();

        $stmtInsert->close();
        $stmtUpdate->close();
        $conn->close();

        header("Location: search.php");
        echo "sikeres kölcsönzés!";
        exit();
    }

    else
    {
        $sql = "SELECT id, img, title, category, author,status FROM books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();

        if (!$book) {
            // Ha a könyv nem található, visszairányít a főoldalra
            header('Location: search.php');
            exit();
        }
    }

}
else {
    // Ha nincs könyv azonosító, visszairányít a főoldalra
    header('Location: search.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Könyv kölcsönzése</title>

</head>
<body>
    <div class="borrow-page">
        <div class="book-details">
            <h2><?php echo $book['title'] ?> kölcsönzése
            </h2>
            <img width="100px" height="150px" src='  <?php echo $book['img'] ?>'>
            <p>Szerző: <?php echo $book['author'] ?>  </p>
            <p>kategória:  <?php echo $book['category'] ?></p>
            <p>Azonosító:  <?php echo $book['id'] ?></p>
        </div>
        <form action="" method="post">
            <input type="hidden" name="bookId" value="<?php echo $book['id']  ?>">
            <input type="hidden" name="userId" value="<?php echo $userId  ?>">

            <label for="from">Mettől?</label>
            <input type="date" name="from" value="<?php echo date('Y-m-d'); ?>" required> <br>

            <label for="to">Meddig?</label>
            <input type="date" name="to" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>

            <input type="submit" value="Kölcsönzés">

        </form>
    </div>

</body>
</html>

