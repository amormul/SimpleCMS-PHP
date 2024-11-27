<?php include_once __DIR__ . '/../../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action active">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Редагування користувача</h1>
        
        <?php if (!empty($data['errors']['general'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['errors']['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="login" class="form-label">Логін</label>
                <input type="text" class="form-control <?php echo !empty($data['errors']['login']) ? 'is-invalid' : ''; ?>" 
                       id="login" name="login" value="<?php echo htmlspecialchars($data['user']['login']); ?>" required>
                <?php if (!empty($data['errors']['login'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['login']); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Новий пароль (залиште порожнім, щоб не змінювати)</label>
                <input type="password" class="form-control <?php echo !empty($data['errors']['password']) ? 'is-invalid' : ''; ?>" 
                       id="password" name="password">
                <?php if (!empty($data['errors']['password'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['password']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Зберегти</button>
                <a href="/admin/users" class="btn btn-secondary">Скасувати</a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>