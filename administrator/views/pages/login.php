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

        $prepare = $pdo->prepare('SELECT * FROM user WHERE login = :login LIMIT 1');
        $prepare->bindValue('login', $login);
        $prepare->execute();
        $user = $prepare->fetch();

        if(!$user)
        {
            $message = 'User doesn\'t exist';
        }
        else
        {
            if($password === $user->password)
            {
                unset($user->password);
                $_SESSION['user'] = $user;
                $_SESSION['id'] = $user->id;
                header ('location: My-Space');
                exit;
            }
            else
            {
                $message = 'Wrong password';
            }
        }
    }
?>
<?php require '../views/partials/header.php' ?>
    <h1>Login</h1>
    <h4><?= $message ?></h4>
    <div class="container">
        <form method="post">
            <div class="row">
                <div class="col-25">
                    <label for="login">login</label>
                </div>
                <div class="col-75">
                    <input type="name" name="login" id="login" value="<?= !empty($_COOKIE['login']) ? $_COOKIE['login'] : '' ?>">
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