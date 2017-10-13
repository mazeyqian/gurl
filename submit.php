<?php require_once ( dirname(__FILE__) . '/config/config.php' ); ?>

<?php
    $post_id = $_GET['post_id'];
    $name = $_GET['name'];
    $act = $_GET['act'];
    $rows = array();
    $resultReturn = 'Unknow';
    $sql = "select post_title, post_content, post_tag, post_category from {$name} where post_id = {$post_id};";
    $rs = $mysqli->query($sql);
    if($rs && $rs->num_rows == 1):
        while($row = $rs->fetch_assoc()):
            $rows[] = $row;
        endwhile;
    else:
        alertBack('没有数据');
    endif;

    /* 数据处理 */
    foreach($rows as $row):
        $title = $row['post_title'];
        $content = $row['post_content'];
        $tag = $row['post_tag'];
        $category = $row['post_category'];
    endforeach;

    // echo $title;
    // echo $content;

    $href_pattern = '/(href=[\'|\"])http[s]{0,1}:\/\/.+([\'|\"])/U';
    $href_replace = '$1#link$2';

    /* $img_pattern = '/(<center><p\sstyle=\"text-align:\scenter\">)?<img.+\/?>(<\/p><\/center>)?/'; */
    $img_pattern = '/<img.+\/?>/U';
    $img_replace = '';

    $other1_pattern = '/<p\sclass=\"moblePageBreak\"\sstyle=\"display:\snone;\">&nbsp;<\/p>/';
    $other1_replace = '';

    $other2_pattern = '/target=[\'|\"]_blank[\'|\"]/';
    $other2_replace = '';


    $pattern = array(
        $href_pattern,
        $img_pattern,
        $other1_pattern/* ,
        $other2_pattern */
    );
    $replace = array(
        $href_replace,
        $img_replace,
        $other1_replace/* ,
        $other2_replace */
    );

    $result1 = preg_replace($pattern, $replace, $content);

    // echo "<pre>{$result1}</pre>";

    $post_title = $title;
    $post_content = $result1;
    $post_tag = $tag;
    $post_category = $category;

    switch($act):
        case 'submit':
            /* 加重关键词 */
            $listStrongStrByArr = strongStrByArr($keywords, $post_content);
            $post_content = $listStrongStrByArr['0'];
            $post_tag = implode(',',$listStrongStrByArr['1']);
            $post_category = $listStrongStrByArr['2'];
            /* die($post_category); */
            /* 验证同样标题是否已存在 */
            $sqlCheckExist = "select 1 from wp where post_title = '{$post_title}';";
            $rs2 = $mysqli->query($sqlCheckExist);
            /* print_r($rs2);
            die(); */
            if($rs2 && $rs2->num_rows > 1):
                /* 修改状态为已删除 */
                $rs3 = $mysqli->query("update {$name} set post_edit_status = 2 where post_id = {$post_id};");
                if(!$rs3):
                    alertBack('改变状态失败-2');
                endif;
                alertBack('已存在');
            endif;

            /* 通过初审插入待发布表 */
            $sqlInsert = "insert into wp (post_title, post_content, post_tag, post_category) values(?, ?, ?, ?);";
            $mysqli_stmt = $mysqli->prepare($sqlInsert);
            $mysqli_stmt->bind_param('ssss', $post_title, $post_content, $post_tag, $post_category);
            $rs1 = $mysqli_stmt->execute();
            if($rs1):
                /* 修改状态为已提交 */
                $rs3 = $mysqli->query("update {$name} set post_edit_status = 3 where post_id = {$post_id};");
                if(!$rs3):
                    alertBack('改变状态失败-3');
                endif;
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
            $post_status = @$_GET['post_status'];
            $resultPost = request_post('http://www.zhibaifa.com/wp-insert-post', array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_tag' => $post_tag,
                'post_category' => $post_category,
                'post_status' => $post_status
            ));
            if($resultPost > 0):
                /* 修改状态为已推送 */
                $rs3 = $mysqli->query("update {$name} set post_edit_status = 4 where post_id = {$post_id};");
                if(!$rs3):
                    alertBack('改变状态失败-4');
                endif;
                $resultReturn = '推送成功';
            else:
                die($resultPost);
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
