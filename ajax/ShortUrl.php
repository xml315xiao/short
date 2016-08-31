<?php
include __DIR__. DIRECTORY_SEPARATOR. 'database.php';

class ShortUrl
{
    protected $pdo;
    protected $table;
    protected $timestamp;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host='. HOSTNAME. ';dbname='. DATABASE, USERNAME, PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('SET NAMES UTF8');
        $this->table = TABLE_NAME;
        $this->timestamp = $_SERVER['REQUEST_TIME'];
    }

    /**
     * Convent long url to short code exit base url.
     * @param $url
     * @param $username
     * @param $customer
     * @return bool|string
     * @throws Exception
     */
    public function conventUrl($url, $username, $customer = '')
    {
        // check url format
        if ( strlen($url) === 0 ) {
            throw new Exception( 'No URL was supplied.' );
        }
        if ( FALSE === filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new Exception( 'URL does not have a valid format.' );
        }

        // check whether the long url already shorted and stored in database
        $short_code = $this->checkExists($url);
        if (FALSE === $short_code) {
            $short_code = $this->createShortCode($url, $username, $customer);
        }

        return $short_code;
    }

    /**
     * Short long url and stored one record in database
     * @param $url
     * @param $username
     * @param $customer
     * @return string
     * @throws Exception
     */
    protected function createShortCode($url, $username, $customer = '')
    {
        // convent id number into short code with 5~7 length
        if (strlen($customer) > 0) {
            $short_code = $customer;
            $is_customer = 1;
        } else {
            $is_customer = 0;
            include_once __DIR__. DIRECTORY_SEPARATOR. 'Bijective.php';
            $obj = new Bijective();

            do {
                $timestamp = str_replace(' ', '', substr(microtime(), 2, 5). substr(microtime(), -5));
                $short_code = $obj->encode($timestamp);
            } while ( FALSE === $this->checkEnable($short_code) );
        }

        // create new record and get the new insert id number
        $sql = 'INSERT INTO ' . $this->table .
            ' (long_url, create_time, short_code, username, iscustomer) ' . ' VALUES (:long_url, :timestamp, :short_code, :username, :iscustomer)';
        $statement = $this->pdo->prepare($sql);
        $params = array(
            'short_code' => $short_code,
            'long_url'  => $url,
            'username'  => $username,
            'iscustomer'  => $is_customer,
            'timestamp' => $this->timestamp
        );
        $statement->execute($params);
        if ( $this->pdo->lastInsertId() < 1) {
            throw new Exception( 'Create new record error' );
        }

        return $short_code;
    }

    /**
     * Fetch long url stored in database.
     * @param $short_code
     * @return bool
     */
    public function parseShortCode($short_code)
    {
        $query = 'SELECT long_url FROM ' . $this->table .
            ' WHERE short_code = :short_code LIMIT 1';
        $statement = $this->pdo->prepare($query);
        $params = array(
            'short_code' => $short_code
        );
        $statement->execute($params);

        $result = $statement->fetch(1);
        return (empty($result)) ? FALSE : $result['long_url'];
    }

    /**
     * Check whether the long url already shorted and stored in database.
     * @param $url
     * @return bool
     */
    protected function checkExists($url)
    {
        $query = 'SELECT short_code FROM ' . $this->table . ' WHERE long_url = :long_url LIMIT 1 ';
        $statement = $this->pdo->prepare($query);
        $params = array(
            'long_url' => $url
        );
        $statement->execute($params);

        $result = $statement->fetch();
        return (empty($result)) ? FALSE : $result['short_code'];
    }

    /**
     * Check whether the short url support by customer has been used.
     * @param $customer
     * @return bool
     */
    public function checkEnable($customer)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE short_code = :short_code';
        $statement = $this->pdo->prepare($query);
        $params = array(
            'short_code' => $customer
        );
        $statement->execute($params);
        $result = $statement->fetch();

        return (empty($result)) ? TRUE : FALSE;
    }

}
