<?php
    include '../app/config.php';
    if (empty($_SESSION['user'])) {
        header('location: index.php');
        exit;
    }
?>
<?php require '../views/partials/header.php' ?>
<div class="boot">
    <h1><a href="Add-Categorie">Catégorie</a></h1>
    <h1><a href="Add-Video">Vidéo</a></h1>
</div>
<?php require '../views/partials/footer.php' ?>