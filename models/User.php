<?php

/**
 * Class User - Обработчик пользователей для работы с базой данных.
 */
class User {
    private $conn;  // Объект соединения с базой данных
    private $table = 'users';  // Название таблицы пользователей

    public $id;  // ID пользователя
    public $login;  // Логин пользователя
    private $password;  // Пароль пользователя

    /**
     * Конструктор класса User.
     *
     * @param mysqli $db Объект соединения с базой данных
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Логин пользователя.
     * Проверяет наличие пользователя с данным логином и паролем в базе данных.
     *
     * @param string $login Логин пользователя
     * @param string $password Пароль пользователя
     *
     * @return bool Возвращает true, если логин и пароль совпадают, иначе false
     */
    public function login($login, $password) {
        // Запрос для получения данных пользователя по логину
        $query = "SELECT id, login, password FROM " . $this->table . " WHERE login = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);  // Логируем ошибку
            return false;
        }

        $stmt->bind_param("s", $login);  // Привязываем параметр для логина

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);  // Логируем ошибку выполнения запроса
            return false;
        }

        $result = $stmt->get_result();  // Получаем результат запроса

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();  // Извлекаем данные пользователя
            if (password_verify($password, $row['password'])) {
                // Проверяем, совпадает ли пароль
                $this->id = $row['id'];  // Устанавливаем ID пользователя
                $this->login = $row['login'];  // Устанавливаем логин пользователя
                return true;
            }
        }

        return false;  // Логин или пароль неверный
    }

    /**
     * Регистрация нового пользователя.
     * Проверяет, существует ли уже пользователь с таким логином и, если нет, создает нового пользователя.
     *
     * @param string $login Логин пользователя
     * @param string $password Пароль пользователя
     *
     * @return bool Возвращает true при успешной регистрации, иначе false
     */
    public function create($login, $password) {
        // Проверяем, существует ли уже пользователь с таким логином
        $check_query = "SELECT id FROM " . $this->table . " WHERE login = ? LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        if (!$check_stmt) {
            error_log("Prepare check failed: " . $this->conn->error);
            return false;
        }

        $check_stmt->bind_param("s", $login);
        if (!$check_stmt->execute()) {
            error_log("Execute check failed: " . $check_stmt->error);
            return false;
        }

        $result = $check_stmt->get_result();
        if ($result->num_rows > 0) {
            error_log("Login already exists: " . $login);  // Логируем, если логин уже существует
            return false;
        }

        // Хешируем пароль перед сохранением
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Запрос для вставки нового пользователя
        $query = "INSERT INTO " . $this->table . " (login, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ss", $login, $hashed_password);  // Привязываем параметры

        return $stmt->execute();  // Выполняем запрос на создание пользователя
    }

    /**
     * Получение списка всех пользователей.
     *
     * @return mysqli_result|false Возвращает результат запроса, либо false в случае ошибки
     */
    public function readAll() {
        $query = "SELECT id, login, created_at FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Query failed: " . $this->conn->error);  // Логируем ошибку запроса
            return false;
        }
        return $result;
    }

    /**
     * Получение данных одного пользователя по его ID.
     *
     * @param int $id ID пользователя
     *
     * @return array|false Возвращает данные пользователя в виде ассоциативного массива или false в случае ошибки
     */
    public function readOne($id) {
        $query = "SELECT id, login FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();  // Возвращаем данные пользователя
    }

    /**
     * Обновление данных пользователя.
     *
     * @param int $id ID пользователя
     * @param string $login Новый логин
     * @param string|null $password Новый пароль (если передан)
     *
     * @return bool Возвращает true при успешном обновлении, иначе false
     */
    public function update($id, $login, $password = null) {
        // Проверяем, существует ли уже другой пользователь с таким логином
        $check_query = "SELECT id FROM " . $this->table . " WHERE login = ? AND id != ? LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        if (!$check_stmt) {
            error_log("Prepare check failed: " . $this->conn->error);
            return false;
        }

        $check_stmt->bind_param("si", $login, $id);
        if (!$check_stmt->execute()) {
            error_log("Execute check failed: " . $check_stmt->error);
            return false;
        }

        $result = $check_stmt->get_result();
        if ($result->num_rows > 0) {
            error_log("Login already exists: " . $login);
            return false;
        }

        // Если пароль был передан, обновляем и его
        if ($password) {
            $query = "UPDATE " . $this->table . " SET login = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssi", $login, $hashed_password, $id);
        } else {
            $query = "UPDATE " . $this->table . " SET login = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param("si", $login, $id);  // Обновляем только логин
        }

        return $stmt->execute();  // Выполняем запрос на обновление
    }

    /**
     * Удаление пользователя.
     * Проверяет, есть ли у пользователя связанные статьи перед удалением.
     *
     * @param int $id ID пользователя
     *
     * @return bool Возвращает true, если пользователь был удален, иначе false
     */
    public function delete($id) {
        // Проверяем, есть ли у пользователя статьи
        $check_query = "SELECT id FROM articles WHERE author_id = ? LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        if (!$check_stmt) {
            error_log("Prepare check failed: " . $this->conn->error);
            return false;
        }

        $check_stmt->bind_param("i", $id);
        if (!$check_stmt->execute()) {
            error_log("Execute check failed: " . $check_stmt->error);
            return false;
        }

        $result = $check_stmt->get_result();
        if ($result->num_rows > 0) {
            error_log("Cannot delete user with articles");
            return false;  // Нельзя удалить пользователя с статьями
        }

        // Удаляем пользователя
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();  // Выполняем запрос на удаление
    }

    /**
     * Сброс пароля администратора на значение по умолчанию.
     *
     * @return bool Возвращает true при успешном сбросе пароля, иначе false
     */
    public function resetAdminPassword() {
        $query = "UPDATE " . $this->table . " SET password = ? WHERE login = 'admin'";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $default_password = 'admin';  // Стандартный пароль для администратора
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
        $stmt->bind_param("s", $hashed_password);

        return $stmt->execute();  // Выполняем запрос на сброс пароля
    }
}