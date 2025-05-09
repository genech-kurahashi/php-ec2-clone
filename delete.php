<?php
// データベース接続情報
$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME'));
try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// 投稿IDの受け取り
$post_id = $_POST['id'] ?? null;

// IDが存在しない場合はエラーメッセージ
if (!$post_id) {
    echo "削除する投稿が指定されていません。";
    exit;
}

// 投稿の論理削除（deleted_atに現在時刻を挿入）
$stmt = $pdo->prepare("UPDATE posts SET deleted_at = NOW() WHERE id = :id");
$stmt->execute([':id' => $post_id]);

// 削除後、掲示板ページにリダイレクト
header("Location: index.php");
exit;
?>
