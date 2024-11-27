<?php
/**
 * Article - Класс для работы со статьями в базе данных.
 * Выполняет операции CRUD: создание, чтение, обновление и удаление.
 */
class Article {
    private $conn; // Объект соединения с базой данных
    private $table = 'articles'; // Название таблицы в базе данных

    public $id; // Идентификатор статьи
    public $title; // Заголовок статьи
    public $content; // Содержание статьи
    public $author_id; // Идентификатор автора статьи
    public $created_at; // Дата создания статьи

    /**
     * Конструктор класса.
     * Инициализирует объект и подключение к базе данных.
     *
     * @param mysqli $db Объект соединения с базой данных.
     */
    public function __construct($db) {
        $this->conn = $db; // Инициализация соединения с базой данных
    }

    /**
     * Создает новую статью в базе данных.
     *
     * @return bool Возвращает true при успешном выполнении, иначе false.
     */
    public function create() {
        // SQL запрос для вставки новой статьи
        $query = "INSERT INTO " . $this->table . " (title, content, author_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query); // Подготовка SQL запроса

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error); // Логируем ошибку
            return false; // Возвращаем false, если подготовка запроса не удалась
        }

        // Привязываем параметры к запросу
        $stmt->bind_param("ssi", $this->title, $this->content, $this->author_id);
        return $stmt->execute(); // Выполняем запрос и возвращаем результат
    }

    /**
     * Читает все статьи из базы данных, включая имя автора.
     *
     * @return mysqli_result Результат выполнения запроса.
     */
    public function readAll() {
        // SQL запрос для получения всех статей с именами авторов
        $query = "SELECT a.*, u.login as author_name FROM " . $this->table . " a 
                 LEFT JOIN users u ON a.author_id = u.id 
                 ORDER BY a.created_at DESC";
        return $this->conn->query($query); // Выполняем запрос и возвращаем результат
    }

    /**
     * Читает одну статью по ее ID.
     *
     * @param int $id Идентификатор статьи.
     * @return mysqli_result Результат выполнения запроса.
     */
    public function readOne($id) {
        // SQL запрос для получения одной статьи по ID с именем автора
        $query = "SELECT a.*, u.login as author_name FROM " . $this->table . " a 
                 LEFT JOIN users u ON a.author_id = u.id 
                 WHERE a.id = ?";
        $stmt = $this->conn->prepare($query); // Подготовка SQL запроса

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error); // Логируем ошибку
            return false; // Возвращаем false, если подготовка запроса не удалась
        }

        $stmt->bind_param("i", $id); // Привязываем параметр ID
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error); // Логируем ошибку выполнения
            return false;
        }

        return $stmt->get_result(); // Возвращаем результат выполнения запроса
    }

    /**
     * Обновляет статью по ID.
     *
     * @return bool Возвращает true при успешном выполнении, иначе false.
     */
    public function update() {
        // SQL запрос для обновления статьи
        $query = "UPDATE " . $this->table . " SET title = ?, content = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query); // Подготовка SQL запроса

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error); // Логируем ошибку
            return false; // Возвращаем false, если подготовка запроса не удалась
        }

        $stmt->bind_param("ssi", $this->title, $this->content, $this->id); // Привязываем параметры к запросу
        return $stmt->execute(); // Выполняем запрос и возвращаем результат
    }

    /**
     * Удаляет статью по ID.
     *
     * @param int $id Идентификатор статьи для удаления.
     * @return bool Возвращает true при успешном выполнении, иначе false.
     */
    public function delete($id) {
        // SQL запрос для удаления статьи по ID
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query); // Подготовка SQL запроса

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error); // Логируем ошибку
            return false; // Возвращаем false, если подготовка запроса не удалась
        }

        $stmt->bind_param("i", $id); // Привязываем параметр ID
        return $stmt->execute(); // Выполняем запрос и возвращаем результат
    }
}