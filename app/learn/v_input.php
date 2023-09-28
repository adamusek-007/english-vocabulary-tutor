<?php
include("../classes.php");
session_start();
$connector = new Connector();
$query_generator = new QueryGenerator();
$connection = $connector->getConnectionToDatabase();

if ($_POST["website"] == "mode-selection") {
    $u_sel_unit = $_SESSION["u-sel-unit"] = $_POST["u-sel-unit"];
    $u_sel_subunit = $_SESSION["u-sel-subunit"] = $_POST["u-sel-subunit"];
    $u_sel_generating_mode = $_SESSION["u-sel-generating-mode"] = $_POST["u-sel-generating-mode"];
    $u_sel_answering_mode = $_SESSION["u-sel-answering-mode"] = $_POST["u-sel-answering-mode"];
    if (isset($_POST["u-sel-hints-mode"])) {
        $_SESSION["hints"] = "on";
    } else {
        $_SESSION["hints"] = "off";
    }
} else if ($_POST["website"] == "view-result") {
    checkDataFromPrevious($connection);
    $u_sel_unit = $_SESSION["u-sel-unit"];
    $u_sel_subunit = $_SESSION["u-sel-subunit"];
    $u_sel_generating_mode = $_SESSION["u-sel-generating-mode"];
    $u_sel_answering_mode = $_SESSION["u-sel-answering-mode"];
}

$correct_result = getResultFromBase($connection, $u_sel_unit, $u_sel_subunit, $u_sel_generating_mode, $query_generator);

if ($correct_result->rowCount() == 0) {
    $correct_result = $connection->query("SELECT * FROM `words` WHERE 1;");
    $result_avability = false;
} else {
    $result_avability = true;
}

$_SESSION["row"] = $correct_row = getCorrectRow($connection, $correct_result);
$correct_english_word = $correct_row["english"];
$correct_polish_word = $correct_row["polish"];

if ($u_sel_answering_mode == "reply-choose") {
    $correct_row_id = $correct_row["id"];
    $random_query = "SELECT * FROM `words` WHERE `id` != {$correct_row_id};";
    $wrong_answers_array = getArrayOfWrongAnswers($connection, $random_query);
    $answers_array = array($correct_english_word, $wrong_answers_array[0], $wrong_answers_array[1], $wrong_answers_array[2]);
} else {
    $answers_array = array();
}
//! Functions 
function checkDataFromPrevious($connection)
{
    if (isset($_SESSION["row"])) {
        $previous_row = $_SESSION["row"];
        $p_row_id = $previous_row["id"];
        if (isset($_POST["user-points"])) {
            $p_points = $_POST["user-points"];
            $query = "UPDATE `words` SET `user_rating` = {$p_points} WHERE `id`= {$p_row_id};";
            $connection->query($query);

        }
        if (isset($_POST["reset-stats"])) {
            if ($_POST["reset-stats"] == "1") {
                $query = "UPDATE `words` SET `user_rating` = 0, `views` = 0, `correct_answers` = 0 WHERE id={$p_row_id};";
                $connection->query($query);
            }
        }
    }
}
//! ALWAYS
function getResultFromBase($connection, $u_sel_unit, $u_sel_subunit, $u_sel_generating_mode, $query_generator)
{

    $q_part_unit_selection = $query_generator->getUnitSubunitQueryPart($u_sel_unit, "unit");
    $q_part_subunit_selection = $query_generator->getUnitSubunitQueryPart($u_sel_subunit, "subunit");
    $q_part_mode_selection = $query_generator->getGeneratingModeQueryPart($u_sel_generating_mode);
    $query = "SELECT * FROM `words` WHERE {$q_part_unit_selection} AND {$q_part_subunit_selection} AND {$q_part_mode_selection};";
    $correct_result = $connection->query($query);
    return $correct_result;

}
function getCorrectRow($connection, $correct_result)
{
    $row_id_array = getRowIdsArray($correct_result);
    $random_array_index = array_rand($row_id_array);
    $row_id_from_array = $row_id_array[$random_array_index];
    $result_row = getRowInternal($connection, $row_id_from_array);
    return $result_row;
}
function getRowIdsArray($result)
{
    $array_of_ids = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($array_of_ids, $row["id"]);
    }
    return $array_of_ids;
}
function getRowInternal($connection, $random_row_id)
{
    $q_select_row = "SELECT * FROM `words` WHERE `id`=$random_row_id;";
    $result = $connection->query($q_select_row);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row;
}
//! END ALWAYS
function getArrayOfWrongAnswers($connection, $query)
{
    $words_array = array();
    $result = $connection->query($query);
    $row_id_array = getRowIdsArray($result);
    $wrong_answers_ids_array = array_rand($row_id_array, 3);
    $w1 = $row_id_array[$wrong_answers_ids_array[0]];
    $w2 = $row_id_array[$wrong_answers_ids_array[1]];
    $w3 = $row_id_array[$wrong_answers_ids_array[2]];
    $q = "SELECT `english` FROM `words` WHERE `id` = {$w1} OR id = {$w2} OR id = {$w3};";
    $result = $connection->query($q);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($words_array, $row["english"]);
    }
    return $words_array;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wprowadź odpowiedź</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/mainstyle.css">
    <link rel="stylesheet" href="../stylesheets/v_input.css">
</head>

<body>
    <div>

    </div>
    <section>
        <a href="../index.html"><img src="images/home.png" id="go-home"></a>
        <form action="v_result.php" method="post" class="first-site-form">
            <?php
            if ($result_avability != true) {
                echo "<label>Słówka z podanych warunków są nie dostępne</label>";
            }
            ?>
            <label for="user-answer">
                <?php echo 'Przetłumacz: "' . $correct_polish_word . '"'; ?>
            </label>
            <?php
            $view_generator = new ViewGenerator();
            $view_generator->setAnsweringModeView($u_sel_answering_mode, $answers_array);
            if ($_SESSION["hints"] == "on") {
                echo "<a id='hint' onclick='showHint()'><img src='images/hintbulb.png' id='hintbulb'></a><br>";
            }
            ?>
            <p class="display-none" id="podpowiedz">
                <?php
                if (isset($correct_row["hint"]) || $correct_row["hint"] != "") {
                    echo $correct_row['hint'];
                } else {
                    substr($correct_english_word, 0, 1);
                }
                ?>
            </p>
        </form>
    </section>
    <script src="basic-scripts.js"></script>
</body>

</html>