<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once (dirname( __FILE__ ).'/mysql.php');
require_once (dirname( __FILE__ ).'/mysqli-connect.php');

$keywords = array(
    /* 白发 */
    '25001' => '白发',
    /* 头发 */
    '26001' => '头发',
    '26002' => '头皮',
    /* 护发 */
    '27001' => '洗发',
    '27002' => '洗头',
    '27003' => '护发',
    '27004' => '养发',
    '27005' => '头疗',
    /* 脱发 */
    '28001' => '脱发',
    '28002' => '毛发',
    '28003' => '脱落',
    '28004' => '斑秃',
    '28005' => '掉发',
    '28006' => '秃顶',
    '28007' => '谢顶',
    /* 健康 */
    '29001' => '肾',
    '29002' => '血热',
    '29003' => '毛孔'
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
    $category = array();
    foreach($arr as $key => $val):
        if(strpos($content, $val) !== false):
            $content = str_replace($val, "<strong class='tag'>{$val}</strong>", $content);
            $tag[] = $val;
            $category[] = substr($key, 0, 2);
        endif;
    endforeach;
    $ret['0'] = $content;
    $ret['1'] = $tag;
    $ret['2'] = implode(',', $category);
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