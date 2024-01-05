<?php
require_once ("conn.php");
global $conn;

$sql = "SELECT admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


?>

<style>
    div a{text-decoration: none; background: #04AA6D; color: #dddddd; font-weight: bold;}
    div a:hover{background: white; color: black;}
    #nav{
        display: flex; flex-direction: row; justify-content: space-evenly; position: fixed; align-items: center; align-content: space-between; width: 100%; padding: 10px 0; margin-top: -10px;
        background: #04AA6D; color:white;
    }
</style>
<div id="nav">
    <i>Üdv, <?php echo $_SESSION["userName"] ?>!</i>
    <?php  if($user["admin"]){
        echo "<a href='addBook.php'>új könyv felvétele</a>";
        echo "<a href='manageBorrows.php'>Kölcsönzések kezelése</a>";
    }?>
    <a href="search.php">Könyvek keresése</a>
    <a href="myBorrows.php"> Saját kölcsönzéseim</a>
    <a href="logout.php">Kilépés</a>
</div>