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

/**
 * 模拟post进行url请求
 * @param string $url
 * @param array $post_data
 */
function request_post($url = '', $post_data = array()) {
    if (empty($url) || empty($post_data)) {
        return false;
    }

    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}
?>