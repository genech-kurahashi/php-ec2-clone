<?php
// データベース接続情報
$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_NAME'));
try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// 投稿データの取得
$stmt = $pdo->query("SELECT * FROM posts WHERE deleted_at IS NULL ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';
    $author_name = $_POST['author_name'] ?? '';

    if ($content && $author_name) {
        $stmt = $pdo->prepare("INSERT INTO posts (comment, created_by, created_at) VALUES (:content, :author_name, NOW())");
        $stmt->execute([
            ':content' => $content,
            ':author_name' => $author_name
        ]);

        // 投稿後にリダイレクトして再投稿を防止
        header("Location: index.php");
        exit;
    } else {
        $error_message = "名前とコメントは必須です。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <style>
        button {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            margin: 5px;
        }

        button[type="submit"] {
            background-color: red;
        }

        button:hover {
            background-color: #45a049;
        }

        button[type="submit"]:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>掲示板</h1>

    <!-- エラーメッセージ -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <!-- 投稿フォーム -->
    <form action="index.php" method="post">
        名前：<input type="text" name="author_name" required><br>
        コメント：<br>
        <textarea name="content" rows="4" cols="40" required></textarea><br>
        <button type="submit">投稿</button>
    </form>

    <hr>

    <!-- 投稿の表示 -->
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
    <div style="margin-bottom: 20px;">
        <strong><?= htmlspecialchars($post['created_by']) ?></strong>
        （<?= $post['created_at'] ?>）<br>
        <?= nl2br(htmlspecialchars($post['comment'])) ?>
        <?php if ($post['updated_at']): ?>
            <div style="font-size: small;">編集: <?= $post['updated_at'] ?></div>
        <?php endif; ?>
        <br>
         <!-- 編集ボタン -->
            <a href="edit.php?id=<?= $post['id'] ?>"><button type="button">編集</button></a>

            <!-- 削除ボタン -->
            <form action="delete.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
            </form>
    </div>
<?php endforeach; ?>

    <?php else: ?>
        <p>投稿がありません。</p>
    <?php endif; ?>
</body>
</html>
