<!DOCTYPE html>
<html>

<head>
    <title>Wybierz tryb</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/v_mode-selection.css">
</head>

<body onload="getWordCount();">
    <div>
        <?php
        include "classes.php";
        $connector = new Connector();
        $connection = $connector->getConnectionToDatabase();
        $view_generator = new ViewGenerator();
        ?>
    </div>
    <a href="index.html"><img src="images/home.png" id="go-home"></a>
    <form action="v_input.php" method="POST">
        <label for="unit-sel">Wybierz dział</label>
        <select id="unit-sel" name="u-sel-unit" onchange="changeSubunitSelection(this)">
            <?php
            $view_generator->setUnitsSelectView($connection, "unit");
            mysqli_close($connection);
            ?>
        </select>
        <label for="subunit-sel">Wybierz pod-dział</label>
        <select id="subunit-sel" name="u-sel-subunit" onchange="getWordCount()">
            <option>Wszystkie</option>
        </select>
        <label for="generating-mode-sel">Wybierz tryb</label>
        <select id="generating-mode-sel" name="u-sel-generating-mode" onchange="getWordCount()">
            <option value="random">Losowo</option>
            <option value="worst-procentage">Najgorszy współczynnik odpowiedzi</option>
            <option value="dont-know">Nie umiem</option>
            <option value="know">Umiem</option>
            <option value="know-well">Umiem bardzo dobrze</option>
            <option value="no-answer">Te na które jeszcze nie odpowiedziałem</option>
            <option value="optimal">Optymalnie pod kątem zapamiętywania</option>
        </select>
        <label for="u-sel-answering-mode">Tryb odpowiadania</label>
        <select name="u-sel-answering-mode">
            <option value="reply-choose">Wybierz spośród kilku różnych odpowiedzi</option>
            <option value="reply_manualy">Wpisz odpowiedź ręcznie</option>
        </select>
        <label class="inline" for="u-sel-hints-mode">Włącz podpowiedzi</label>
        <input class="inline" name="u-sel-hints-mode" value="on" type="checkbox"><br>
        <input name="website" value="mode-selection" type="hidden">
        <input type="submit" value="Zatwierdź">
        <label id="word-count"></label>
    </form>
    <script src="summon-php-scripts.js"></script>
</body>

</html>