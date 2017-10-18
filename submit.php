<?php require_once ( dirname(__FILE__) . '/config/config.php' ); ?>

<?php
    $post_id = $_GET['post_id'];
    $name = $_GET['name'];
    $act = $_GET['act'];
    $rows = array();
    $resultReturn = 'Unknow';
    $sql = "select post_title, post_content, post_tag, post_category, post_date from {$name} where post_id = {$post_id};";
    $rs = $mysqli->query($sql);
    if($rs && $rs->num_rows == 1):
        while($row = $rs->fetch_assoc()):
            $rows[] = $row;
        endwhile;
    else:
        /* die($sql); */
        alertBack('没有数据');
    endif;

    /* 数据处理 */
    foreach($rows as $row):
        $title = $row['post_title'];
        $content = $row['post_content'];
        $tag = $row['post_tag'];
        $category = $row['post_category'];
        $date = $row['post_date'];
    endforeach;

    // echo $title;
    // echo $content;

    $href_pattern = '/(href=[\'|\"])http[s]{0,1}:\/\/.+([\'|\"])/U';
    $href_replace = '$1#link$2';

    /* $img_pattern = '/(<center><p\sstyle=\"text-align:\scenter\">)?<img.+\/?>(<\/p><\/center>)?/'; */
    $img_pattern = '/<img.+\/?>/U';
    $img_replace = '';

    $other1_pattern = '/<p\sclass=\"moblePageBreak\"\sstyle=\"display:\snone;\">&nbsp;<\/p>/U';
    $other1_replace = '';

    $other2_pattern = '/target=[\'|\"]_blank[\'|\"]/U';
    $other2_replace = '';

    $other3_pattern = '/style=[\'|\"].*[\'|\"]/U';
    $other3_replace = '';


    $pattern = array(
        $href_pattern,
        $img_pattern,
        $other1_pattern,
        $other3_pattern/* ,
        $other2_pattern */
    );
    $replace = array(
        $href_replace,
        $img_replace,
        $other1_replace,
        $other3_replace/* ,
        $other2_replace */
    );

    $result1 = preg_replace($pattern, $replace, $content);

    // echo "<pre>{$result1}</pre>";

    $post_title = $title;
    $post_content = $result1;
    $post_tag = $tag;
    $post_category = $category;
    $post_date = $date;

    switch($act):
        case 'submit':
            /* 加重关键词 */
            $listStrongStrByArr = strongStrByArr($keywords, $post_content);
            $post_content = $listStrongStrByArr['0'];
            /* 替换主语 */
            $post_content = str_replace('小编', '治白发网', $post_content);
            $post_content = str_replace('民福康', '治白发网', $post_content);
            $post_content = str_replace('本篇文章版权归三九养生堂所有，未经许可，谢绝转载。', '治白发网部分内容来自网络，本站信息仅供参考，不能作为诊断及治疗的依据。', $post_content);
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
                    alertBack('改变状态失败-2');
                endif;
                alertBack('已存在');
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
                    alertBack('改变状态失败-3');
                endif;
                /* die(); */
                $resultReturn = '提交成功';
            else:
                $resultReturn = '数据库错误';
            endif;
            break;
        case 'delete':
            /* 修改状态为已删除 */
            $rs3 = $mysqli->query("update {$name} set post_edit_status = 1 where post_id = {$post_id};");
            if(!$rs3):
                alertBack('改变状态失败-1');
            endif;
            $resultReturn = '删除成功';
            break;
        case 'push':
        case 'strongpush':
        case 'pushimmediately':
            $post_status = @$_GET['post_status'];
            /*  验证非法关键词 */
            if(!isIllegality($post_content) && $act != 'strongpush'):
                alertBack('内容包含非法关键词');
            endif;
            if($act == 'pushimmediately'):
                $post_date = date("Y-m-d H:i:s");
            endif;
            $resultPost = request_post('http://www.zhibaifa.com/wp-insert-post', array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_tag' => $post_tag,
                'post_category' => $post_category,
                'post_status' => $post_status,
                'post_date' => $post_date
            ));
            if($resultPost > 0):
                /* 修改状态为已推送 */
                $rs3 = $mysqli->query("update {$name} set post_edit_status = 4 where post_id = {$post_id};");
                if(!$rs3):
                    alertBack('改变状态失败-4');
                endif;
                $resultReturn = '推送成功';
            else:
                /* die($resultPost); */
                $resultReturn = '推送失败';
            endif;
            break;
        default:
            $resultReturn = 'ERROR: CASE?';
    endswitch;

    $rs->close();
    $mysqli->close();
    alertBack($resultReturn);
?>
