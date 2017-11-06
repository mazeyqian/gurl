<?php
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

require_once ( dirname(__FILE__) . '/../config/config.php' );

$post_id = $_POST['post_id'];
$post_title = $_POST['post_title'];
$post_content = $_POST['post_content'];
$act = $_POST['act'];
$name = $_POST['name'];//'39yst_3';

switch($act):
    case 'submit':
        $href_pattern = '/(href=[\'|\"])http[s]{0,1}:\/\/.+([\'|\"])/U';
        $href_replace = '$1#link$2';

        $img_pattern = '/<img.+\/?>/U';
        $img_replace = '';

        $other1_pattern = '/<p\sclass=\"moblePageBreak\"\sstyle=\"display:\snone;\">&nbsp;<\/p>/U';
        $other1_replace = '';

        $other3_pattern = '/style=[\'|\"].*[\'|\"]/U';
        $other3_replace = '';


        $pattern = array(
            $href_pattern,
            $img_pattern,
            $other1_pattern,
            $other3_pattern
        );
        $replace = array(
            $href_replace,
            $img_replace,
            $other1_replace,
            $other3_replace
        );

        $post_content = preg_replace($pattern, $replace, $post_content);

        /* 加重关键词 */
        $listStrongStrByArr = strongStrByArr($keywords, $post_content);
        $post_content = $listStrongStrByArr['0'];
        /* 替换主语 */
        $post_content = str_replace('小编', '治白发网', $post_content);
        //$post_content = str_replace('本文', '治白发网', $post_content);
        $post_content = str_replace('民福康', '治白发网', $post_content);
        $post_content = str_replace('本篇文章版权归三九养生堂所有，未经许可，谢绝转载。', '治白发网部分内容来自网络，本站信息仅供参考，不能作为诊断及治疗的依据。', $post_content);
        $post_content = str_replace('本篇文章版权归三九养生堂所有', '治白发网部分内容来自网络，本站信息仅供参考，不能作为诊断及治疗的依据。', $post_content);
        $post_content = str_replace('三九养生堂', '治白发网', $post_content);
        $post_content = str_replace('结语：', '治白发网：', $post_content);
        $post_content = str_replace('总结：', '治白发网：', $post_content);
        $post_tag = implode(',',$listStrongStrByArr['1']);
        $post_category = $listStrongStrByArr['2'];
        $post_date = getPostDate();
        /* die($post_category); */
        /* 验证同样标题是否已存在 */
        $sqlCheckExist = "select 1 from wp where post_title = '{$post_title}';";
        $rs2 = $mysqli->query($sqlCheckExist);
        /* print_r($rs2);
        die(); */
        if($rs2 && $rs2->num_rows > 0):
            /* 修改状态为已删除 */
            $rs3 = $mysqli->query("update {$name} set post_edit_status = 2 where post_id = {$post_id};");
            if(!$rs3):
                die(json_encode(array('ret' => '改变状态失败-2')));
            endif;
            /* alertBack('已存在'); */
            die(json_encode(array('ret' => '已存在')));
        endif;

        /* 通过初审插入待发布表 */
        $sqlInsert = "insert into wp (post_title, post_content, post_tag, post_category, post_date) values(?, ?, ?, ?, ?);";
        $mysqli_stmt = $mysqli->prepare($sqlInsert);
        $mysqli_stmt->bind_param('sssss', $post_title, $post_content, $post_tag, $post_category, $post_date);
        $rs1 = $mysqli_stmt->execute();
        if($rs1):
            /* 修改状态为已提交 */
            $rs3 = $mysqli->query("update {$name} set post_edit_status = 3 where post_id = {$post_id};");
            if(!$rs3):
                /* alertBack('改变状态失败-3'); */
                die(json_encode(array('ret' => '改变状态失败-3')));
            endif;
            /* die(); */
            /* $resultReturn = '提交成功'; */
            die(json_encode(array('ret' => '提交成功')));
        else:
            /* $resultReturn = '数据库错误'; */
            die(json_encode(array('ret' => '数据库错误')));
        endif;
        break;
    case 'delete':
        /* 修改状态为已删除 */
        $rs3 = $mysqli->query("update {$name} set post_edit_status = 1 where post_id = {$post_id};");
        if(!$rs3):
        /* alertBack('改变状态失败-1'); */
        die(json_encode(array('ret' => '改变状态失败-1')));
        endif;
        /* $resultReturn = '删除成功'; */
        die(json_encode(array('ret' => '删除成功')));
        break;
    default:
        /* $resultReturn = 'ERROR: CASE?'; */
        die(json_encode(array('ret' => 'ERROR: CASE?')));
endswitch;