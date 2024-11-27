<?php
/**
 * Database - Класс для подключения и работы с базой данных.
 */
class Database {
    private $host = 'localhost';    // Хост базы данных
    private $username = 'root';     // Имя пользователя для подключения
    private $password = 'root';     // Пароль пользователя для подключения
    private $database = 'blog_db';  // Имя базы данных для подключения

    /**
     * Получение подключения к базе данных.
     * @return mysqli Возвращает объект соединения mysqli.
     */
    public function getConnection() {
        // Создание нового подключения к базе данных с помощью класса mysqli
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Проверка на наличие ошибки при подключении
        if ($conn->connect_error) {
            // Завершаем выполнение скрипта с выводом сообщения об ошибке подключения
            die("Connection failed: " . $conn->connect_error);
        }

        // Установка кодировки соединения в UTF-8 для корректного отображения символов
        $conn->set_charset("utf8");

        // Возвращаем объект соединения для дальнейшей работы с базой данных
        return $conn;
    }
}