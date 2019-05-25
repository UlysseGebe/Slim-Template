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
                        $message = 'Something is rong';
                        $viewData['message'] = $message;
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
                            $message = 'Something is rong';
                            $viewData['message'] = $message;
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
    ->map(['GET', 'POST'],
        '/Categorie', 
        function ($request, $response, $args) {
            if (empty($_SESSION['user'])) {
                header('location: '.ADURL.'');
                exit;
            }
            $style = 'bootstrap';

            // View data
            $viewData = [];
            $viewData['style'] = $style;

            if ($request->getMethod() == 'POST') {
                if(isset($_POST['name'], $_POST['link'], $_POST['legend'], $_FILES["imageToUpload"], $_POST['description'])) {
                    if(!empty($_POST['name']) AND !empty($_POST['link']) AND !empty($_POST['legend']) AND !empty($_FILES["imageToUpload"]) AND !empty($_POST['description'])) {
                        include('imageLoader.php');
                        if ($uploadOk == 0) {
                            $message = 'Sorry, your file was not uploaded.';
                            $viewData['message'] = $message;
                            $viewData['color'] = 'red';
                        } else {
                            if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) {
                                $data = [
                                    'name' => trim($_POST['name']),
                                    'legend' => trim($_POST['legend']),
                                    'content' => trim($_POST['description']),
                                    'link' => trim($_POST['link']),
                                    'image' => trim($_FILES["imageToUpload"]["name"]),
                                ];
            
                                $prepare = $this->db->prepare('INSERT INTO categorie (categorie_name, categorie_legend, categorie_content, categorie_link, categorie_image) VALUES (:name, :legend, :content, :link, :image)');
                                $prepare->execute($data);
                                $message = 'Une catégorie a été ajoutée';
                                $viewData['message'] = $message;
                                $viewData['color'] = 'green';
                            } else {
                                $message = 'Sorry, there was an error uploading your file.';
                                $viewData['message'] = $message;
                                $viewData['color'] = 'red';
                            }
                        }
                }
                else {
                       $message = 'Veuillez remplir tous les champs';
                       $viewData['message'] = $message;
                       $viewData['color'] = 'red';
                    }
                }
            }
            else {
                return $this->view->render($response, 'resources/categorie.twig', $viewData);
            }
        return $this->view->render($response, 'resources/categorie.twig', $viewData);
        }
    )
    ->setName('categorie')
;

$app
    ->map(['GET', 'POST'],
        '/Video', 
        function ($request, $response, $args) {
            if (empty($_SESSION['user'])) {
                header('location: '.ADURL.'');
                exit;
            }
            $style = 'bootstrap';

            // View data
            $viewData = [];
            $viewData['style'] = $style;

            $prepare = $this->db->prepare('SELECT * FROM categorie WHERE categorie_name != ?');
            $prepare->execute(array('Home'));
            $categories = $prepare->fetchAll();
            $viewData['categories'] = $categories;

            if ($request->getMethod() == 'POST') {
                if(isset($_POST['name'], $_POST['categorie'], $_POST['videoToUpload'], $_POST['description'], $_POST['imageToUpload'], $_POST['fallback'])) {
                    if(!empty($_POST['name']) AND !empty($_POST['categorie']) AND !empty($_POST['videoToUpload']) AND !empty($_POST['description']) AND !empty($_POST['imageToUpload']) AND !empty($_POST['fallback'])) {
                    $data = [
                        'name' => trim($_POST['name']),
                        'categorie' => trim($_POST['categorie']),
                        'url' => trim($_POST['videoToUpload']),
                        'description' => trim($_POST['description']),
                        'poster' => trim($_POST['imageToUpload']),
                        'fallback' => trim($_POST['fallback']),
                    ];
                
                    $prepare = $this->db->prepare('INSERT INTO video (video_name, video_categorie, video_url, video_description, video_poster, video_fallback) VALUES (:name, :categorie, :url, :description, :poster, :fallback)');
                    $prepare->execute($data);
                    
                    $message = 'Une Video a été ajoutée';
                    $viewData['message'] = $message;
                    $viewData['color'] = 'green';
                }
                else {
                       $message = 'Veuillez remplir tous les champs';
                       $viewData['message'] = $message;
                       $viewData['color'] = 'red';
                    }
                }
            }
            else {
                return $this->view->render($response, 'resources/video.twig', $viewData);
            }
            return $this->view->render($response, 'resources/video.twig', $viewData);
        }
    )
    ->setName('video')
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