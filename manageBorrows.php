
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kölcsönzések kezelése</title>
</head>


<?php
require_once("conn.php");
include("index.php");
echo "<h2>Kölcsönzések kezelése</h2>";
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_borrow'])) {
        $borrow_id = $_POST['return_borrow'];

        // Retrieve the book_id associated with the borrow record
        $getBookIdSql = "SELECT book_id FROM borrows WHERE id = ?";
        $getBookIdStmt = $conn->prepare($getBookIdSql);
        $getBookIdStmt->bind_param("i", $borrow_id);
        $getBookIdStmt->execute();
        $getBookIdResult = $getBookIdStmt->get_result();
        $bookIdRow = $getBookIdResult->fetch_assoc();
        $bookId = $bookIdRow['book_id'];
        $getBookIdStmt->close();

        // Set the 'returned' field to 1 for the borrow record
        $updateReturnedSql = "UPDATE borrows SET returned = 1 WHERE id = ?";
        $updateReturnedStmt = $conn->prepare($updateReturnedSql);
        $updateReturnedStmt->bind_param("i", $borrow_id);

        if ($updateReturnedStmt->execute()) {
            // Set the 'status' field to 1 for the associated book
            $updateBookStatusSql = "UPDATE books SET status = 1 WHERE id = ?";
            $updateBookStatusStmt = $conn->prepare($updateBookStatusSql);
            $updateBookStatusStmt->bind_param("i", $bookId);

            if ($updateBookStatusStmt->execute()) {
                echo "Book returned successfully!";
            } else {
                echo "Error updating book status: " . $updateBookStatusStmt->error;
            }

            $updateBookStatusStmt->close();
        } else {
            echo "Error updating return status: " . $updateReturnedStmt->error;
        }

        $updateReturnedStmt->close();
    }

    // Retrieve and display borrows
    $sql = "SELECT borrows.id, books.title, users.name, borrows.borrow_from, borrows.borrow_to, borrows.returned 
            FROM borrows 
            INNER JOIN books ON borrows.book_id = books.id 
            INNER JOIN users ON borrows.user_id = users.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>Cím</th>
                <th>Olvasó</th>
                <th>Kölcsönzés kezdete</th>
                <th>Kölcsönzés vége</th>
                <th>Visszahozva?</th>
                <th></th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["borrow_from"] . "</td>";
            echo "<td>" . $row["borrow_to"] . "</td>";
            echo "<td>" . ($row["returned"] ? "Igen" : "Nem") . "</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='return_borrow' value='" . $row["id"] . "'>
                        <input type='submit' value='Visszahozva'>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No borrows found.";
    }
    ?>


    </body>
    </html>

    <?php
} else {
    header("Location: index.php");
}
?>
