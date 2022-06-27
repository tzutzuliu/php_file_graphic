<?php
/****
 * 1.建立資料庫及資料表
 * 2.建立上傳檔案機制
 * 3.取得檔案資源
 * 4.取得檔案內容
 * 5.建立SQL語法
 * 6.寫入資料庫
 * 7.結束檔案
 */
include_once "base.php";

if(isset($_FILES['file']) && $_FILES['file']['error']==0){
   // echo $_FILES['file']['tmp_name'];
    $file=fopen($_FILES['file']['tmp_name'],'r');
    fgets($file);
    while(!feof($file)){
        $str=fgets($file);
        $col=explode(",",$str);
        if(count($col)==6){
            $year=str_replace(["年",'"'],"",explode(" ",$col[0])[0]);
            $month=str_replace(["月",'"'],"",explode(" ",$col[0])[1]);

            $data=['year'=>$year,
                   'month'=>$month,
                   'tempe'=>$col[1],
                   'humidity'=>$col[2],
                   'daylight'=>$col[3],
                   'preci'=>$col[4],
                   'preci_days'=>$col[5],
                  ];
            save('temperature',$data);
        }
    }
    fclose($file);

    //$sql="insert into `upload` (`name`,`type`,`collections`) values('{$name}','{$type}','{$_POST['collections']}')";
    //$pdo->exec($sql);
}
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
<h1 class="header">文字檔案匯入練習</h1>
<!---建立檔案上傳機制--->
<form id="uploadForm" action="?" method="post" enctype="multipart/form-data">
    <div>
        選擇檔案:<input type="file" name="file">
    </div>

    <input type="submit" value="上傳">
</form>


<!----讀出匯入完成的資料----->
<div style="width:50%;margin:1rem auto;">
<form action="?" method="get">
    選擇年份:<select name="year" >
        <?php
            $years=$pdo->query("SELECT `year` FROM `temperature` GROUP BY `year`")->fetchAll(PDO::FETCH_ASSOC);

            foreach($years as $year){
                echo "<option value='{$year['year']}'>{$year['year']}</option>";
            }
        ?>
    </select>
    <input type="submit" value="送出">
</form>
</div>
<table id="list">
    <tr>
        <th>年</th>
        <th>月</th>
        <th>平均氣溫[0C]</th>
        <th>平均相對溼度[%]</th>
        <th>日照時數[小時]</th>
        <th>降水量[毫米]</th>
        <th>降水日數[日]</th>
        <th>操作</th>
    </tr>
    <?php
    if(isset($_GET['year'])){
        $sql="SELECT * FROM temperature where `year`='{$_GET['year']}'";
    }else{
        $sql="SELECT * FROM temperature";
    }
    $rows=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    $counts=count($rows);
    if(isset($_GET['year'])){
        $file=fopen('paper.csv','w');
        fwrite($file,"\xEF\xBB\xBF");
        $tempe=0;
        $humidity=0;
        $daylight=0;
        $preci=0;
        $preci_days=0;
        fwrite($file,'"年份","月份","平均氣溫[0C]","平均相對溼度[%]","日照時數[小時]","降水量[毫米]","降水日數[日]"'."\r\n");
        foreach($rows as $row){
            unset($row['id']);
            $tempe+=$row['tempe'];
            $humidity+=$row['humidity'];
            $daylight+=$row['daylight'];
            $preci+=$row['preci'];
            $preci_days+=$row['preci_days'];
            fwrite($file,join(',',$row)."\r\n");
        }
            $tempe=round($tempe/$counts,2);
            $humidity=round($humidity/$counts,2);
            $daylight=round($daylight/$counts,2);
            $preci=round($preci/$counts,2);
            $preci_days=round($preci_days/$counts,2);
            fwrite($file,"\"\",\"平均:\",$tempe,$humidity,$daylight,$preci,$preci_days"."\r\n");
        fclose($file);
    }
    //$files=all('upload');
    foreach($rows as $row){
    ?>
    <tr>
        <td><?=$row['year'];?></td>
        <td><?=$row['month'];?></td>
        <td><?=$row['tempe'];?></td>
        <td><?=$row['humidity'];?></td>
        <td><?=$row['daylight'];?></td>
        <td><?=$row['preci'];?></td>
        <td><?=$row['preci_days'];?></td>
        <td>
            <button onclick="location.href='update_row.php?id=<?=$row['id'];?>'">編輯</button>
            <button onclick="location.href='del_row.php?id=<?=$row['id'];?>'">刪除</button>
        </td>
    </tr>
    <?php
    }

    ?>
</table>
<div style="width:500px;margin:1rem auto;text-align:right">
<?php if(isset($_GET['year'])){  ?>
    <a href="paper.csv" download>下載</a>
<?php } ?>
</div>

</body>
</html>