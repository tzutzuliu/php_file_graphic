<?php
/****
 * 1.建立資料庫及資料表
 * 2.建立上傳圖案機制
 * 3.取得圖檔資源
 * 4.進行圖形處理
 *   ->圖形縮放
 *   ->圖形加邊框
 *   ->圖形驗證碼
 * 5.輸出檔案
 */
include_once "base.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文字檔案匯入</title>
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
<h1 class="header">圖形處理練習</h1>
<!---建立檔案上傳機制--->

<form id="uploadForm" action="?" method="post" enctype="multipart/form-data">
    <div>
        選擇檔案:<input type="file" name="file">
    </div>
    <div>
        <input type="number" step="any" name="percent" value="0.5">
    </div>   
    <div>
        <select name="color" >
            <option value="blue">藍色</option>
            <option value="red">紅色</option>
            <option value="green">綠色</option>
        </select>
    </div>   
    <input type="submit" value="上傳">
</form>

<?php
if(isset($_FILES['file']['tmp_name'])){
    move_uploaded_file($_FILES['file']['tmp_name'],'./upload/'.$_FILES['file']['name']);
    echo "<img src='./upload/{$_FILES['file']['name']}'>";
}
?>

<!----縮放圖形----->
<?php
if(isset($_FILES['file']['tmp_name'])){
    $filename="./upload/{$_FILES['file']['name']}";
    $percent=$_POST['percent']??0.5;

    $src_width=getimagesize($filename)[0];
    $src_height=getimagesize($filename)[1];

    $dst_width=$src_width*$percent;
    $dst_height=$src_height*$percent;
    
    $dst_img=imagecreatetruecolor($dst_width,$dst_height);
    $src_img=imagecreatefromjpeg($filename);

    imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_width,$dst_height,$src_width,$src_height);

    imagejpeg($dst_img,"./upload/result.jpg",100);
    imagedestroy($dst_img);
    imagedestroy($src_img);

}

?>
<h2>縮放後的圖形</h2>
<img src="./upload/result.jpg" alt="">
<!----圖形加邊框----->
<?php
if(isset($_FILES['file']['tmp_name'])){
    $filename="./upload/{$_FILES['file']['name']}";
    $percent=0.5;
    $border=10;
    $color=$_POST['color'];
    $src_width=getimagesize($filename)[0];
    $src_height=getimagesize($filename)[1];

    $dst_width=$src_width*$percent;
    $dst_height=$src_height*$percent;
    
    $dst_img=imagecreatetruecolor($dst_width,$dst_height);
    $blue=imagecolorallocate($dst_img,0,0,255);
    $red=imagecolorallocate($dst_img,255,0,0);
    $green=imagecolorallocate($dst_img,0,255,0);
    
    imagefill($dst_img,0,0,$$color);
    
/*     switch($color){
        case 'blue':
            imagefill($dst_img,0,0,$blue);
        break;
        case 'red':
            imagefill($dst_img,0,0,$red);
        break;
        case "green":
            imagefill($dst_img,0,0,$green);
        break;
    } */
    $src_img=imagecreatefromjpeg($filename);

    $dst_width=$src_width*$percent - ($border*2);
    $dst_height=$src_height*$percent - ($border*2);
    
    imagecopyresampled($dst_img,$src_img,$border,$border,0,0,$dst_width,$dst_height,$src_width,$src_height);

    imagejpeg($dst_img,"./upload/border.jpg",100);
    imagedestroy($dst_img);
    imagedestroy($src_img);

}
?>
<h2>加邊框後的圖形</h2>
<img src="./upload/border.jpg" alt="">

<!----產生圖形驗證碼----->



</body>
</html>