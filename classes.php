<?php
class ViewGenerator extends QueryGenerator
{
    function setUnitsSelectView($connection, $unit)
    {
        $query = "SELECT DISTINCT ${unit} FROM words ORDER BY ${unit} ASC;";
        $result = mysqli_query($connection, $query);
        echo "<option>Wszystkie</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option> ${row['unit']} </option>";
        }
    }
    function setUnitWordView($connection, $unit)
    {
        $query_generator = new QueryGenerator();
        $query = $query_generator->getWordViewQuery($unit);
        $result = mysqli_query($connection, $query);
        $table_structure = "<thead><th>Id</th><th>Unit</th><th>EN</th><th>PL</th><th>Podpowiedź</th><th>Edytuj</th></thead><tbody>";
        echo $table_structure;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>${row['id']}</td>";
            echo "<td>${row['subunit']}</td>";
            echo "<td>${row['english']}</td>";
            echo "<td>${row['polish']}</td>";
            echo "<td>${row['hint']}</td>";
            echo "<td><a><img id=${row['id']} onclick='setEditView(this)' src='images/pencil.png' ></img></a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
    }
    function setSubunitSelectView($connection, $unit)
    {
        $query_generator = new QueryGenerator();
        $query = $query_generator->getSubunitSelectViewQuery($unit);
        $result = mysqli_query($connection, $query);
        echo "<option>Wszystkie</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option>${row['subunit']}</option>";
        }

    }
    function setWordCount($connection, $unit, $subunit, $mode)
    {
        $query_generator = new QueryGenerator();
        $q_p_unit = $query_generator->getUnitSubunitQueryPart($unit, "unit");
        $q_p_subunit = $query_generator->getUnitSubunitQueryPart($subunit, "subunit");
        $q_p_mode = $query_generator->getGeneratingModeQueryPart($mode);
        $query = "SELECT COUNT(`id`) AS 'liczba' FROM `words` WHERE ${q_p_unit} AND ${q_p_subunit} AND ${q_p_mode};";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "${row['liczba']}";
        }

    }
    function setAnsweringModeView($u_sel_answering_mode, $answers_array)
    {
        if ($u_sel_answering_mode == "reply-choose") {
            echo "<fieldset>";
            echo "<legend>Wybierz odpowiedź</legend>";
            foreach ($answers_array as $a) {
                $random_array_id = array_rand($answers_array, 1);
                $value = $answers_array[$random_array_id];
                unset($answers_array[$random_array_id]);
                echo '<button value="' . $value . '" name="user-answer">' . $value . "</button><br>";
            }
            echo "</fieldset>";
        } else {
            echo "<input type='text' lang='en' name='user-answer' height='48' autocomplete='off' autofocus autocapitalize='off'><br>";
            echo "<input type='submit' value='Zatwierdź'><br>";
        }
    }
}
class QueryGenerator
{
    function getUnitSubunitQueryPart($u_sel, $unit_subunit)
    {
        if ($u_sel == "Wszystkie") {
            return "`${unit_subunit}` = `${unit_subunit}`";
        } else {
            return "`${unit_subunit}` = ${u_sel}";
        }
    }
    function getGeneratingModeQueryPart($generating_mode)
    {
        if ($generating_mode == "worst-procentage") {
            return "`correct_answers`/`views` < 0.9";
        } else if ($generating_mode == "dont-know") {
            return "`user_rating` = 0";
        } else if ($generating_mode == "know") {
            return "`user_rating` = 1";
        } else if ($generating_mode == "know-well") {
            return "`user_rating` = 2";
        } else if ($generating_mode == "no-answer") {
            return "`views` = 0";
        } else if ($generating_mode == "optimal") {
            return "(`date_time_to_repeat` < now() OR `date_time_to_repeat` IS NULL)";
        } else {
            return "`user_rating` = `user_rating`";
        }
    }
    function getWordViewQuery($unit)
    {
        if ($unit == "Wszystkie") {
            $query = "SELECT `id`, `english`, `polish`, `hint`, `subunit` FROM `words` WHERE `unit` = `unit` ORDER BY `subunit`, `english`;";
            return $query;
        } else {
            $query = "SELECT `id`, `english`, `polish`, `hint`, `subunit` FROM `words` WHERE `unit` = ${unit} ORDER BY `subunit`, `english`;";
            return $query;
        }
    }
    function getSubunitSelectViewQuery($unit)
    {
        if ($unit == "Wszystkie") {
            $query = "SELECT DISTINCT `subunit` FROM `words` ORDER BY `subunit` ASC;";
            return $query;
        } else {
            $query = "SELECT DISTINCT `subunit` FROM `words` WHERE `unit` = ${unit} ORDER BY `subunit` ASC;";
            return $query;
        }
    }
}
class Connector
{
    function getConnectionToDatabase()
    {
        $mysql_server_address = "127.0.0.1";
        $server_port = 3306;
        $mysql_user_name = "root";
        $mysql_password = "";
        $database_name = "slowka";

        $connection = mysqli_connect($mysql_server_address, $mysql_user_name, $mysql_password, $database_name, $server_port);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $connection;
        }
    }
}
?>