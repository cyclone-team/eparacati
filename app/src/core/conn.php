<?php
$config = include_once 'config.php';
date_default_timezone_set('America/Fortaleza');

try {
  $conn = new PDO(
    "mysql:host={$config['db']['host']}:{$config['db']['port']};dbname={$config['db']['dbname']}",
    $config['db']['username'],
    $config['db']['password']
  );
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}
