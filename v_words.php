<!DOCTYPE html>
<html>

<head>
    <title>Wyświetl słówka</title>
    <link rel="stylesheet" href="stylesheets/v_words.css">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <section>
        <a href="index.html"><img src="images/home.png" id="go-home"></a>
        <select onchange="getTableWordsView(this.value)">
            <?php
        include "classes.php";
        $connector = new Connector();
        $view_generator = new ViewGenerator();
        $connection = $connector->getConnectionToDatabase();
        $view_generator->setUnitsSelectView($connection, "unit");
        mysqli_close($connection);
        ?>
        </select>
    </section>
    <table id="table-view">
    </table>
    <script src="summon-php-scripts.js"></script>
</body>

</html>