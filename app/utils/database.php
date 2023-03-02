<?php

class Database
{
    private const HOST = "localhost";
    private const DB_NAME = "scandidb";
    private const USERNAME = "root";
    private const PASSWORD = "";
    public $db;
    public function __construct()
    {
        $this->setConnection();
    }
    public function setConnection()
    {
        $connectionStr = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME;
        $this->db = new PDO($connectionStr, self::USERNAME, self::PASSWORD);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}