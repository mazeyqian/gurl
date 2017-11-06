<?php require_once ( dirname(__FILE__) . '/config/config.php' ); ?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>爬虫 - 展示筛选</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <h1 class="text-center"><a href="/">爬虫 - 展示筛选</a></h1>
        <div class="container">

        <?php
            $name = @$_GET['name'];
            $sqlLike = '';
            //$sqlLike = generateSQLLike($keywords);
            //$sqlLike = " and post_content like '%白发%'";
            //$sqlLike = " and (post_content like '%维生素E%' )";
            if($name == ''):
                $name = 'wp';
            endif;
            $rows = array();
            $sql = "select post_id, post_title, post_content from {$name} where post_edit_status = 0 {$sqlLike} limit 10;";
            echo "<script>console.log('{$sql}');</script>";
            $rs = $mysqli->query($sql);
            if($rs && $rs->num_rows > 0):
                while($row = $rs->fetch_assoc()):
                    $rows[] = $row;
                endwhile;
            else:
                echo 'Nothing';
            endif;
            $rs->close();
            $mysqli->close();
            /* 循环输出文章 */
            foreach($rows as $row):
        ?>
            <div class="row">
                <div class="col-md-8">
                    <article>
                        <header>
                            <h2 class="text-center"><?php echo $row['post_title']; ?></h2>
                        </header>
                        <div contenteditable="true">
                            <?php echo $row['post_content']; ?>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                <?php if($name == 'wp'): ?>
                    <a href="submit.php?act=pushimmediately&post_status=publish&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-success">立即发布</a>
                    <a href="submit.php?act=push&post_status=future&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-success">定时发布</a>
                    <a href="submit.php?act=push&post_status=draft&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-info">草稿</a>
                    <a href="submit.php?act=strongpush&post_status=draft&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-warning">强推</a>
                <?php else: ?>
                    <a href="submit.php?act=submit&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-primary">提交</a>
                <?php endif; ?>
                    <a href="submit.php?act=delete&name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-danger">删除</a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </body>
</html>