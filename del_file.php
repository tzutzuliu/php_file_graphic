<?php
include_once "base.php";
$id=$_GET['id'];
$file=$pdo->query("SELECT * FROM `upload` WHERE `id`='$id'")->fetch();
unlink("./upload/".$file['name']);
del($id);
header("location:manage.php");
?>