<?php require_once ( dirname(__FILE__) . '/config/config.php' );?>
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
        <h1 class="text-center">爬虫 - 展示筛选</h1>
        <div class="container">

        <?php
            $rows = array();
            $sql = 'select article_title, article_content from 39yst_1;';
            $rs = $mysqli->query($sql);
            if($rs && $rs->num_rows > 0):
                while($row = $rs->fetch_assoc()):
                    $rows[] = $row;
                endwhile;
            else:
                echo 'nothing';
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
                            <h2 class="text-center"><?php echo $row['article_title']; ?></h2>
                        </header>
                        <div>
                            <?php echo $row['article_content']; ?>
                        </div>
                    </article>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </body>
</html>