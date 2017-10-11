<?php require_once ( dirname(__FILE__) . '/config/config.php' ); ?>

<?php
    $id = $_GET['id'];
    $rows = array();
    $sql = "select article_title, article_content from 39yst_1 where id = {$id};";
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
        $title = $row['article_title'];
        $content = $row['article_content'];
    endforeach;

    echo $title;
    echo $content;

    $href_pattern = '/(href=[\'|\"])http[s]{0,1}:\/\/.+([\'|\"])/U';
    $href_replace = '$1#link$2';

    $pattern = array(
        $href_pattern
    );
    $replace = array(
        $href_replace
    );


    $result1 = preg_replace($pattern, $replace, $content);

    echo "<pre>{$result1}</pre>";
?>
