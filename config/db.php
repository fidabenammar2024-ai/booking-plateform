<?php
class Database
{
    public function connect()
    {
        $config = require __DIR__ . '/database_config.php';
        try {
            $conn = new PDO(
                'mysql:host=' . $config['host']
                    . ';dbname=' . $config['dbname']
                    . ';charset=utf8mb4',
                $config['username'],
                $config['password']
            );
            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            return $conn;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die(
                'Impossible de se connecter à la base de données.'
            );
        }
    }
}
