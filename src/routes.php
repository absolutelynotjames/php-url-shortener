<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function (Request $request, Response $response, array $args) {
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/new', function (Request $request, Response $response, array $args) {

    //get post body
    $submission = $request->getParsedBody();    

    //random number generator for unique id
    $randomFactory = new RandomLib\Factory;
    $idGenerator = $randomFactory->getLowStrengthGenerator();    

    //let's keep our character set simple
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    //for timestamping submissions, might be useful for something later on
    $time = Time();

    //generate inique id
    $id = $idGenerator->generateString(8, $characters);

    $this->logger->info($submission['url']);
    
    try {
        $this->db->set($id, ['url' => $submission['url'], 'time' => $time]);
    } catch(Exception $e) {
        $this->logger->info($e);
    } finally {
        return $id;
    }
});