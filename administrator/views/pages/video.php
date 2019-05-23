<?php
    include '../app/config.php';
    if (empty($_SESSION['user'])) {
        header('location: index.php');
        exit;
    }
    $prepare = $pdo->prepare('SELECT * FROM categorie WHERE categorie_name != ?');
    $prepare->execute(array('Home'));
    $categories = $prepare->fetchAll();
?>
<?php require '../views/partials/header.php' ?>
<div class="container">
    <h1>Vidéo</h1>
    <form action="/action_page.php">
        <div class="form-group">
            <label for="name">Nom de la vidéo:</label>
            <input type="name" class="form-control" placeholder="Entrer catégorie" name="name">
        </div>
        <div class="form-group">
            <label for="name">Catégorie de la vidéo:</label>
            <select class="form-control">
                <option selected>Liste des catégorie disponible</option>
                <?php foreach($categories as $_categorie): ?>
                <option value="<?= $_categorie->categorie_link ?>"><?= $_categorie->categorie_name ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <label for="link">FallBack de la vidéo:</label>
            <input type="text" class="form-control" placeholder="Entrer une légende" name="link">
        </div>
        <div class="form-group">
            <label for="fileToUpload">Poster de la vidéo:</label>
            <input type="file" class="form-control" name="fileToUpload">
        </div>
        <div class="form-group">
            <label for="fileToUpload">Lien de la vidéo:</label>
            <input type="file" class="form-control" name="fileToUpload">
        </div>
        <div class="form-group">
            <label for="link">Description de la vidéo:</label>
            <textarea class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
<?php require '../views/partials/footer.php' ?>