<?php
$DBHOST = "training2025-db-instance-1-us-east-1a.c25mkwu8gg8k.us-east-1.rds.amazonaws.com";
$DBPORT = "5432";
$DBNAME = "salesdb";
$DBUSER = "kurahashi";
$DBPASS = "training2025-kurahashi";

try {
    $pdo = new PDO("pgsql:host=$DBHOST;port=$DBPORT;dbname=$DBNAME;user=$DBUSER;password=$DBPASS");
    $rows = $pdo->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>", print_r($rows, true), "</pre>";
} catch (PDOException $e) {
    echo "DB connection failed: ", $e->getMessage();
}
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>テストページ</title>
  </head>
  <body>
    <p>htmlが表示できたよ</p>
  </body>
</html>