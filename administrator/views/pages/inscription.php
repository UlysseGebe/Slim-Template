<?php
    include '../app/config.php';
    if (!empty($_SESSION['user'])) {
        header('location: My-Space');
        exit;
    }
    
    $message = '';

    if(!empty($_POST))
    {
        $login = $_POST['login'];
        $salt = hash('md5', $_POST['login'].SALT);
        $password = hash('sha256', $salt.$_POST['password']);
        setcookie('login', $login, time() + 10, '/');

        $prepare = $pdo->prepare(
            'INSERT INTO user (login, password) VALUES (:login, :password)'
        );
        $prepare->bindValue('login', $login);
        $prepare->bindValue('password', $password);
        $prepare->execute();

        $message = 'User registered';
        header ('location: Login');
        exit;
    }
?>
<?php require '../views/partials/header.php' ?>
    <h1>Inscription</h1>
    <h4><?= $message ?></h4>
    <div class="container">
        <form method="post">
            <div class="row">
                <div class="col-25">
                    <label for="login">login</label>
                </div>
                <div class="col-75">
                    <input type="name" name="login" id="login">
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="password">Password</label>
                </div>
                <div class="col-75">
                    <input type="password" name="password" id="password">
                </div>
            </div>
            <div class="row">
                <input type="submit">
            </div>
        </form>
    </div>
<?php require '../views/partials/footer.php' ?>