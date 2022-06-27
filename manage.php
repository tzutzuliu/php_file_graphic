<?php
/**
 * 1.建立資料庫及資料表來儲存檔案資訊
 * 2.建立上傳表單頁面
 * 3.取得檔案資訊並寫入資料表
 * 4.製作檔案管理功能頁面
 */
include_once "base.php";

if(isset($_FILES['file']) && $_FILES['file']['error']==0){
    $type=$_FILES['file']['type'];
    $sub=explode(".",$_FILES['file']['name'])[1] ;
    $name=date("Ymdhis").".".$sub;
    move_uploaded_file($_FILES['file']['tmp_name'],'./upload/'.$name);
    $sql="insert into `upload` (`name`,`type`,`collections`) values('{$name}','{$type}','{$_POST['collections']}')";
    $pdo->exec($sql);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>檔案管理功能</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #uploadForm{
            width:400px;
            margin:1rem auto;
            font-size:1.25rem;
            padding:1rem;
        }
        #list{
            border-collapse: collapse;
            box-shadow: 0 0 10px #ccc;
            margin:1rem auto;
        }
        #list img{
            width:150px;
        }
        #list td,#list th{
            border:1px solid #ccc;
            padding:0.5rem 1.1rem;
            font-size:1.15rem;
        }
        #list tr:hover{
            background:lightgreen;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
<h1 class="header">檔案管理練習</h1>
<!----建立上傳檔案表單及相關的檔案資訊存入資料表機制----->
<form id="uploadForm" action="?" method="post" enctype="multipart/form-data">
    <div>
        選擇檔案:<input type="file" name="file">
    </div>
    <div>
        <select name="collections" >
            <option value="圖片">圖片</option>
            <option value="文件">文件</option>
            <option value="試算表">試算表</option>
        </select>
    </div>
    <input type="submit" value="上傳">
</form>


<!----透過資料表來顯示檔案的資訊，並可對檔案執行更新或刪除的工作----->
<table id="list">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>thumb</th>
        <th>type</th>
        <th>collections</th>
        <th>操作</th>
    </tr>
    <?php
    $sql="SELECT * FROM upload";
    $files=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    //$files=all('upload');
    foreach($files as $file){
    ?>
    <tr>
        <td><?=$file['id'];?></td>
        <td>
            <?php
            switch($file['collections']){
                case "圖片":
                    echo "<img src='./upload/{$file['name']}'>";
                break;
                case "文件":
                    echo "<img src='./icon/Word_icon.png'>";
                break;
                case "試算表":
                    echo "<img src='./icon/Excel_icon.png'>";
                break;
            }
            ?>
        </td>
        <td><?=$file['name'];?></td>
        <td><?=$file['type'];?></td>
        <td><?=$file['collections'];?></td>
        <td>
            <button onclick="location.href='update_file.php?id=<?=$file['id'];?>'">更換檔案</button>
            <button onclick="location.href='del_file.php?id=<?=$file['id'];?>'">刪除</button>
        </td>
    </tr>
    <?php
    }

    ?>
</table>




</body>
</html>