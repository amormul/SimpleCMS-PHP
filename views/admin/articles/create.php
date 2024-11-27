<?php include_once __DIR__ . '/../../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action active">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Створення статті</h1>
        
        <?php if (!empty($data['errors']['general'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['errors']['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/admin/articles/create">
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" class="form-control <?php echo !empty($data['errors']['title']) ? 'is-invalid' : ''; ?>" 
                       id="title" name="title" required>
                <?php if (!empty($data['errors']['title'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['title']); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Текст статті</label>
                <textarea class="form-control <?php echo !empty($data['errors']['content']) ? 'is-invalid' : ''; ?>" 
                          id="content" name="content" rows="10" required></textarea>
                <?php if (!empty($data['errors']['content'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['content']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Створити</button>
                <a href="/admin/articles" class="btn btn-secondary">Скасувати</a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>