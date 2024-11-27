<?php
session_start();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Блог</a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="nav-link" href="/admin/dashboard">Адмінпанель</a>
                <a class="nav-link" href="/auth/logout">Вийти</a>
            <?php else: ?>
                <a class="nav-link" href="/auth/login">Увійти</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container mt-4">