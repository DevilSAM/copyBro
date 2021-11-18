<?php

class MyDB {
    public static $PDO = null;

    public static $HOST = null;
    public static $DB   = null;
    public static $USER = null;
    public static $PASS = null;
    public static $CHARSET = 'utf8mb4';

    public function get () {
        if(self::$PDO != null) return self::$PDO;

        return self::$PDO = $this->createDBInstance();
    }

    private function createDBInstance () {
        $dsn = "mysql:host=". self::$HOST .";dbname=". self::$DB . ";charset=" .self::$CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, self::$USER, self::$PASS, $options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

}