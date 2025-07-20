<?php

namespace core;


class Model
{
    protected $db;
    protected $con;

    public function __construct($connectionName = 'default')
    {
        $configPath = __DIR__ . '/../app/config/database.php';
        if (!file_exists($configPath)) {
            throw new \Exception("Arquivo de configuração não encontrado.");
        }
        $config = require $configPath;

        $connectionName = $connectionName ?? $config['default'];
        $connections = $config['connections'];
        if (!isset($connections[$connectionName])) {
            throw new \Exception("Conexão '{$connectionName}' não está definida.");
        }
        $conn = $connections[$connectionName];


        $this->con = new \mysqli(
            $conn['host'],
            $conn['username'],
            $conn['password'],
            $conn['database']
        );

        $this->con->set_charset($conn['charset']);

        if ($this->con->connect_error) {
            die("Erro de conexão com o banco de dados [{$connectionName}]:" . $this->con->connect_error);
        }
        $this->db = new Database($this->con);
    }
}
