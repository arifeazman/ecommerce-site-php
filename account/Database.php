<?php

$host = 'localhost';
$user = 'root';
$passwd = '';
$schema = 'lastdb';

/* The PDO object */
$pdo = NULL;

/* Connection string, or "data source name" */
$dsn = 'mysql:host=' . $host . ';dbname=' . $schema;

/* Connection inside a try/catch block */
try
{
    $pdo = new PDO($dsn, $user,  $passwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo 'Database connection failed.';
    die();
}
