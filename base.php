<?php
$dsn="mysql:host=localhost;charset=utf8;dbname=files";
$pdo=new PDO($dsn,'root','');
date_default_timezone_set('Asia/Taipei');

function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function all($table,...$arg){
    global $pdo;
    
    //建立共有的基本SQL語法
    $sql="SELECT * FROM $table ";
    
    //依參數數量來決定進行的動作因此使用switch...case
    switch(count($arg)){
        case 1:
    
            //判斷參數是否為陣列
            if(is_array($arg[0])){
    
                //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                foreach($arg[0] as $key => $value){
    
                    $tmp[]="`$key`='$value'";
    
                }
    
                //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                $sql.=" WHERE ". implode(" AND " ,$tmp);
            }else{
                
                //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                $sql.=$arg[0];
            }
        break;
        case 2:
    
            //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
            foreach($arg[0] as $key => $value){
    
                $tmp[]="`$key`='$value'";
    
            }
    
            //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
            $sql.=" WHERE ". implode(" AND " ,$tmp) . $arg[1];
        break;
    
        //執行連線資料庫查詢並回傳sql語句執行的結果
        }
    
        //fetchAll()加上常數參數FETCH_ASSOC是為了讓取回的資料陣列中
        //只有欄位名稱,而沒有數字的索引值
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    }
    
    function del($id){
        global $pdo;
        return $pdo->exec("DELETE FROM `upload` WHERE `id`='$id'");
    }

/**
 * $table - 資料表名稱 字串型式
 * $arg - 陣列型式
 *        1. 如果陣列中有key值為id，則執行更新(update)的功能
 *        2. 如果陣列中沒有key值為id，則執行新增(insert)的功能
 */

function  save($table,$arg){
    global $pdo;
    if(isset($arg['id'])){
        //update

        foreach($arg as $key => $value){

            if($key!='id'){

                $tmp[]="`$key`='$value'";
            }

        }
        //建立更新的sql語法
        $sql="UPDATE $table SET ".implode(" , " ,$tmp)." WHERE `id`='{$arg['id']}'";

    }else{
        //insert
        $cols=implode("`,`",array_keys($arg));
        $values=implode("','",$arg);

        //建立新增的sql語法
        $sql="INSERT INTO $table (`$cols`) VALUES('$values')";

    }
    
    return $pdo->exec($sql);

}