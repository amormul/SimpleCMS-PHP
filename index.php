<?php
session_start();  // Начало сессии для отслеживания пользовательских данных

// Сессия истекает после 30 минут бездействия
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();  // Очистка всех данных сессии
    session_destroy();  // Уничтожение сессии
    header('Location: /auth/login');  // Перенаправление пользователя на страницу логина
    exit;
}

// Обновляем время последней активности
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();  // Обновление времени последней активности пользователя
}

// Подключение необходимых файлов
include_once 'config/Database.php';  // Конфигурация для базы данных
include_once 'models/User.php';  // Модель пользователя
include_once 'models/Article.php';  // Модель статьи
include_once 'middleware/AuthMiddleware.php';  // Миддлвар для аутентификации (если нужно)
include_once 'controllers/AuthController.php';  // Контроллер для аутентификации
include_once 'controllers/AdminController.php';  // Контроллер для администрирования
include_once 'controllers/ArticleController.php';  // Контроллер для управления статьями

// Получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// Получаем URI запроса
$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?');  // Убираем параметры GET из запроса (например, ?id=123)

// Маршрутизация в зависимости от запрашиваемого URI
switch ($request) {
    case '/':
        // Главная страница, контроллер для отображения списка статей
        $controller = new ArticleController($db);
        $controller->index();  // Метод для отображения всех статей
        break;

    case '/auth/login':
        // Страница логина
        $controller = new AuthController($db);
        $controller->login();  // Метод для отображения формы логина
        break;

    case '/auth/logout':
        // Логаут (выход)
        $controller = new AuthController($db);
        $controller->logout();  // Метод для выхода из системы
        break;

    case '/admin/dashboard':
        // Панель администратора
        $controller = new AdminController($db);
        $controller->dashboard();  // Метод для отображения панели администратора
        break;

    case '/admin/users':
        // Страница управления пользователями
        $controller = new AdminController($db);
        $controller->users();  // Метод для отображения списка пользователей
        break;

    case '/admin/users/create':
        // Страница создания пользователя
        $controller = new AdminController($db);
        $controller->createUser();  // Метод для создания нового пользователя
        break;

    case '/admin/users/delete':
        // Удаление пользователя
        if (isset($_GET['id'])) {  // Проверка, передан ли ID для удаления
            $controller = new AdminController($db);
            $controller->deleteUser($_GET['id']);  // Удаляем пользователя с указанным ID
        }
        header('Location: /admin/users');  // Перенаправление обратно на страницу пользователей
        break;

    case '/admin/articles':
        // Страница управления статьями
        $controller = new AdminController($db);
        $controller->articles();  // Метод для отображения списка статей
        break;

    case '/admin/articles/create':
        // Страница создания статьи
        $controller = new AdminController($db);
        $controller->createArticle();  // Метод для создания новой статьи
        break;

    case '/admin/articles/delete':
        // Удаление статьи
        if (isset($_GET['id'])) {  // Проверка, передан ли ID для удаления
            $controller = new AdminController($db);
            $controller->deleteArticle($_GET['id']);  // Удаляем статью с указанным ID
        }
        header('Location: /admin/articles');  // Перенаправление обратно на страницу статей
        break;

    default:
        // Страница для отображения статьи по ID или редактирования
        if (preg_match('/^\/article\/view\/(\d+)$/', $request, $matches)) {
            $controller = new ArticleController($db);
            $controller->view($matches[1]);  // Метод для просмотра статьи по ID
        } elseif (preg_match('/^\/admin\/users\/edit\/(\d+)$/', $request, $matches)) {
            $controller = new AdminController($db);
            $controller->editUser($matches[1]);  // Метод для редактирования пользователя по ID
        } elseif (preg_match('/^\/admin\/articles\/edit\/(\d+)$/', $request, $matches)) {
            $controller = new AdminController($db);
            $controller->editArticle($matches[1]);  // Метод для редактирования статьи по ID
        } else {
            // Если не найдено соответствие маршруту
            http_response_code(404);  // Отправляем код 404 (страница не найдена)
            include_once 'views/404.php';  // Отображаем страницу 404
        }
        break;
}