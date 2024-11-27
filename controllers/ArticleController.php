<?php
/**
 * ArticleController - Контроллер для работы с статьями.
 */
class ArticleController {
    private $article; // Модель для работы с статьями

    /**
     * Конструктор контроллера.
     * Создает объект модели Article для работы с базой данных.
     *
     * @param mysqli $db Соединение с базой данных.
     */
    public function __construct($db) {
        // Создаем объект модели для работы со статьями
        $this->article = new Article($db);
    }

    /**
     * Отображает список всех статей.
     * Получает статьи из базы данных и передает их в представление.
     */
    public function index() {
        // Получаем все статьи
        $articles = $this->article->readAll();

        // Показываем список статей
        include_once 'views/articles/index.php';
    }

    /**
     * Отображает одну статью по ID.
     * Если статья найдена, показываем ее, если нет — перенаправляем на главную страницу.
     *
     * @param int $id ID статьи.
     */
    public function view($id) {
        // Получаем статью по ID
        $article = $this->article->readOne($id);

        // Если статья найдена, показываем ее
        if ($article && $article->num_rows > 0) {
            include_once 'views/articles/view.php';
        } else {
            // Если статья не найдена, перенаправляем на главную
            header('Location: /');
            exit;
        }
    }
}
