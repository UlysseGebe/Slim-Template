<?php

session_cache_limiter(false);
session_start();

// 404
$container['notFoundHandler'] = function($container) {
    return function($request, $response) use ($container)
    {
        $viewData = [
            'code' => 404,
            'title' => 404,
            'style' => 'style',
        ];

        return $container['view']->render($response->withStatus(404), 'resources/error.twig', $viewData);
    };
};

// 500
$container['errorHandler'] = function($container) {
    return function($request, $response) use ($container)
    {
        $viewData = [
            'code' => 500,
            'title' => 500,
            'style' => 'style',
        ];

        return $container['view']->render($response->withStatus(500), 'resources/error.twig', $viewData);
    };
};

// Home
$app
    ->map(['GET', 'POST'],
        '/', 
        function ($request, $response, $args) {
            if (!empty($_SESSION['user'])) {
                $url = $this->router->pathFor('adminpage');
                return $response->withRedirect($url);
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
                            $url = $this->router->pathFor('adminpage');
                            return $response->withRedirect($url);
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
                $url = $this->router->pathFor('adminpage');
                return $response->withRedirect($url);
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

                    $url = $this->router->pathFor('login');
                    return $response->withRedirect($url);
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
                $url = $this->router->pathFor('login');
                return $response->withRedirect($url);
            }

            $query = $this->db->query('SELECT * FROM categorie ORDER BY classement');
            $categories = $query->fetchAll();
            
            $query = $this->db->query('SELECT * FROM video ORDER BY classement');
            $videos = $query->fetchAll();

            $query = $this->db->query('SELECT id FROM video WHERE id > 3 LIMIT 1');
            $test = $query->fetch();

            $style = 'bootstrap';
            $boot = 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css';
            // View data
            $viewData = [];
            $viewData['style'] = $style;
            $viewData['boot'] = $boot;
            $viewData['categories'] = $categories;
            $viewData['videos'] = $videos;

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
                $url = $this->router->pathFor('login');
                return $response->withRedirect($url);
            }
            $style = 'bootstrap';
            $boot = 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css';
            // View data
            $viewData = [];
            $viewData['style'] = $style;
            $viewData['boot'] = $boot;

            if ($request->getMethod() == 'POST') {
                $query = $this->db->query('SELECT COUNT(*) AS totale FROM categorie');
                $categorie = $query->fetch();
                $totale = $categorie->totale + 1;
                if(isset($_POST['name'], $_POST['link'], $_POST['legend'], $_FILES["imageToUpload"], $_POST['description'])) {
                    if(!empty($_POST['name']) AND !empty($_POST['link']) AND !empty($_POST['legend']) AND !empty($_FILES["imageToUpload"]) AND !empty($_POST['description'])) {
                        include('imageLoader.php');
                        if ($imageResult == 0) {
                            $data = [
                                'classement' => (int)$totale,
                                'name' => trim($_POST['name']),
                                'legend' => trim($_POST['legend']),
                                'content' => trim($_POST['description']),
                                'link' => trim($_POST['link']),
                                'image' => trim($_FILES["imageToUpload"]["name"]),
                            ];

                            $prepare = $this->db->prepare('INSERT INTO categorie (classement, categorie_name, categorie_legend, categorie_content, categorie_link, categorie_image) VALUES (:classement, :name, :legend, :content, :link, :image)');
                            $prepare->execute($data);
                            $message = 'Une catégorie a été ajoutée';
                            $viewData['message'] = $message;
                            $viewData['color'] = 'green';
                        } 
                        else {
                            $message = 'Sorry, there was an error uploading your file.';
                            $viewData['message'] = $message;
                            $viewData['color'] = 'red';
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
                $url = $this->router->pathFor('login');
                return $response->withRedirect($url);
            }
            $style = 'bootstrap';
            $boot = 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css';
            // View data
            $viewData = [];
            $viewData['style'] = $style;
            $viewData['boot'] = $boot;

            $prepare = $this->db->prepare('SELECT * FROM categorie WHERE categorie_name != ?');
            $prepare->execute(array('Home'));
            $categories = $prepare->fetchAll();
            $viewData['categories'] = $categories;

            if ($request->getMethod() == 'POST') {
                if(isset($_POST['name'], $_POST['categorie'], $_FILES['videoToUpload'], $_POST['description'], $_FILES['imageToUpload'], $_POST['fallback'])) {
                    if(!empty($_POST['name']) AND !empty($_POST['categorie']) AND !empty($_FILES['videoToUpload']) AND !empty($_POST['description']) AND !empty($_FILES['imageToUpload']) AND !empty($_POST['fallback'])) {
                        include('imageLoader.php');
                        include('videoLoader.php');
                        if ($imageResult == 0 AND $videoResult == 0) {
                            $query = $this->db->query('SELECT COUNT(*) AS totale FROM video');
                            $video = $query->fetch();
                            $totale = $video->totale + 1;
                            $data = [
                                'classement' => (int)$totale,
                                'name' => trim($_POST['name']),
                                'categorie' => trim($_POST['categorie']),
                                'url' => trim($_FILES["videoToUpload"]["name"]),
                                'description' => trim($_POST['description']),
                                'poster' => trim($_FILES["imageToUpload"]["name"]),
                                'fallback' => trim($_POST['fallback']),
                            ];
                        
                            $prepare = $this->db->prepare('INSERT INTO video (classement, video_name, video_categorie, video_url, video_description, video_poster, video_fallback) VALUES (:classement, :name, :categorie, :url, :description, :poster, :fallback)');
                            $prepare->execute($data);
                            
                            $message = 'Une Video a été ajoutée';
                            $viewData['message'] = $message;
                            $viewData['color'] = 'green';
                        }
                        else {
                            $message = 'Veuillez remplir tous les champs et vérifier le format des fichiers';
                            $viewData['message'] = $message;
                            $viewData['color'] = 'red';
                        }
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
            $url = $this->router->pathFor('login');
            return $response->withRedirect($url);
        }
    )
    ->setName('logout')
;

$app
    ->get(
        '/Delete/{categorie}/{delete}',
        function($request, $response, $args)
        {
            if ($args['categorie'] == 'categorie') {
                $prepare = $this->db->prepare('SELECT * FROM categorie WHERE id = ?');
                $prepare->execute(array($args['delete']));
                $verify = $prepare->fetch();
            }
            else if ($args['categorie'] == 'item') {
                $prepare = $this->db->prepare('SELECT * FROM video WHERE id = ?');
                $prepare->execute(array($args['delete']));
                $verify = $prepare->fetch();
            }

            if (!empty($verify)) {
                if ($args['categorie'] == 'categorie') {
                    $suppr = $this->db->prepare('DELETE FROM categorie WHERE id = ?');
                    $suppr->execute(array($args['delete']));
                    unlink('../assets/image/'.$verify->categorie_image);
                }
                else {
                    $suppr = $this->db->prepare('DELETE FROM video WHERE id = ?');
                    $suppr->execute(array($args['delete']));
                    unlink('../assets/video/'.$verify->video_url);
                    unlink('../assets/image/'.$verify->video_poster);
                }
            }
            else {
                $url = $this->router->pathFor('adminpage');
                return $response->withRedirect($url);
            }

            // View data
            $viewData = [];

            // On redirige le visiteur vers la page d'accueil
            $url = $this->router->pathFor('adminpage');
            return $response->withRedirect($url);
        }
    )
    ->setName('delete')
;

$app
    ->get(
        '/Change/{categorie}/{change}/{type}',
        function($request, $response, $args)
        {
            if ($args['categorie'] == 'categorie') {
                if ($args['type'] == '1') {
                    $prepare = $this->db->prepare('SELECT classement FROM categorie WHERE classement > ? LIMIT 1');
                }
                else if ($args['type'] == '0') {
                    $prepare = $this->db->prepare('SELECT classement FROM categorie WHERE classement < ? ORDER BY classement DESC LIMIT 1');
                }
                $prepare->execute(array($args['change']));
                $test = $prepare->fetch();
                if (!empty($test)) {
                    $prepare = $this->db->prepare('UPDATE categorie SET classement = CASE WHEN classement = :bf THEN :cu WHEN classement = :cu THEN :bf END WHERE classement IN (:bf, :cu)');
                    $prepare->bindValue('bf', $test->classement);
                    $prepare->bindValue('cu', $args['change']);
                    $prepare->execute();
                }
            }
            else if ($args['categorie'] == 'item') {
                if ($args['type'] == '1') {
                    $prepare = $this->db->prepare('SELECT classement FROM video WHERE classement > ? LIMIT 1');
                }
                else if ($args['type'] == '0') {
                    $prepare = $this->db->prepare('SELECT classement FROM video WHERE classement < ? ORDER BY classement DESC LIMIT 1');
                }
                $prepare->execute(array($args['change']));
                $test = $prepare->fetch();
                if (!empty($test)) {
                    $prepare = $this->db->prepare('UPDATE video SET classement = CASE WHEN classement = :bf THEN :cu WHEN classement = :cu THEN :bf END WHERE classement IN (:bf, :cu)');
                    $prepare->bindValue('bf', $test->classement);
                    $prepare->bindValue('cu', $args['change']);
                    $prepare->execute();
                }
            }

            // View data
            $viewData = [];

            // On redirige le visiteur vers la page d'accueil
            $url = $this->router->pathFor('adminpage');
            return $response->withRedirect($url);
        }
    )
    ->setName('change')
;