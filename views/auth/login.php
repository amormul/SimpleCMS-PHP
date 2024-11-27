<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Вхід в адмінпанель</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['errors']['auth'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($data['errors']['auth']); ?></div>
                <?php endif; ?>

                <form method="POST" action="/auth/login">
                    <div class="mb-3">
                        <label for="login" class="form-label">Логін</label>
                        <input type="text" 
                               class="form-control <?php echo !empty($data['errors']['login']) ? 'is-invalid' : ''; ?>" 
                               id="login" 
                               name="login" 
                               value="<?php echo htmlspecialchars($data['login']); ?>"
                               required>
                        <?php if (!empty($data['errors']['login'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($data['errors']['login']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control <?php echo !empty($data['errors']['password']) ? 'is-invalid' : ''; ?>" 
                               id="password" 
                               name="password"
                               required>
                        <?php if (!empty($data['errors']['password'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($data['errors']['password']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Увійти</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>