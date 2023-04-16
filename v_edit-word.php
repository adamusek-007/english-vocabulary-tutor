<!DOCTYPE html>
<html>

<head>
    <title>Edytuj słówko</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/v_add-words.css">

</head>

<body>
    <div>
    <?php
    include "classes.php";
    $connector = new Connector();
    $connection = $connector->getConnectionToDatabase();

    $id = $_REQUEST["id"];
    $query = "SELECT * FROM `words` WHERE `id` = ${id}";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $en = $row["english"];
    $pl = $row["polish"];
    $unit = $row["unit"];
    $hint = $row["hint"];

    $subunit = $row["subunit"];

    ?>
    </div>
    <form method="POST">
        <section>
            <label for="unit">Dział: </label>
            <input type="number" id="unit" name="unit" min="0" max="8" value=<?php echo $unit ?>>
            <label for="subunit">Pod dział:</label>
            <input type="number" id="subunit" name="subunit" min="0" max="8" value=<?php echo $subunit; ?>>
        </section>
        <input type="hidden" id="word-id" value="<?php echo $id ?>">
        <label for="en">Angielski:</label>
        <input lang="en" id="en" type="text" name="en-GB" autocomplete="off" autofocus autocapitalize="off" value=<?php echo "${en}"; ?>>

        <label for="pl">Polski:</label>
        <input type="text" id="pl" name="pl" autocomplete="off" autocapitalize="off" value="<?php echo $pl; ?>">

        <label for="hint">Podpowiedź:</label>
        <input type="text" id="hint" name="hint" autocomplete="off" autocapitalize="off">
        <input type="button" value="Zatwierdź zmiany" onclick="updateWord()">
    </form>
</body>
<script src="summon-php-scripts.js"></script>

</html>