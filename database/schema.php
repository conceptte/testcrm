<?php

$schema = file_get_contents(__DIR__ . '/schema.sql');

$pdo = new PDO(
    'mysql:host=db;dbname=' . ($_ENV['DB_DATABASE'] ?? ''),
    $_ENV['DB_USERNAME'] ?? '',
    $_ENV['DB_PASSWORD'] ?? '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]
);

$pdo->exec($schema);