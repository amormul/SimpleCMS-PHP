<?php include_once __DIR__ . '/../../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action active">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Створення користувача</h1>
        
        <?php if (!empty($data['errors']['general'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['errors']['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/admin/users/create">
            <div class="mb-3">
                <label for="login" class="form-label">Логін</label>
                <input type="text" class="form-control <?php echo !empty($data['errors']['login']) ? 'is-invalid' : ''; ?>" 
                       id="login" name="login" required>
                <?php if (!empty($data['errors']['login'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['login']); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control <?php echo !empty($data['errors']['password']) ? 'is-invalid' : ''; ?>" 
                       id="password" name="password" required>
                <?php if (!empty($data['errors']['password'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['password']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Підтвердження пароля</label>
                <input type="password" class="form-control <?php echo !empty($data['errors']['confirm_password']) ? 'is-invalid' : ''; ?>" 
                       id="confirm_password" name="confirm_password" required>
                <?php if (!empty($data['errors']['confirm_password'])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($data['errors']['confirm_password']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Створити</button>
                <a href="/admin/users" class="btn btn-secondary">Скасувати</a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>