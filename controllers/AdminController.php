<?php
/**
 * AdminController - Контроллер для администрирования пользователей и статей.
 */
class AdminController {
    private $user;
    private $article;

    /**
     * AdminController constructor.
     * Инициализация контроллера, требующего авторизацию, и загрузки моделей для пользователей и статей.
     *
     * @param mysqli $db Подключение к базе данных.
     */
    public function __construct($db) {
        // Проверка авторизации с помощью промежуточного ПО
        AuthMiddleware::requireAuth();

        // Инициализация моделей для работы с пользователями и статьями
        $this->user = new User($db);
        $this->article = new Article($db);
    }

    /**
     * Отображение главной страницы админ панели.
     */
    public function dashboard() {
        // Подготовка данных для отображения имени пользователя
        $data = [
            'username' => $_SESSION['user_login']
        ];

        // Включение представления для главной страницы админ панели
        include_once __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Отображение списка пользователей.
     */
    public function users() {
        // Получение всех пользователей из базы данных
        $users = $this->user->readAll();

        // Включение представления для списка пользователей
        include_once __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Создание нового пользователя.
     */
    public function createUser() {
        $data = [
            'errors' => [],
            'login' => ''
        ];

        // Обработка формы при POST запросе
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Очистка данных для предотвращения XSS атак
            $data['login'] = htmlspecialchars($login);

            // Проверка логина на валидность
            if (empty($login)) {
                $data['errors']['login'] = 'Логін є обов\'язковим';
            } elseif (strlen($login) < 3) {
                $data['errors']['login'] = 'Логін повинен містити мінімум 3 символи';
            }

            // Проверка пароля на валидность
            if (empty($password)) {
                $data['errors']['password'] = 'Пароль є обов\'язковим';
            } elseif (strlen($password) < 4) {
                $data['errors']['password'] = 'Пароль повинен містити мінімум 4 символи';
            }

            // Проверка совпадения паролей
            if ($password !== $confirm_password) {
                $data['errors']['confirm_password'] = 'Паролі не співпадають';
            }

            // Если нет ошибок, создание пользователя
            if (empty($data['errors'])) {
                if ($this->user->create($login, $password)) {
                    // Перенаправление на страницу списка пользователей
                    header('Location: /admin/users');
                    exit;
                } else {
                    $data['errors']['general'] = 'Помилка при створенні користувача';
                }
            }
        }

        // Включение представления для создания пользователя
        include_once __DIR__ . '/../views/admin/users/create.php';
    }

    /**
     * Редактирование пользователя.
     *
     * @param int $id ID пользователя.
     */
    public function editUser($id) {
        // Получаем пользователя по ID
        $user = $this->user->readOne($id);
        if (!$user) {
            // Если пользователь не найден, перенаправляем на страницу списка
            header('Location: /admin/users');
            exit;
        }

        $data = [
            'user' => $user,
            'errors' => []
        ];

        // Обработка формы при POST запросе
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';

            // Проверка логина
            if (empty($login)) {
                $data['errors']['login'] = 'Логін є обов\'язковим';
            } elseif (strlen($login) < 3) {
                $data['errors']['login'] = 'Логін повинен містити мінімум 3 символи';
            }

            // Проверка пароля, если он был изменен
            if (!empty($password) && strlen($password) < 4) {
                $data['errors']['password'] = 'Пароль повинен містити мінімум 4 символи';
            }

            // Если нет ошибок, обновляем пользователя
            if (empty($data['errors'])) {
                if ($this->user->update($id, $login, $password)) {
                    // Перенаправление на страницу списка пользователей
                    header('Location: /admin/users');
                    exit;
                } else {
                    $data['errors']['general'] = 'Помилка при оновленні користувача';
                }
            }
        }

        // Включение представления для редактирования пользователя
        include_once __DIR__ . '/../views/admin/users/edit.php';
    }

    /**
     * Удаление пользователя.
     *
     * @param int $id ID пользователя.
     */
    public function deleteUser($id) {
        // Проверка, что нельзя удалить текущего авторизованного пользователя
        if ($id != $_SESSION['user_id']) {
            $this->user->delete($id);
        }
        // Перенаправление на страницу списка пользователей
        header('Location: /admin/users');
        exit;
    }

    /**
     * Отображение списка статей.
     */
    public function articles() {
        // Получение всех статей из базы данных
        $articles = $this->article->readAll();

        // Включение представления для списка статей
        include_once __DIR__ . '/../views/admin/articles.php';
    }

    /**
     * Создание новой статьи.
     */
    public function createArticle() {
        $data = [
            'errors' => [],
            'title' => '',
            'content' => ''
        ];

        // Обработка формы при POST запросе
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            // Очистка данных
            $data['title'] = htmlspecialchars($title);
            $data['content'] = htmlspecialchars($content);

            // Проверка данных
            if (empty($title)) {
                $data['errors']['title'] = 'Заголовок є обов\'язковим';
            }
            if (empty($content)) {
                $data['errors']['content'] = 'Текст статті є обов\'язковим';
            }

            // Если нет ошибок, создание статьи
            if (empty($data['errors'])) {
                $this->article->title = $title;
                $this->article->content = $content;
                $this->article->author_id = $_SESSION['user_id'];

                if ($this->article->create()) {
                    // Перенаправление на страницу списка статей
                    header('Location: /admin/articles');
                    exit;
                } else {
                    $data['errors']['general'] = 'Помилка при створенні статті';
                }
            }
        }

        // Включение представления для создания статьи
        include_once __DIR__ . '/../views/admin/articles/create.php';
    }

    /**
     * Редактирование статьи.
     *
     * @param int $id ID статьи.
     */
    public function editArticle($id) {
        // Получаем статью по ID
        $article = $this->article->readOne($id);
        if (!$article || $article->num_rows === 0) {
            // Если статья не найдена, перенаправляем на страницу списка
            header('Location: /admin/articles');
            exit;
        }

        $data = [
            'article' => $article->fetch_assoc(),
            'errors' => []
        ];

        // Обработка формы при POST запросе
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            // Проверка данных
            if (empty($title)) {
                $data['errors']['title'] = 'Заголовок є обов\'язковим';
            }
            if (empty($content)) {
                $data['errors']['content'] = 'Текст статті є обов\'язковим';
            }

            // Если нет ошибок, обновляем статью
            if (empty($data['errors'])) {
                $this->article->id = $id;
                $this->article->title = $title;
                $this->article->content = $content;

                if ($this->article->update()) {
                    // Перенаправление на страницу списка статей
                    header('Location: /admin/articles');
                    exit;
                } else {
                    $data['errors']['general'] = 'Помилка при оновленні статті';
                }
            }
        }

        // Включение представления для редактирования статьи
        include_once __DIR__ . '/../views/admin/articles/edit.php';
    }

    /**
     * Удаление статьи.
     *
     * @param int $id ID статьи.
     */
    public function deleteArticle($id) {
        // Удаляем статью по ID
        $this->article->delete($id);

        // Перенаправление на страницу списка статей
        header('Location: /admin/articles');
        exit;
    }
}