<?php
include("../classes.php");
$connector = new Connector();
$connection = $connector->getConnectionToDatabase();
$view_generator = new ViewGenerator();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Wybierz tryb</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/mainstyle.css">
    <link rel="stylesheet" href="../stylesheets/v_mode-selection.css">
</head>

<body onload="getWordCount();">
    <a href="../index.html"><i class="fa-solid fa-house"></i></a>
    <form action="v_input.php" method="POST">
        <label for="unit-sel">Wybierz dział</label>
        <select id="unit-sel" name="u-sel-unit" onchange="setSubunitSelection(this)">
            <?php
            $view_generator->setUnitsSelectView($connection, "unit");
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
            <option value="optimal" selected>Optymalnie pod kątem zapamiętywania</option>
        </select>
        <label for="u-sel-answering-mode">Tryb odpowiadania</label>
        <select name="u-sel-answering-mode">
            <option value="reply-choose">Wybierz spośród kilku różnych odpowiedzi</option>
            <option value="reply_manualy" selected>Wpisz odpowiedź ręcznie</option>
        </select>
        <label class="inline" for="u-sel-hints-mode">Włącz podpowiedzi</label>
        <input class="inline" name="u-sel-hints-mode" value="on" type="checkbox"><br>
        <input name="website" value="mode-selection" type="hidden">
        <input type="submit" value="Zatwierdź">
        <label id="word-count"></label>
    </form>
    <script>
        function getWordCount() {
            const xhttp = new XMLHttpRequest();
            var unitSelection = document.getElementById("unit-sel").value;
            var subunitSelection = document.getElementById("subunit-sel").value;
            var mode = document.getElementById("generating-mode-sel").value;
            xhttp.onload = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var wordCountText = "Wybrana liczba słówek: " + this.responseText;
                    document.getElementById("word-count").innerHTML = wordCountText;
                }
            }
            xhttp.open("GET", "../s_get-selection-word-count.php?unit=" + unitSelection + "&subunit=" + subunitSelection + "&mode=" + mode);
            xhttp.send();
        }
        function setSubunitSelection(unitSelection) {
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("subunit-sel").innerHTML = this.responseText;
                    getWordCount();
                }
            }
            xhttp.open("GET", "s_get-subunit.php?s=" + unitSelection.value);
            xhttp.send();
        }
    </script>
    <script src="https://kit.fontawesome.com/4de8e58cfc.js" crossorigin="anonymous"></script>
</body>

</html>