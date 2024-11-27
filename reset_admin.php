<?php
include_once 'config/Database.php';
include_once 'models/User.php';

// Создаем объект для работы с базой данных
$database = new Database();
$db = $database->getConnection();

// Создаем объект пользователя и сбрасываем пароль администратора
$user = new User($db);
if ($user->resetAdminPassword()) {
    // Успешный сброс пароля
    echo "Admin password has been reset to 'admin'";
} else {
    // Ошибка при сбросе пароля
    echo "Failed to reset admin password";
}