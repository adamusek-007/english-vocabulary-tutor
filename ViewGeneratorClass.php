<?php
class ViewGenerator
{
    function setUnitsSelectView($connection, $unit)
    {
        $stmt = $connection->prepare("SELECT DISTINCT ${unit} FROM words ORDER BY ${unit} ASC;");
        $stmt->execute();
        echo "<option>Wszystkie</option>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option>". $row['unit'] . "</option>";
        }
    }
    function setUnitWordView($connection, $unit)
    {
        $query_generator = new QueryGenerator();
        $query = $query_generator->getWordViewQuery($unit);
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $table_structure = "<thead><th>Id</th><th>Unit</th><th>EN</th><th>PL</th><th>Podpowiedź</th><th>Edytuj</th></thead><tbody>";
        echo $table_structure;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>${row['id']}</td>";
            echo "<td>${row['subunit']}</td>";
            echo "<td>${row['english']}</td>";
            echo "<td>${row['polish']}</td>";
            echo "<td>${row['hint']}</td>";
            echo "<td><a><i id=${row['id']} onclick='setEditView(this)' class=\"fa-solid fa-pencil\" style=\"color: #ffffff;\"></i></a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
    }
    function setSubunitSelectView($connection, $unit)
    {
        $query_generator = new QueryGenerator();
        $query = $query_generator->getSubunitSelectViewQuery($unit);
        $stmt = $connection->query($query);
        echo "<option>Wszystkie</option>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        $stmt = $connection->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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