<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once (dirname( __FILE__ ).'/mysql.php');
require_once (dirname( __FILE__ ).'/mysqli-connect.php');


function alertBack($str) {
    die("<script>alert('{$str}');window.self.location=document.referrer;</script>");
}
?>