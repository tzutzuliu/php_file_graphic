<?php
/**
 * 1.建立表單
 * 2.建立處理檔案程式
 * 3.搬移檔案
 * 4.顯示檔案列表
 */
include_once "base.php";

//dd($_FILES);

if(isset($_FILES['img']) && $_FILES['img']['error']==0){
    echo $_FILES['img']['name'].'<br>';
    echo $_FILES['img']['type'].'<br>';
    move_uploaded_file($_FILES['img']['tmp_name'],"./upload/".$_FILES['img']['name']);
    echo "./upload/".$_FILES['img']['name']."<br>";
    echo "<img src='./upload/{$_FILES['img']['name']}' style='width:200px'>";
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>檔案上傳</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
 <h1 class="header">檔案上傳練習</h1>
 <!----建立你的表單及設定編碼----->
<form action="?" method="post" enctype="multipart/form-data">
    <div>
        選擇圖片:<input type="file" name="img">
    </div>
    <input type="submit" value="上傳">
</form>



<!----建立一個連結來查看上傳後的圖檔---->  


</body>
</html>