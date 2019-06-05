<?php

// 404
$container['notFoundHandler'] = function($container) {
    return function($request, $response) use ($container)
    {
        $viewData = [
            'code' => 404,
            'title' => 404,
        ];

        return $container['view']->render($response->withStatus(404), 'pages/error.twig', $viewData);
    };
};

// 500
$container['errorHandler'] = function($container) {
    return function($request, $response) use ($container)
    {
        $viewData = [
            'code' => 500,
            'title' => 500,
        ];

        return $container['view']->render($response->withStatus(500), 'pages/error.twig', $viewData);
    };
};

// Home
$app
    ->get(
        '/',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM categorie ORDER BY classement');
            $categories = $query->fetchAll();
            $title = 'Portfolio';

            // View data
            $viewData = [];
            $viewData['categories'] = $categories;
            $viewData['title'] = $title;

            return $this->view->render($response, 'pages/home.twig', $viewData);
        }
    )
    ->setName('home')
;

// Pages
$app
    ->get(
        '/as-{categorie}',
        function($request, $response, $arguments)
        {
            // Fetch promotions
            $prepare = $this->db->prepare('SELECT * FROM categorie WHERE categorie_link = ? ORDER BY classement');
            $prepare->execute(array($arguments['categorie']));
            $categorie = $prepare->fetchAll();

            $prepare = $this->db->prepare('SELECT * FROM video WHERE video_categorie = ? ORDER BY classement');
            $prepare->execute(array($arguments['categorie']));
            $video = $prepare->fetchAll();

            $prepare = $this->db->prepare('SELECT * FROM categorie WHERE categorie_link != ?');
            $prepare->execute(array($arguments['categorie']));
            $others = $prepare->fetchAll();

            foreach ($categorie as $_categorie) {
                $title = $_categorie->categorie_name;
            }

            // View data
            $viewData = [];
            $viewData['categorie'] = $categorie;
            $viewData['others'] = $others;
            $viewData['videos'] = $video;

            if (empty($categorie)) {
                throw new \Slim\Exception\NotFoundException($request, $response);
            }
            else {
                $viewData['title'] = $title;
                return $this->view->render($response, 'pages/article.twig', $viewData);
            }
        }
    )
    ->setName('article')
;

// Contact
$app
    ->map(['GET', 'POST'],
        '/contact', 
        function ($request, $response) {
            $title = 'Contact';
            // View data
            $viewData = [];
            $viewData['title'] = $title;

            if ($request->getMethod() == 'POST') {
                $to = "u.geberowicz@gmail.com";
                $subject = "My subject";
                $message = "Hello world!";
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <ulysse.geberowicz@hetic.net>'."\r\n";

                mb_send_mail($to,$subject,$message,$headers);
                
                return $this->view->render($response, 'pages/contact.twig', $viewData);
            }
            else {
                return $this->view->render($response, 'pages/contact.twig', $viewData);
            }
        }
    )
    ->setName('contact')
;