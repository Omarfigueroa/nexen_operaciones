<?php
namespace App\Connection;


use PDO;
use PDOException;
use Dotenv\Dotenv;
class SQLConnection
{
    protected string $server = "";

    protected Dotenv $dotenv;

    private array $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

    protected PDO $connection;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable( __DIR__ . '/../..');
        $dotenv->safeLoad();
        $this->server = "sqlsrv:server=" . $_ENV['DB_HOST'] . ";" . "database=" . $_ENV['DB_DATABASE'];
    }

    public function open(): PDO|PDOException
    {
        try {
            $this->connection = new PDO($this->server, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $this->options);
        } catch (PDOException $e) {
            echo "Hubo un problema con la conexion: " . $e->getMessage();
        }

        return $this->connection;
    }

    public function close(): null
    {
        return $this->connection = null;
    }
}