<?php
    $dsn = '******';
    $user = '******';
    $password = '******';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date datetime,"
    . "pass char(32)"
    .");";
    $stmt = $pdo->query($sql);
    
    /* データ一覧（確認用）    
    $sql = 'SELECT * FROM mission5_1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].',';
        echo $row['pass'].'<br>';
    echo "<hr>";
    }
    */
    
    // 編集時以外に投稿フォームに表示する文字列
    $editName = "";
    $editComment = "";
    $editPassword = "";
    
    // 編集番号を保持
    $hidden = "";
    
    // 投稿日時
    $date = date("Y-m-d H:i:s");
    
    // 送信ボタンが押されたら名前、コメント、パスワードを受信
    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $pass = $_POST["pass"];
        
        // 名前、コメント、パスワードが空じゃなかったら編集番号を受信
        if ((!empty($name) && !empty($comment)) && !empty($pass)) {
            $nowNo = $_POST["hidden"];
            
            // 編集モード
            // 編集する投稿番号が空じゃなかったら既存の投稿に新しい書き込みを上書き
            if(!empty($nowNo)) {
                $sql = 'UPDATE mission5_1 SET name=:name, comment=:comment, date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                $stmt -> bindParam(':id', $nowNo, PDO::PARAM_INT);
                $stmt -> execute();
            }
            
            // 新規投稿モード
            // 編集する投稿番号が空だったら新しい書き込みをデータベースに保存
            else {
                $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> execute();
            }
        }
    }
    
    // 削除ボタンが押されたら削除番号とパスワードを受信
    elseif (isset($_POST["delete"])) {
        $deleteNo = $_POST["deleteNo"];
        $delPass = $_POST["delPass"];
        
        // 削除番号とパスワードが空じゃないかつ、一致していたら該当する投稿を削除
        if (!empty($deleteNo) && !empty($delPass)) {
            $sql = 'DELETE FROM mission5_1 WHERE id=:id AND pass=:delPass';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':id', $deleteNo, PDO::PARAM_INT);
            $stmt -> bindParam(':delPass', $delPass, PDO::PARAM_STR);
            $stmt -> execute();
        }
    }
    
     // 編集ボタンが押されたら編集番号とパスワードを受信
    elseif (isset($_POST["edit"])) {
        $editNo = $_POST["editNo"];
        $editPass = $_POST["editPass"];
        
        // 編集番号とパスワードが空じゃないかつ、一致していたら編集元の名前、コメント、パスワード、編集番号を取得
        // この変数は入力フォームの「value=」に代入されているため取得した文字列が入力フォームに表示される
        if (!empty($editNo) && !empty($editPass)) {
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id AND pass=:editPass';
            $stmt = $pdo -> prepare($sql);
            $stmt->bindParam(':id', $editNo, PDO::PARAM_INT);
            $stmt->bindParam(':editPass', $editPass, PDO::PARAM_STR);
            $stmt -> execute();
            $row = $stmt->fetch();
            $editName = $row['name'];
            $editComment = $row['comment'];
            $editPassword = $row['pass'];
            $hidden = $editNo;


        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<body>
    <h1>きのことたけのこ どっち派？</h1>
    
    <form action="" method="post">
        <!-- 入力フォーム -->
        <div>
            <input type="text" name="name" value="<?php echo $editName; ?>" placeholder="名前"><br>
            <input type="text" name="comment" value="<?php echo $editComment; ?>" placeholder="コメント"><br>
            <input type="text" name="pass" value="<?php echo $editPassword; ?>" placeholder="パスワード">
            <input type="hidden" name="hidden" value="<?php echo $hidden; ?>">
            <input type="submit" name="submit"><br>
            <br>
        </div>
        <!-- 削除フォーム -->
        <div>
            <input type="number" name="deleteNo" placeholder="削除番号"><br>
            <input type="text" name="delPass" placeholder="パスワード">
            <input type="submit" name="delete" value="削除"><br>
            <br>
        </div>
        <!-- 編集フォーム -->
        <div>
            <input type="number" name="editNo" placeholder="編集番号"><br>
            <input type="text" name="editPass" placeholder="パスワード">
            <input type="submit" name="edit" value="編集"><br>
            <br>
        </div>
    </form>
    <?php
        // データを全て取得
        $sql = 'SELECT * FROM mission5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        // データが１個以上あったら
        if(count($results) >= 1) {
            // データを出力
            foreach ($results as $row) {
                echo $row['id']." ";
                echo $row['name']." ";
                echo $row['comment']." ";
                echo $row['date']."<br>";
                }
        }
        // １個もなかったら
        else {
            echo "データがありません";
        }
    ?>
</body>

</html>