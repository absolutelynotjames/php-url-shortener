<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    // Render index view
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

$app->post('/new', function (Request $request, Response $response, array $args) {

    $body = $response->getBody();

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

    //make sure id isn't already in the database
    while ($this->db->get($id) !== false):
        $id = $idGenerator->generateString(8, $characters);
    endwhile;

    // build url with id
    $url = $this->url . $id;

    //submit to database
    $this->db->set($id, ['url' => $submission['url'], 'time' => $time]);

    $this->logger->info($url . ' submitted');    

    //build json 
    $body->write(json_encode(array(
        "url" => $url,
    ), JSON_UNESCAPED_SLASHES));

    //respond with the full url
    return $response;
});
