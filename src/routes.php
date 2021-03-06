<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    // Render index view

    $request = $this->csrf->generateNewToken($request);    
    
    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $args = ['nameKey' => $nameKey, 'valueKey' => $valueKey, 'name' => $name, 'value'=>$value];

    return $this->renderer->render($response, 'index.phtml', $args);
});

//pattern for matching against our url generator
$app->get('/{id:[0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ]{8}}', function (Request $request, Response $response, array $args) {

    //get key from url
    $key = $this->db->get($args['id']);

    //if db returns something for this key
    if ($key):
        return $response->withRedirect($key['url']);
    else:
        return $response->withRedirect('/');
    endif;

    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/new', \NewController::class . ':new');