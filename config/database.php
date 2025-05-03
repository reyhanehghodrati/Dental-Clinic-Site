<?php

class database
{
    private static $conn;

    public static function connect()
    {


        self::loadenv(__DIR__ . '/../.env');

        $servername = $_ENV['servername'] ?? 'localhost';
        $username   = $_ENV['username'] ?? 'root';
        $password   = $_ENV['password'] ?? '';
        $dbname     = $_ENV['dbname'] ?? '';

        self::$conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!self::$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return self::$conn;
    }

    private static function loadenv($file)
    {
        if (!file_exists($file)) return;

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;

            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}