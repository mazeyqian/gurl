<?php require_once ( dirname(__FILE__) . '/config/config.php' ); ?>

<?php
    $post_id = $_GET['post_id'];
    $name = $_GET['name'];
    $rows = array();
    $sql = "select post_title, post_content from {$name} where post_id = {$post_id};";
    $rs = $mysqli->query($sql);
    if($rs && $rs->row_nums=1):
        while($row = $rs->fetch_assoc()):
            $rows[] = $row;
        endwhile;
    else:
        echo "Nothing!";
    endif;

    /* 数据处理 */
    foreach($rows as $row):
        $title = $row['post_title'];
        $content = $row['post_content'];
    endforeach;

    echo $title;
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

    echo "<pre>{$result1}</pre>";
?>
