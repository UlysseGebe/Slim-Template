<?php

session_cache_limiter(false);
session_start();

// Home
$app
    ->map(['GET', 'POST'],
        '/', 
        function ($request, $response, $args) {
            if (!empty($_SESSION['user'])) {
                header('location: My-Space');
                exit;
            }
            $style = 'backstyle';

            // View data
            $viewData = [];
            $viewData['style'] = $style;

            if ($request->getMethod() == 'POST') {
                if(!empty($_POST)) {
                    $login = $_POST['login'];
                    $salt = hash('md5', $_POST['login'].SALT);
                    $password = hash('sha256', $salt.$_POST['password']);
                
                    $prepare = $this->db->prepare('SELECT * FROM user WHERE login = :login LIMIT 1');
                    $prepare->bindValue('login', $login);
                    $prepare->execute();
                    $user = $prepare->fetch();
                
                    if(!$user) {
                        $message = 'User doesn\'t exist';
                        return $this->view->render($response, 'resources/login.twig', $viewData);
                    }
                    else {
                        if($password === $user->password) {
                            unset($user->password);
                            $_SESSION['user'] = $user;
                            header('location: My-Space');
                            exit;
                        }
                        else {
                            $message = 'Wrong password';
                            return $this->view->render($response, 'resources/login.twig', $viewData);
                        }
                    }
                }
            }
            else {
                $viewData['cookie'] = !empty($_COOKIE['login']) ? $_COOKIE['login'] : '';
                return $this->view->render($response, 'resources/login.twig', $viewData);
            }
        }
    )
    ->setName('login')
;

$app
    ->map(['GET', 'POST'],
        '/Register', 
        function ($request, $response, $args) 
        {
            if (!empty($_SESSION['user'])) {
                header('location: My-Space');
                exit;
            }
            $style = 'backstyle';
            // View data
            $viewData = [];
            $viewData['style'] = $style;

            if ($request->getMethod() == 'POST') {
                if(!empty($_POST['login']) & !empty($_POST['password'])){
                    $login = $_POST['login'];
                    $salt = hash('md5', $_POST['login'].SALT);
                    $password = hash('sha256', $salt.$_POST['password']);
                    setcookie('login', $login, time() + 10, '/');
                    
                    $prepare = $this->db->prepare('INSERT INTO user (login, password) VALUES (:login, :password)');
                    $prepare->bindValue('login', $login);
                    $prepare->bindValue('password', $password);
                    $prepare->execute();

                    header('location: Login');
                    exit;
                }
                else {
                    if(!empty($_POST['login'])){
                        $message = 'Renseigner le mot de passe';
                    }
                    else if(!empty($_POST['password'])){
                        $message = 'Renseigner le login';
                    }
                    else {
                        $message = 'Remplire tous les champs';
                    }
                    $viewData['message'] = $message;
                    return $this->view->render($response, 'resources/inscription.twig', $viewData);
                }
            }
            else {
                return $this->view->render($response, 'resources/inscription.twig', $viewData);
            }
        }
    )
    ->setName('register')
;

$app
    ->get(
        '/My-Space',
        function($request, $response)
        {
            if (empty($_SESSION['user'])) {
                header('location: '.ADURL.'');
                exit;
            }
            $style = 'bootstrap';
            // View data
            $viewData = [];
            $viewData['style'] = $style;

            return $this->view->render($response, 'resources/adminpage.twig', $viewData);
        }
    )
    ->setName('adminpage')
;

$app
    ->get(
        '/Logout',
        function($request, $response)
        {
            // View data
            $viewData = [];

            // On détruit les variables de notre session
            session_unset ();

            // On détruit notre session
            session_destroy ();

            // On redirige le visiteur vers la page d'accueil
            header('location: '.ADURL.'');
            exit;
        }
    )
    ->setName('logout')
;