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

    public function normalizeUrl($url)
    {        
        /*
        first check to see if our url starts with a protocol, if not assign https
        https://stackoverflow.com/questions/2762061/how-to-add-http-if-it-doesnt-exists-in-the-url 
        */
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }        

        //parse url        
        $parse = parse_url($url);          
        
        //if path is empty, set it to an empy string
        if (empty($parse['path'])) {
            $parse['path'] = '';
        }
        
        //strip www from host if it exists
        $url = preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $url);

        return $url;
    }

    function new ($request, $response, $args) {

        $responseBody = $response->getBody();

        //get post body
        $requestBody = $request->getParsedBody();

        $requestBody['url'] = $this->normalizeUrl($requestBody['url']);

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

        $this->logger->info($url . ' submitted' . $requestBody['url']);

        //build json
        $responseBody->write(json_encode(array(
            "url" => $url,
        ), JSON_UNESCAPED_SLASHES));

        //respond with the full url
        return $response;
    }
}
