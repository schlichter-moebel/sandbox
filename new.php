<?php

include 'config.php';

if ($_POST) {
    $titel = $_POST["titel"];
    $slug = $_POST["slug"];
    $text = $_POST["text"];

    if (!mysql_connect($config['hostname'], $config['username'], $config['password'])) die('Verbindung schlug fehl: ' . mysql_error());
    mysql_select_db($config['database']);

    $sql = "INSERT INTO posts (id, title, slug, text, created, status) VALUES ('', '$titel', '$slug', '$text', '', '')";

    if ($result = mysql_query($sql)) {
        echo "New record created successfully. <a href='index.php'>Startseite</a>" ;
    } else {
        echo "Error: " . $sql . "<br>" . mysql_error();
    }

    mysql_close();
}
else
{
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
    <style> ul li {list-style-type: none; } </style>
    <body>
    <article>
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="dropplets.jpg" />
            </div>
            <ul>
                <li>New Post</li>
                <li><a href="index.php">Back</a></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="three-quarters post">
        <form action="new.php" method="post">
             <ul>
                <li><label>Titel</label></li>
                <li><input type="text" name="titel" /></li>
             </ul>
             <ul>
                <li><label>Slug</label></li>
                <li><input type="text" name="slug" /></li>
             </ul>
             <ul>
                <li><label>Text</label></li>
                <li><textarea name="text" rows="5" cols="40"></textarea></li>
             </ul>
             <input type="submit" value="Senden">
        </form>
        </div>
    </div>
    </article>
    </body>
</html>

<?php } ?>