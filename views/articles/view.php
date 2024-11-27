<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <?php if ($article && $row = $article->fetch_assoc()): ?>
        <article class="my-4">
            <h1 class="mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>
            <div class="mb-3 text-muted">
                <small>
                    Автор: <?php echo htmlspecialchars($row['author_name']); ?>
                    <br>
                    Дата: <?php echo date('d.m.Y H:i', strtotime($row['created_at'])); ?>
                </small>
            </div>
            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
        </article>
    <?php else: ?>
        <div class="alert alert-warning mt-4">
            Статтю не знайдено
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="/" class="btn btn-secondary">← Назад до списку статей</a>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>