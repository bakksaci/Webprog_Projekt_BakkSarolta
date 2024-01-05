<?php
include ("index.php");
require_once("conn.php");
global  $conn;


// Ellenőrizze, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Lekérdezés a kölcsönzésekről a bejelentkezett felhasználó számára
$userId = $_SESSION['userid'];
$sql = "SELECT borrows.id, borrows.returned, borrows.borrow_from, borrows.borrow_to, books.title, books.author, books.img
        FROM borrows 
        INNER JOIN books ON borrows.book_id = books.id 
        WHERE borrows.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saját kölcsönzéseim</title>

</head>
<body>
    <h2>Saját kölcsönzéseim</h2>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th colspan=2>Cím</th>
                    <th>Szerző</th>
                    <th>Mettől</th>
                    <th>Meddig</th>
                    <th>Visszahozva?</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td> <img src=' <?php echo $row["img"] ?>' width='100px'></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td><?php echo $row['borrow_from']; ?></td>
                        <td><?php echo $row['borrow_to']; ?></td>
                        <?php echo "<td>" . ($row["returned"] ? "Igen" : "Nem") . "</td>"; ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Nincs kölcsönzés jelenleg.</p>
    <?php } ?>

    <a href="index.php">Vissza</a>
</body>
</html>
