<!DOCTYPE html>
<html>

<head>
    <title>Dodaj słówko</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/mainstyle.css">
    <link rel="stylesheet" href="../stylesheets/v_add-words.css">

</head>

<body>
    <a href="../index.html"><img src="../images/home.png" id="go-home"></a><br>

    <div>
        <?php
        include ("../classes.php");
        $connector = new Connector();
        $connection = $connector->getConnectionToDatabase();

        $booleans = ["en", "pl", "unit", "subunit"];

        foreach ($booleans as $i) {
            $bool = "b_" . $i;
            $$bool = isset($POST[$i]);
            if($$bool) {
                if(empty($POST[$i])) {
                    $$bool = false;
                } else {
                    $$bool = true;
                }
            }
        }
        if ($b_en && $b_pl && $b_unit && $b_subunit) {
            $en = $_POST["en"];
            $pl = $_POST["pl"];
            $unit = $_POST["unit"];
            $subunit = $_POST["subunit"];
            $query = "INSERT INTO words (`english`, `polish`, `unit`, `subunit`) VALUES ('{$en}', '{$pl}', '{$unit}', '{$subunit}');";
            $connection->query($query);
        } else {
            echo "<h3>Nie wstawiono słówka!</h3>";
        }
        ?>
    </div>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <section>
            <label for="unit">Dział: </label>
            <input type="number" id="unit" name="unit" min="0" max="8" value=<?php if ($b_unit)
                echo "{$unit}"; ?> required>
            <label for="subunit">Pod dział:</label>
            <input type="number" id="subunit" name="subunit" min="0" max="8" value=<?php if ($b_subunit)
                echo "{$subunit}"; ?> required>
        </section>
        <label for="en">Angielski:</label>
        <input lang="en-GB" id="en" type="text" name="en" autocomplete="off" autofocus autocapitalize="off" required>
        <label for="pl">Polski: </label>
        <input type="text" id="pl" name="pl" autocomplete="off" autocapitalize="off" required>

        <input type="submit" value="Dodaj">
    </form>
</body>

</html>