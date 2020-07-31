<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission_5-1</title>
</head>
<body>

<?php

	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date datetime,"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);


    //送信
    $name = $_POST["name"];
    $comment = $_POST["comment"]; 
    $date=date("y/m/d h:i:s");
    $pass=$_POST["pass"];
    $num=$_POST["num"];
    
    if($name!=NULL && $comment!=NULL && $pass!=NULL){
        if($num==NULL){
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
        }else{
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass,date=:date WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $num, PDO::PARAM_INT);
	        $stmt->execute();
        }
    }
	
	
	//削除
	$id1 = $_POST["delete"];
	$pass1=$_POST["pass1"];
	
	if($id1!=NULL && $pass1!=NULL){
	    $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($id1==$row['id'] && $pass1==$row['pass']){
        	    $sql = 'delete from tbtest where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id1, PDO::PARAM_INT);
	            $stmt->execute();
	        }
	    }
	}



	//編集
    $id2 = $_POST["edit"];
    $pass2=$_POST["pass2"];
    
    if($id2!=NULL && $pass2!=NULL){
        $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($id2==$row['id'] && $pass2==$row['pass']){
                $id3=$id2;
                $pass3=$pass2;
                $editname=$row['name'];
                $editcomment=$row['comment'];
            }
        }
    }  



?>

<!--送信フォーム-->
<form action="" method="post">
<input type="text" name="name" placeholder="名前" value="<?php echo $editname?>"><br>
<input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment?>">
<input type="hidden" name="num" value="<?php echo $id3?>"><br>
<input type="text" name="pass" placeholder="パスワード" value="<?php echo $pass3?>">
<input type="submit" name="submit"><br>
</form>

<!--削除フォーム-->
<form action="" method="post">
<input type="number" name="delete" placeholder="削除対象番号"><br>
<input type="text" name="pass1" placeholder="パスワード">
<input type="submit" value="削除">
</form>

<!--編集フォーム-->
<form action="" method="post">
<input type="number" name="edit" placeholder="編集対象番号"><br>
<input type="text" name="pass2" placeholder="パスワード">
<input type="submit" value="編集">
</form>


<?php
	//抽出
	$sql = 'SELECT * FROM tbtest';
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
?>


</body>
</html>