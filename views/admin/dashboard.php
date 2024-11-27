<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="/admin/articles" class="list-group-item list-group-item-action">Статті</a>
            <a href="/admin/users" class="list-group-item list-group-item-action">Користувачі</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Ласкаво просимо, <?php echo htmlspecialchars($data['username']); ?>!</h1>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>