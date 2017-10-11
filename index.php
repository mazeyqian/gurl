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
            $name = $_GET['name'];
            if($name == ''):
                $name = 'wp';
            endif;
            $rows = array();
            $sql = "select post_id, post_title, post_content from {$name} limit 20;";
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
                        <div>
                            <?php echo $row['post_content']; ?>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <a href="submit.php?name=<?php echo $name; ?>&post_id=<?php echo $row['post_id']; ?>" class="btn btn-primary">提交</a>
                    <a href="#" class="btn btn-danger">删除</a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </body>
</html>