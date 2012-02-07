<?php
  require_once 'Phactory/lib/Phactory.php';
  $pdo = new PDO('mysql:host=localhost; dbname=server_oi_dev', 'root', '');
  Phactory::setConnection($pdo);