<?php

// Home
$app
    ->get(
        '/',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM categorie');
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
            $prepare = $this->db->prepare('SELECT * FROM categorie WHERE categorie_link = ?');
            $prepare->execute(array($arguments['categorie']));
            $categorie = $prepare->fetchAll();

            $prepare = $this->db->prepare('SELECT * FROM video WHERE video_categorie = ?');
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
            $viewData['title'] = $title;

            if (empty($categorie)) {
                header('Location: http://localhost:8888/Slim-Template/web');
                die;
            }
            else {
                return $this->view->render($response, 'pages/article.twig', $viewData);
            }
        }
    )
    ->setName('article')
;

// Contact
$app
    ->get(
        '/contact',
        function($request, $response)
        {
            $title = 'Contact';

            // View data
            $viewData = [];
            $viewData['title'] = $title;

            return $this->view->render($response, 'pages/contact.twig', $viewData);
        }
    )
    ->setName('contact')
;