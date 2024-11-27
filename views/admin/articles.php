<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action active">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Управління статтями</h1>
        <div class="mb-3">
            <a href="/admin/articles/create" class="btn btn-primary">Додати статтю</a>
        </div>
        <?php if ($articles && $articles->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            <th>Автор</th>
                            <th>Дата</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($article = $articles->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($article['id']); ?></td>
                                <td><?php echo htmlspecialchars($article['title']); ?></td>
                                <td><?php echo htmlspecialchars($article['author_name']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?></td>
                                <td>
                                    <a href="/admin/articles/edit/<?php echo $article['id']; ?>" class="btn btn-sm btn-primary">Редагувати</a>
                                    <a href="/admin/articles/delete?id=<?php echo $article['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Ви впевнені?')">Видалити</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Статті відсутні</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>