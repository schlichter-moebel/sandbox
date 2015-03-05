<?php

include 'config.php';


if (!mysql_connect($config['hostname'], $config['username'], $config['password'])) die('Verbindung schlug fehl: ' . mysql_error());
mysql_select_db($config['database']);

$result = mysql_query("SELECT * FROM posts");
$num_rows = mysql_num_rows($result);
$blog_twitter = "dropplets";
mysql_close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo "Welcome to Dropplets"; ?></title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="subdiv.css">
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'
    </head>
    <body>
        <a href="new.php">New Post</a>
            <?php for($i=0;$i<$num_rows;$i++):
                $article = mysql_fetch_object($result);
            ?>
            <article>
            <div class="row">
                <div class="one-quarter meta">
                    <div class="thumbnail">
                        <img src="dropplets.jpg" />
                    </div>
                    <ul>
                        <li><?php echo $article->title; ?></li>
                        <li><a href="mailto:<?php echo $article->email; ?>?subject=Hello Welt"><?php echo $article->email; ?></a></li>
                        <li><a href="http://twitter.com/<?php echo $blog_twitter; ?>">&#64;<?php echo $blog_twitter; ?></a></li>
                        <li></li>
                    </ul>
                </div>
                <div class="three-quarters post">
                    <h2><?php echo $article->title; ?></h2>
                    <p><?php echo $article->text; ?></p>
                </div>
            </div>
        </article>
        <?php endfor; ?>
    </body>
</html>
