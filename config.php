<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=school_db;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $ex) {
    die('Database connection failed. Import database.sql first. ' . e($ex->getMessage()));
}
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function require_login() {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
}
