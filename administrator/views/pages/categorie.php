<?php
    include '../app/config.php';
    if (empty($_SESSION['user'])) {
        header('location: index.php');
        exit;
    }
    if(isset($_POST['name'], $_POST['link'], $_POST['legend'], $_POST['fileToUpload'], $_POST['description'])) {
        if(!empty($_POST['name']) AND !empty($_POST['link']) AND !empty($_POST['legend']) AND !empty($_POST['fileToUpload']) AND !empty($_POST['description'])) {
        $data = [
            'name' => trim($_POST['name']),
            'legend' => trim($_POST['legend']),
            'content' => trim($_POST['description']),
            'link' => trim($_POST['link']),
            'image' => trim($_POST['fileToUpload']),
        ];
    
        $prepare = $pdo->prepare('INSERT INTO categorie (categorie_name, categorie_legend, categorie_content, categorie_link, categorie_image)
        VALUES (:name, :legend, :content, :link, :image)');
        $prepare->execute($data);
        $message = 'Une catégorie a été ajoutée';
    }
    else {
           $message = 'Veuillez remplir tous les champs';
        }
    }
?>
<?php require '../views/partials/header.php' ?>
<div class="container">
    <h1>Catégorie</h1>
    <form method="post">
        <div class="form-group">
            <label for="name">Nom de la catégorie:</label>
            <input type="name" class="form-control" placeholder="Entrer catégorie" name="name">
        </div>
        <div class="form-group">
            <label for="link">Lien de la catégorie:</label>
            <input type="text" class="form-control" placeholder="Entrer le lien (nom sans espace)" name="link">
        </div>
        <div class="form-group">
            <label for="link">Légende de la catégorie:</label>
            <input type="text" class="form-control" placeholder="Entrer une légende" name="legend">
        </div>
        <div class="form-group">
            <label for="fileToUpload">Image de la catégorie:</label>
            <input type="file" class="form-control" name="fileToUpload">
        </div>
        <div class="form-group">
            <label for="link">Description de la catégorie:</label>
            <textarea class="form-control" name="description"></textarea>
        </div>
        <input type="submit" class="btn btn-default">
        <div style="text-align: center; color: red">
            <?php if(isset($message)) { echo $message; } ?>
        </div>
    </form>
</div>
<?php require '../views/partials/footer.php' ?>