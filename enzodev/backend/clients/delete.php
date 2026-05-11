<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "../config/database.php";
$db = new Database();

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $db->deleteClient($id);
    header("Location: list.php");
    exit;
}
