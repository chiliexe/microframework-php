<?php

namespace Core;

use PDO;
use PDOException;
use SQLite3;

class Database
{
    public static function getConnection()
    {
        $conf = require_once __DIR__ . "/../app/database.php";

        if($conf['driver'] == "sqlite") {
            $sqlite = __DIR__ . "/../storage/database/" . $conf["sqlite"]["file"];

            // create file if not exists
            $sqliteConnection = new SQLite3($sqlite);
            $sqliteConnection->close();
            $sqlite = "sqlite:" . $sqlite;

            try {
                $pdo = new PDO($sqlite);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $pdo;
            }catch (PDOException $e){
                echo $e->getMessage();
            }
        }elseif ($conf['driver'] == "myslq"){
            $host = $conf['mysql']['host'];
            $db = $conf['mysql']['database'];
            $user = $conf['mysql']['username'];
            $pass = $conf['mysql']['pass'];
            $collate = $conf['mysql']['collate'];
            $charset = $conf['mysql']['charset'];

            try {
                $pdo = new PDO("mysql:$host;dbname=$db;charset=$charset", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES '$charset'
                COLLATE '$collate'");

                return $pdo;
            }catch (PDOException $e){
                echo $e->getMessage();
            }
        }
    }
}