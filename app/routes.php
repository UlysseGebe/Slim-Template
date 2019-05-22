<?php

// Home
$app
    ->get(
        '/',
        function($request, $response)
        {
            $query = $this->db->query('SELECT * FROM categorie');
            $categories = $query->fetchAll();

            // View data
            $viewData = [];
            $viewData['categories'] = $categories;

            return $this->view->render($response, 'pages/home.twig', $viewData);
        }
    )
    ->setName('home')
;

// Promotions
$app
    ->get(
        '/director-of-filming',
        function($request, $response)
        {
            // Fetch promotions
            $query = $this->db->query('SELECT * FROM categorie');
            $categories = $query->fetchAll();

            // View data
            $viewData = [];
            $viewData['categories'] = $categories;

            return $this->view->render($response, 'pages/director.twig', $viewData);
        }
    )
    ->setName('director')
;

// Promotion
$app
    ->get(
        '/cinematographer',
        function($request, $response, $arguments)
        {
            // Fetch promotions
            $query = $this->db->query('SELECT * FROM categorie');
            $categories = $query->fetchAll();

            // View data
            $viewData = [];
            $viewData['categories'] = $categories;

            return $this->view->render($response, 'pages/cinematographer.twig', $viewData);
        }
    )
    ->setName('cinematographer')
;

// $app
//     ->get(
//         'Post/director-of-filming',
//         function($request, $response)
//         {
//             // Fetch promotions
//             $query = $this->db->query('SELECT * FROM categorie');
//             $categories = $query->fetchAll();

//             // View data
//             $viewData = [];
//             $viewData['categories'] = $categories;

//             return $this->view->render($response, 'pages/director.twig', $viewData);
//         }
//     )
//     ->setName('director')
// ;

// Random student
$app
    ->get(
        '/students/random',
        function($request, $response)
        {
            return 'random student';
        }
    )
    ->setName('random_student')
;

// Student
$app
    ->get(
        '/students/{slug:[a-z_-]+}',
        function($request, $response, $arguments)
        {
            // View data
            $viewData = [];

            return $this->view->render($response, 'pages/student.twig', $viewData);
        }
    )
    ->setName('student')
;
