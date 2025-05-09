<?php
$dsn  = sprintf('pgsql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME'));
try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    $rows = $pdo->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>", print_r($rows, true), "</pre>";
} catch (PDOException $e) {
    echo "DB connection failed: ", $e->getMessage();
}
