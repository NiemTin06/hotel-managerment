<?php

class Database{
    protected function connect(){
        try{
            $dsn = "mysql:host=" . DB_HOST .
                   ";dbname=" . DB_NAME .
                   ";charset=utf8mb4";

            $dbh = new PDO($dsn, DB_USER, DB_PASS);

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $dbh;
        }
        catch(PDOException $e){
            print "Error!: " . $e->getMessage() . "<br>";
            die();
        }
    }
}