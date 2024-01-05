<?php
global $conn;
require_once("conn.php");
include("index.php");

// Ellenőrizze a kapcsolatot
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Alapértelmezett limit beállítása
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? $_GET['limit'] : 10;

if ($limit > 25) $limit = 25;

$_GET['limit'] = $limit;

// GET paraméterek ellenőrzése és értékük kinyerése
$title = isset($_GET['title']) ? $_GET['title'] : '';
$author = isset($_GET['author']) ? $_GET['author'] : '';
$available = isset($_GET['available']) ? $_GET['available'] : '';

// Az aktuális oldal kinyerése
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Az OFFSET számítása az aktuális oldal alapján
$offset = ($page - 1) * $limit;

// SQL lekérdezés összeállítása a paraméterek alapján
$sql = "SELECT * FROM books WHERE 1";
$countSQL = "SELECT COUNT(*) as total FROM books WHERE 1";

if (!empty($title)) {
    $sql .= " AND title LIKE '%$title%'";
    $countSQL .= " AND title LIKE '%$title%'";
}

if (!empty($author)) {
    $sql .= " AND author LIKE '%$author%'";
    $countSQL .= " AND author LIKE '%$author%'";
}

if (!empty($available)) {
    $sql .= " AND status = 1";
    $countSQL .= " AND status = 1";
}

$sql .= " LIMIT $limit OFFSET $offset";

// Lekérdezés az összes könyv lekéréséhez
$result = $conn->query($sql);

// Összes találat számának lekérdezése
$countResult = $conn->query($countSQL);
$countRow = $countResult->fetch_assoc();

$totalPages = ceil($countRow['total'] / $limit);

echo "Összes találat: ". $countRow['total'];

// Kapcsolat bezárása
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Könyvek listája</title>
    <style>
        .active {
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>
<!-- Kereső űrlap -->
<form action="" method="get">
    <label for="author">Szerző:</label>
    <input type="text" id="author" name="author" value="<?php echo isset($_GET['author']) ? htmlspecialchars($_GET['author']) : ''; ?>">

    <label for="title">Cím:</label>
    <input type="text" id="title" name="title" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">

    <label for="limit">Limit / oldal:</label>
    <input type="number" id="limit" min=1 name="limit" value="<?php echo isset($_GET['limit']) ? htmlspecialchars($_GET['limit']) : ''; ?>">

    <label for="available">Elérhető</label>
    <input type="checkbox" name="available" value="1" <?php if(isset($_GET['available'])) echo 'checked'; ?>>


    <input type="submit" value="Szűrés">
</form>

<h2>Könyvek listája</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Kép</th><th>Szerző</th><th>Cím</th><th>Kategória</th><th>Elérhető</th><th>Kölcsönzés</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<form method='post' action='borrow.php'>";
        echo "<input type='hidden' value='" . $row["id"] . "' name='bookId'>";
        echo "<input type='hidden' value='" . $_SESSION["userid"] . "' name='userId'>";
        echo "<tr>";
        echo "<td> <img src=' " . $row["img"] . "' width='100px' height='150px'></td>";
        echo "<td>" . $row["author"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . ($row["status"] == 0 ? 'nem elérhető' : 'elérhető') . "</td>";

        if($row["status"]){
            echo "<td>";
            echo "<input type='submit' value='kikölcsönöz'>";
            echo "</td>";
        }

        echo "</tr>";
        echo "</form>";
    }

    echo "</table>";
    // Pagination links
    echo "<div id='pages'> ";
    echo "<div id='pages'> ";
    for ($i = 1; $i <= $totalPages; $i++) {
        $backgroundStyle = ($i == $page) ? 'background-color: #04AA6D; color: white;' : '';
        echo "<a style='$backgroundStyle' href='?page=$i&limit=$limit&author=$author&title=$title&available=$available'>$i </a> ";
    }


    echo "</div>";
} else {
    echo "Nincsenek könyvek az adatbázisban.";
}
?>

</body>