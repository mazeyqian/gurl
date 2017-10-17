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
    '28008' => '光头',
    /* 健康 */
    '29001' => '肾',
    '29002' => '血热',
    '29003' => '毛孔',
    /* 食疗 */
    '31001' => '黑芝麻',
    '31002' => '黑豆',
    '31003' => '黑木耳',
    '31004' => '核桃',
    '31005' => '猕猴桃',
    '31006' => '奇异果',
    '31007' => '黑米',
    '31008' => '红豆',
    '31009' => '六味地黄丸',
    '31010' => '淘米水',
    '31011' => '西红花',
    '31012' => '番红花',
    '31013' => '桑葚',
    '31014' => '内脏',
    '31015' => '肝脏',
    '31016' => '维生素',
    '31017' => '何首乌',
    '31018' => '当归',
    '31019' => '人参',
    '31020' => '紫糯米',
    '31021' => '桂圆',
    '31022' => '松子',
    '31023' => '枣',
    '31024' => '乌骨',
    /* 理疗 */
    '32001' => '按摩',
    '32002' => '侧柏',
    '32003' => '梳头',
    '32004' => '睡眠',
    '32005' => '早睡',
    '32006' => '美容觉',
    '32007' => '运动',
    '32008' => '跑步',
    '32009' => '梳头',
    '32010' => '生姜',
    /* 商家 */
    '33001' => '诗碧曼',
    '33002' => '岳灵',
    '33003' => '章光101',
    '33004' => '一吃黑',
    /* 染发 */
    '34001' => '染发',
    '34002' => '海娜花',
    '34003' => '指甲花',
    '34004' => '海娜粉',
    /* 发型 */
    '35001' => '发型',
    '35002' => '造型',
    '35003' => '烫发',
    /* 产品 */
    '36001' => '梳子',
    '36002' => '牛角梳',
    '36003' => '枕头',
    '36004' => '头套',
    '36005' => '精油',
    /* 原因 */
    '37001' => '少年白',
    '37002' => '遗传',
    '37003' => '熬夜',
    '37004' => '压力',
    '37005' => '营养',
    '37006' => '基因'
);

$web = array(
    '39yst',
    '三九养生堂'
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
            /* 关键词出现在a标签中会混乱 */
            //$content = str_replace($val, "<strong class='tag'>{$val}</strong>", $content);
            $tag[] = $val;
            $category[] = substr($key, 0, 2);
        endif;
    endforeach;
    $ret['0'] = $content;
    $ret['1'] = $tag;
    $ret['2'] = implode(',', array_unique($category));
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

/* 验证是否含有非法关键词 */
function isIllegality($str) {
    global $web;
    foreach($web as $val):
        if(strpos($str, $val) !== false):
            return false;
        endif;
    endforeach;
    return true;
}

/* 计算发布时间 */
function getPostDate() {
    global $mysqli;
    $nowDate = date("Y-m-d H:i:s");
    //return $nowDate;
    $rows = array();
    /* 获取最新时间 */
    $sql = "select post_date from wp order by post_date desc limit 1;";
    $rs = $mysqli->query($sql);
    if($rs && $rs->num_rows > 0):
        while($row = $rs->fetch_assoc()):
            $rows[] = $row;
        endwhile;
        foreach($rows as $row):
            $lastestDate = $row['post_date'];
        endforeach;
    else:
        /* 没有记录返回当前时间 */
        return $nowDate;
    endif;
    $thisDate = strtotime('+2 hour', strtotime($lastestDate)) >= strtotime($nowDate) ? date('Y-m-d H:i:s',strtotime('+2 hour', strtotime($lastestDate))) : $nowDate;
    return $thisDate;
}
?>