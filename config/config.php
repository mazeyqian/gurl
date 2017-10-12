<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once (dirname( __FILE__ ).'/mysql.php');
require_once (dirname( __FILE__ ).'/mysqli-connect.php');

$keywords = array(
    '白发', '头发', '洗发', '护发', '养发', '头皮', '头疗', '肾', '血热', '脱发', '毛发', '脱落', '毛孔', '斑秃', '掉发', '秃顶', '谢顶'
);

function alertBack($str) {
    die("<script>alert('{$str}');window.self.location=document.referrer;</script>");
}

function generateSQLLike($arr) {
    $ret = '';
    foreach($arr as $val):
        $ret .= " or post_content like '%{$val}%'";
    endforeach;
    $ret = " and (1 = 0 {$ret})";
    return $ret;
}

function strongStrByArr($arr, $content) {
    $ret = '';
    foreach($arr as $val):
        $content = str_replace($val, "<strong class='tag'>{$val}</strong>", $content);
    endforeach;
    return $content;
}
?>