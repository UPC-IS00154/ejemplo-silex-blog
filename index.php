<?php

// Ejemplo de uso de Silex

// uso del autoload generado por composer
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// crea unos datos de ejemplo
$blogPosts = array(
    1 => array(
        'date'      => '2015-11-02',
        'author'    => 'jchavarr',
        'title'     => 'Uso de Silex',
        'body'      => 'Este es un ejemplo del uso de Silex',
    ),
    2 => array(
        'date'      => '2015-11-02',
        'author'    => 'jchavarr',
        'title'     => 'Creación de rutas en Silex',
        'body'      => 'Es posible definir rutas en Silex',
    ),
    3 => array(
        'date'      => '2015-11-02',
        'author'    => 'jchavarr',
        'title'     => 'Creación de controladores en Silex',
        'body'      => 'Es posible definir controladores en Silex',
    ),
);

// Aplicación 
// ==========

// crea un objeto Application
$app = new Silex\Application();

$app->get('/', function () use ($app) {
    return $app->redirect('index.php/menu');
});

$app->get('/menu', function() {
  $output = '<h1>Ejemplo</h1><hr/>';
  $output .= '<a href="./blog">Blog</a>';
  $output .= '<hr/>';
  $output .= '<form action="./feedback" method="POST">';
  $output .= '<input type="text" name="message"></input>';
  $output .= '<button type="submit">Enviar Comentario</button>';
  $output .= '</form>';
  return $output;
});

// ruta : /blog
$app->get('/blog', function () use ($blogPosts) {
    $output = '<h1>Ejemplo : Blog</h1><hr/>';
    foreach ($blogPosts as $key => $post) {
        $output .= '<a href="./blog/'. $key . '">';
        $output .= $post['title'] . '</a>';
        $output .= '<br />';
    }

    return $output;
});

// ruta : /blog/{id}
$app->get('/blog/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1><hr/>".
            "<p>{$post['body']}</p>". 
            '<hr/>'.
            '<a href="../blog">Blog</a>';
});

// ruta : /feedback
$app->post('/feedback', function (Request $request) {
    $message = $request->get('message');
    // mail('feedback@yoursite.com', '[YourSite] Feedback', $message);

    return new Response('Thank you for your feedback!', 201);
});


// ejecuta la aplicación
$app->run();
