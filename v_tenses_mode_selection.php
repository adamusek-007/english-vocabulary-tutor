<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/v_tenses.css">
</head>

<body>
    <form action="v_tenses.php">
        <select>
            <optgroup label="Czasy teraźniejsze">
                <option>Present Simple</option>
                <option>Present Continuous</option>
                <option>Present Perfect</option>
                <option>Present Perfect Continuous</option>
            </optgroup>
            <optgroup label="Czasy przeszłe">
                <option>Past Simple</option>
                <option>Past Continuous</option>
                <option>Past Perfect</option>
                <option>Past Perfect Continuous</option>
            </optgroup>
            <optgroup label="Czasy przyszłe">
                <option>Future Simple</option>
                <option>Future Continuous</option>
                <option>Future Perfect</option>
                <option>Future Perfect Continuous</option>
            </optgroup>
        </select>
        <fieldset>
            <legend>Wybierz jak chcesz się uczyć</legend>
            <div class="row">
                <label>Teoria</label>
                <input type="radio" name="learn_mode" <?php if (
                    isset($learn_mode) &&
                    $learn_mode == "0")
                    echo "checked"; ?> value="0">
            </div><br>
            <div class="row">
                <label>Praktyka</label>
                <input type="radio" name="learn_mode" <?php if (
                    isset($learn_mode) &&
                    $learn_mode == "1")
                    echo "checked"; ?> value="1">
            </div>
        </fieldset>
        <input type="submit" value="Zatwierdź">
    </form>
</body>

</html>