<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Truy cập bị từ chối.");
}
require_once '../db.php';
?>