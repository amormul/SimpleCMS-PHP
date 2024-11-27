<?php
/**
 * AuthController - Контроллер для аутентификации пользователей.
 */
class AuthController {
    private $user; // Модель пользователя для работы с данными
    private $errors = []; // Массив для хранения ошибок валидации

    /**
     * Конструктор контроллера.
     * Инициализирует объект модели User для работы с пользователями.
     *
     * @param mysqli $db Объект соединения с базой данных.
     */
    public function __construct($db) {
        // Инициализация модели для работы с пользователями
        $this->user = new User($db);
    }

    /**
     * Валидация данных при входе.
     * Проверяет, что логин и пароль соответствуют требованиям безопасности.
     *
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @return bool Возвращает true, если данные валидны, иначе false.
     */
    private function validateLoginInput($login, $password) {
        // Проверка логина
        if (empty($login)) {
            $this->errors['login'] = 'Логін є обов\'язковим';
        } elseif (strlen($login) < 3) {
            $this->errors['login'] = 'Логін повинен містити мінімум 3 символи';
        } elseif (strlen($login) > 50) {
            $this->errors['login'] = 'Логін не може бути довшим за 50 символів';
        }

        // Проверка пароля
        if (empty($password)) {
            $this->errors['password'] = 'Пароль є обов\'язковим';
        } elseif (strlen($password) < 4) {
            $this->errors['password'] = 'Пароль повинен містити мінімум 4 символи';
        }

        // Если ошибок нет, возвращаем true
        return empty($this->errors);
    }

    /**
     * Страница для входа пользователя.
     * Проверяет введенные данные и выполняет аутентификацию.
     */
    public function login() {
        // Проверка, что пользователь не авторизован (используется middleware)
        AuthMiddleware::requireGuest();

        $data = [
            'login' => '', // Логин пользователя
            'errors' => [] // Массив ошибок
        ];

        // Обработка формы при отправке данных
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем данные из формы и обрабатываем
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $data['login'] = htmlspecialchars($login); // Экранируем логин для предотвращения XSS

            // Валидируем данные
            if ($this->validateLoginInput($login, $password)) {
                // Пытаемся авторизовать пользователя
                if ($this->user->login($login, $password)) {
                    // Перегенерируем ID сессии для безопасности
                    session_regenerate_id(true);
                    // Сохраняем данные пользователя в сессии
                    $_SESSION['user_id'] = $this->user->id;
                    $_SESSION['user_login'] = $this->user->login;
                    $_SESSION['last_activity'] = time(); // Время последней активности

                    // Перенаправляем на страницу администратора
                    header('Location: /admin/dashboard');
                    exit;
                } else {
                    // Ошибка авторизации
                    $this->errors['auth'] = 'Невірний логін або пароль';
                }
            }
            // Передаем ошибки в представление
            $data['errors'] = $this->errors;
        }

        // Показываем форму входа с ошибками, если они есть
        include_once 'views/auth/login.php';
    }

    /**
     * Выход из системы.
     * Удаляет все данные сессии и перенаправляет на страницу входа.
     */
    public function logout() {
        // Стартуем сессию
        session_start();
        // Удаляем все данные из сессии
        session_unset();
        session_destroy();
        // Перенаправляем на страницу входа
        header('Location: /auth/login');
        exit;
    }
}