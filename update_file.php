<?php
include_once "base.php";

if(isset($_FILES['file']) && $_FILES['file']['error']==0){
    $type=$_FILES['file']['type'];
    $sub=explode(".",$_FILES['file']['name'])[1] ;
    $name=date("Ymdhis").".".$sub;
    $origin_file=$pdo->query("select * from `upload` where `id`='{$_GET['id']}'")->fetch();
    //echo "select * from `upload` where `id`='{$_GET['id']}'";
    move_uploaded_file($_FILES['file']['tmp_name'],'./upload/'.$name);
    unlink("./upload/".$origin_file['name']);
    //echo "<br>"."update `upload` set `name`='{$name}',`type`='{$type}',`collections`='{$_POST['collections']}' where `id`='{$_GET['id']}'";
    $sql="update `upload` set `name`='{$name}',`type`='{$type}',`collections`='{$_POST['collections']}' where `id`='{$_GET['id']}'";
    $pdo->exec($sql);
   header("location:manage.php");
}
?>

<form id="uploadForm" action="?id=<?=$_GET['id'];?>" method="post" enctype="multipart/form-data">
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