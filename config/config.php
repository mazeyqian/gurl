<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once (dirname( __FILE__ ).'/mysql.php');
require_once (dirname( __FILE__ ).'/mysqli-connect.php');

$keywords = array(
    '4' => '白发',
    '5' => '头发',
    '6' => '洗发',
    '7' => '洗头',
    '8' => '护发',
    '9' => '养发',
    '10' => '头皮',
    '11' => '头疗',
    '12' => '肾',
    '13' => '血热',
    '14' => '脱发',
    '15' => '毛发',
    '16' => '脱落',
    '17' => '毛孔',
    '18' => '斑秃',
    '19' => '掉发',
    '20' => '秃顶',
    '4' => '谢顶'
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
    $ret = array();
    $tag = array();
    foreach($arr as $val):
        if(strpos($content, $val) !== false):
            $content = str_replace($val, "<strong class='tag'>{$val}</strong>", $content);
            $tag[] = $val;
        endif;
    endforeach;
    $ret['0'] = $content;
    $ret['1'] = $tag;
    return $ret;
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