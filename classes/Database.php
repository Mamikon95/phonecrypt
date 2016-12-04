<?php
/**
 * Wrapper for PDO class, works with the database
 */
class Database
{

    private $serverName = "localhost";
    private $userName = "phonecrypt";
    private $password = "phonecrypt";
    private $dbName = "phonecrypt";

    /**
     * PDO connection object
     * @var PDO
     */
    protected $connection;

    /**
     * Constructor - connects to database
     */
    public  function __construct() {
        try {
            $this->connection = new PDO("mysql:host={$this->serverName};dbname={$this->dbName}", $this->userName, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            header('Content-Type: application/json');
            print json_encode(['errors' => ["Database connection failed: " . $e->getMessage()]]);
            exit();
        }
    }

    /**
     * Retrieve all user records
     * @return array
     */
    public function getAllUsers() {
        $sth = $this->connection->prepare("SELECT phone, email FROM users");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new user entry
     * @param $phone
     * @param $email
     */
    public function insertUserData($phone, $email) {
        $this->connection->exec("INSERT INTO users (phone, email) VALUES ('$phone', '$email')");
    }
}