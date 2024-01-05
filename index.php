<style>
    table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    tr:nth-child(even){background-color: #f2f2f2;}

    tr:hover {background-color: #ddd;}

    th {
        padding-top: 12px;
        padding-bottom: 12px;
        color: white;
        background-color: #04AA6D;}

    input[type=button], input[type=submit], input[type=reset] {
        background-color: dodgerblue;
        border: none;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        margin: 4px 2px;
        cursor: pointer;
    }

    input[type=text],  input[type=date], input[type=number] {
        padding: 2px;
        font-size: 1.1rem;
    }

    #pages{
        margin-top: 20px;
        width: 100%;
        text-align: center;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    #pages a{
        text-decoration: none;
        color: black;
        background: white;
        font-size: 1.1rem;
        padding: 5px 10px;
        border: 1px solid black;
        margin-right: 10px;
    }



</style>


<?php
session_start();

include("navigation.php");
echo "<br> <br>";

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}




