<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action active">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Управління користувачами</h1>
        <div class="mb-3">
            <a href="/admin/users/create" class="btn btn-primary">Додати користувача</a>
        </div>
        <?php if ($users && $users->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логін</th>
                            <th>Дата реєстрації</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['login']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Редагувати</a>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="/admin/users/delete?id=<?php echo $user['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Ви впевнені?')">Видалити</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Користувачі відсутні</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>