<!DOCTYPE html>
<html>

<head>
    <title>Wynik</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/v_result.css">
</head>

<body>
    <div>
        <?php
        include "classes.php";
        session_start();
        $connector = new Connector();
        $connection = $connector->getConnectionToDatabase();

        $row = $_SESSION["row"];
        $generating_mode = $_SESSION["u-sel-generating-mode"];
        $answering_mode = $_SESSION["u-sel-answering-mode"];
        $row_id = $row["id"];
        $english_word = $row["english"];
        $views = $row["views"];
        $correct_answers = $row["correct_answers"];
        $correct_answers_streak = $row["correct_answers_streak"];

        $user_answer = $_POST["user-answer"];
        $parsed_english_word = parseText($english_word);
        $parsed_user_answer = parseText($user_answer);
        $bool_correct = hash_equals($parsed_english_word, $parsed_user_answer);
        // !Must be before to not get dividing by 0
        if ($bool_correct) {
            $correct_answers += 1;
            $views += 1;
            $correct_answers_streak += 1;
        } else {
            $correct_answers += 0;
            $views += 1;
            $correct_answers_streak = 0;
        }
        $procentage_correct = round($correct_answers / $views * 100, 2);
        updateRow($connection, $bool_correct, $correct_answers, $views, $row_id, $correct_answers_streak, $generating_mode, $row, $answering_mode);

        
        
        //! @Tests
        getArrayOfCorrectAnswers("claim sth");
        $correct_answers_array = getArrayOfCorrectAnswers($english_word);
        $array1 = getArrayOfCorrectAnswers("top-of-the-range");
        $uarray1 = array_unique($array1);
        $endarray = array();
        foreach ($uarray1 as $a) {
            $tempArray = getArrayOfCorrectAnswers($a);
            $endarray = array_merge($endarray, $tempArray);
        }
        $uendarray = array_unique($endarray);

        function updateRow($connection, $bool_correct, $correct_answers, $views, $row_id, $correct_answers_streak, $generating_mode, $row, $answering_mode)
        {
            
            if ($generating_mode == "optimal" && $answering_mode == "reply_manualy") {
                $formated_date_time = getNextRepeatTime($correct_answers_streak);
            } else {
                if (is_null($row["date_time_to_repeat"])) {
                    $formated_date_time = "NULL";
                } else {
                    $formated_date_time = "'" . $row["date_time_to_repeat"] . "'";
                }
            }
            $query = "UPDATE `words` SET `views`='${views}', `correct_answers`='${correct_answers}', `last_viewed`=now(), `correct_answers_streak` = '${correct_answers_streak}', `date_time_to_repeat`=${formated_date_time}  WHERE `id`='${row_id}';";
            mysqli_query($connection, $query);
        }
        function getNextRepeatTime($correct_answers_streak)
        {
            $next_repeat_time = new DateTime("now");
            if ($correct_answers_streak == 0) {
                $next_repeat_time->add(new DateInterval("PT30S"));
            } else if ($correct_answers_streak == 1) {
                $next_repeat_time->add(new DateInterval("PT1M"));
            } else if ($correct_answers_streak == 2) {
                $next_repeat_time->add(new DateInterval("PT3M"));
            } else if ($correct_answers_streak == 3) {
                $next_repeat_time->add(new DateInterval("PT5M"));
            } else if ($correct_answers_streak == 4) {
                $next_repeat_time->add(new DateInterval("PT10M"));
            } else if ($correct_answers_streak == 5) {
                $next_repeat_time->add(new DateInterval("PT30M"));
            } else if ($correct_answers_streak == 6) {
                $next_repeat_time->add(new DateInterval("PT1H"));
            } else if ($correct_answers_streak == 7) {
                $next_repeat_time->add(new DateInterval("PT6H"));
            } else if ($correct_answers_streak == 8) {
                $next_repeat_time->add(new DateInterval("PT12H"));
            } else if ($correct_answers_streak == 9) {
                $next_repeat_time->add(new DateInterval("P1D"));
            } else if ($correct_answers_streak == 10) {
                $next_repeat_time->add(new DateInterval("P3D"));
            } else if ($correct_answers_streak == 11) {
                $next_repeat_time->add(new DateInterval("P1W"));
            } else if ($correct_answers_streak == 12) {
                $next_repeat_time->add(new DateInterval("P2W"));
            } else if ($correct_answers_streak == 13) {
                $next_repeat_time->add(new DateInterval("P1M"));
            } else if ($correct_answers_streak == 13) {
                $next_repeat_time->add(new DateInterval("P3M"));
            }

            $formated_date_time = $next_repeat_time->format("Y-m-d H:i:s");
            $formated_date_time = "'" . $formated_date_time . "'";
            return $formated_date_time;
        }
        function parseText($word)
        {
            $word = trim($word);
            $word = mb_strtolower($word);
            $word = stripcslashes($word);
            $word = htmlspecialchars($word);
            return $word;
        }
        function getArrayOfCorrectAnswers($word)
        {
            $arrayOfAnswers = array();
            $minus = str_replace("-", " ", $word);
            array_push($arrayOfAnswers, $minus);
            $sb = str_replace("sb", "somebody", $word);
            array_push($arrayOfAnswers, $sb);
            $sth = str_replace("sth", "something", $word);
            array_push($arrayOfAnswers, $sth);
            $lower = strtolower($word);
            array_push($arrayOfAnswers, $lower);
            $uc = strtoupper($word);
            array_push($arrayOfAnswers, $uc);
            $ucf = ucfirst($word);
            array_push($arrayOfAnswers, $ucf);
            $ucword = ucwords($word);
            array_push($arrayOfAnswers, $ucword);
            return $arrayOfAnswers;
        }
        mysqli_close($connection);
        ?>
    </div>

    <?php
    if ($bool_correct) {
        echo "<img class='mainimg' src='images/ok.png'>";
    } else {
        echo "<img class='mainimg' src='images/notok.png'>";
    }
    ?>
    <section id="top-section">
        <div>
            <?php
            echo "<img src=images/ok.png><p class='correct-answer'>${english_word}</p>";
            ?>
        </div>
        <div>
            <?php
            if ($bool_correct == false) {
                echo "<img src=images/user_icon.png><p class='wrong-answer'>${parsed_user_answer}</p>";
            }
            ?>
        </div>
    </section>
    <form action="v_input.php" method="POST" name="form">
        <fieldset id="form-rating-section">
            <legend>Oceń swoją odpowiedź</legend>
            <section class="row">
                <label for="user-points">Nie umiem</label>
                <input type="radio" name="user-points" class="punctationBtn" value="0" <?php if (
                    isset($punctation) &&
                    $punctation == "0" || $bool_correct != true
                )
                    echo "checked"; ?>><br>
            </section>
            <section class="row">
                <label for="user-points">Umiem</label>
                <input type="radio" name="user-points" class="punctationBtn" value="1" <?php if (
                    isset($punctation) &&
                    $punctation == "1" || $bool_correct == true
                )
                    echo "checked"; ?>><br>
            </section>
            <section class="row">
                <label for="user-points2">Bardzo dobrze umiem</label>
                <input type="radio" name="user-points" class="punctationBtn" value="2" <?php if (
                    isset($punctation) &&
                    $punctation == "2"
                )
                    echo "checked"; ?>><br>
            </section>
        </fieldset>
        <section id="form-bottom-section">
            <?php
            echo "<progress id='procentage-bar' value='${procentage_correct}' max='100'></progress>";
            echo "<p>${procentage_correct}% poprawnych odpowiedzi</p>";
            ?>
            <label for="reset-stats">Resetuj statystyki</label>
            <input type="hidden" name="reset-stats" value="0">
            <input type="checkbox" name="reset-stats" class="reset-stats-checkbox" value="1"><br>
            <input name="website" value="view-result" type="hidden">
        </section>
        <input type="submit" value="Zatwierdź"></input>
        <script src="basic-scripts.js"></script>
    </form>
</body>

</html>