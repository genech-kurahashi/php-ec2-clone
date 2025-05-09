<?php
// データベース接続
$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME'));
try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// 投稿IDを取得
$id = $_GET['id'] ?? null;
if (!$id) {
    die("投稿IDが指定されていません。");
}

// 投稿を取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("投稿が見つかりません。");
}

// 編集処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'] ?? '';
    if ($comment) {
        $stmt = $pdo->prepare("UPDATE posts SET comment = :comment, updated_at = NOW() WHERE id = :id");
        $stmt->execute([':comment' => $comment, ':id' => $id]);

        // 編集後、一覧ページへリダイレクト
        header("Location: index.php");
        exit;
    } else {
        $error_message = "コメントは必須です。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>投稿編集</title>
</head>
<body>
    <h1>投稿編集</h1>

    <!-- エラーメッセージ -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <form action="edit.php?id=<?= $post['id'] ?>" method="post">
        名前：<strong><?= htmlspecialchars($post['created_by']) ?></strong><br>
        コメント：<br>
        <textarea name="comment" rows="4" cols="40" required><?= htmlspecialchars($post['comment']) ?></textarea><br>
        <button type="submit">保存</button>
    </form>

    <a href="index.php">戻る</a>
</body>
</html>
