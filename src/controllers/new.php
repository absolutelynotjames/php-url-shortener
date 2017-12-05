<?php class NewController
{
    protected $db;

    public function __construct($url, $db, $logger)
    {
        $this->url = $url;        
        $this->db = $db;
        $this->logger = $logger;       
        $this->timestamp = Time();        
        
        //random number generator for unique id
        $randomFactory = new RandomLib\Factory;
        $this->idGenerator = $randomFactory->getLowStrengthGenerator();
       
        //let's keep our character set simple
        $this->characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';      
          
    }
    function new ($request, $response, $args) {

        $responseBody = $response->getBody();

        //get post body
        $requestBody = $request->getParsedBody();

        //generate inique id
        $id = $this->idGenerator->generateString(8, $this->characters);

        //make sure id isn't already in the database
        while ($this->db->get($id) !== false):
            $id = $this->idGenerator->generateString(8, $this->$characters);
        endwhile;

        // build url with id
        $url = $this->url . $id;

        //submit to database
        $this->db->set($id, ['url' => $requestBody['url'], 'time' => $this->timestamp]);

        $this->logger->info($url . ' submitted');

        //build json
        $responseBody->write(json_encode(array(
            "url" => $url,
        ), JSON_UNESCAPED_SLASHES));

        //respond with the full url
        return $response;
    }
}
