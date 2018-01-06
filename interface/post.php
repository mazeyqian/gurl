<?php
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

require_once ( dirname(__FILE__) . '/../config/config.php' );

/* echo "callSuccess(" . json_encode("{test}") . ")"; */


$retArr = array();
$retPost = array();
$name = $_POST['name'];//'39yst_3';
$sqlLike = generateSQLLike($keywords);
//$sqlLike = " and post_content like '%白发%'";
//$sqlLike = " and (post_content like '%打嗝%' )";
if($name == ''):
    $name = 'wp';
endif;
$rows = array();
$sql = "select post_id, post_title, post_content from {$name} where post_edit_status = 0 {$sqlLike} limit 10;";
$rs = $mysqli->query($sql);
if($rs && $rs->num_rows > 0):
    while($row = $rs->fetch_assoc()):
    $rows[] = $row;
    endwhile;
else:
    echo 'Nothing';
endif;
$rs->close();
$mysqli->close();
/* 循环输出文章 */
$i = 0;
foreach($rows as $row):
    $retPost[$i]['post_id'] = $row['post_id'];
    $retPost[$i]['post_title'] = $row['post_title'];
    $retPost[$i]['post_content'] = $row['post_content'];
    $i++;
endforeach;

echo json_encode($retPost);