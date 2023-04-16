<?php
require_once("classes.php");
$connector = new Connector();
$connection = $connector->getConnectionToDatabase();
$view_generator = new ViewGenerator();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Wyświetl słówka</title>
    <link rel="stylesheet" href="stylesheets/v_words.css">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>
    <section>
        <a href="index.html" id="home-ico"><i class="fa-solid fa-house"></i></a>
        <select onchange="getTableWordsView(this.value)">
            <?php
            $view_generator->setUnitsSelectView($connection, "unit");
            ?>
        </select>
    </section>
    <table id="table-view">
    </table>
    <script src="summon-php-scripts.js"></script>
</body>

</html>