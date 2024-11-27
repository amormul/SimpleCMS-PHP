<?php include_once __DIR__ . '/../layout/header.php'; ?>

<h1>Статті</h1>

<?php if ($articles->num_rows > 0): ?>
    <div class="row">
        <?php while($article = $articles->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/article/view/<?php echo $article['id']; ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <small class="text-muted">
                                Автор: <?php echo htmlspecialchars($article['author_name']); ?>
                                <br>
                                Дата: <?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>Наразі немає доступних статей</p>
<?php endif; ?>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>