<?php if (isset($model['success'])) : ?>
    <script>
        alert('success');
        document.location.href = '<?= $model["success"] ?>';
    </script>
<?php endif; ?>