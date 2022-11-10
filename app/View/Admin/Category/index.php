<?php if (isset($model['error'])) : ?>
    <?= $model['error'] ?>
<?php endif; ?>
<?php // if (isset($model['success'])) : ?>
<!--    <script>-->
<!--        document.location.href = '/admin/categories';-->
<!--);        alert('--><?php //// $model["success"] ?>
<!--//    </script>-->
<?php // endif; ?>

<form method="post">
    <div><span>Categories</span>
        <br>
        <label for="id">Id</label>
        <input type="text" name="categoryId" id="id" required>
        <br>
        <label for="categoryName">Nama Category</label>
        <input type="text" name="categoryName" id="categoryName" required>
        <br>
        <button type="submit">submit</button>
        <a href="/admin">kembali</a>
    </div>
</form>

<?php foreach ($model['category'] as $key => $value) : ?>
    <div>
        <span>
        <?= $key + 1; ?>
        </span>
        <h1><?= $value->getCategoryId() ?></h1>
        <h2><?= $value->getCategoryName() ?></h2>
        <h2><?= $value->getCreatedAt() ?></h2>
        <h2><?= $value->getupdatedAt() ?></h2>
        <a href="/admin/categories-update?id=<?= $value->getCategoryId() ?>">Edit</a>
        <a href="/admin/categories-delete?id=<?= $value->getCategoryId() ?>"
           onclick="return confirm('Ingin menghapus?');">Delete</a>
    </div>
<?php endforeach; ?>
