<?php
class QueryGenerator
{
    function getUnitSubunitQueryPart($u_sel, $unit_subunit)
    {
        if ($u_sel == "Wszystkie") {
            return "`{$unit_subunit}` = `{$unit_subunit}`";
        } else {
            return "`{$unit_subunit}` = {$u_sel}";
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
            $query = "SELECT `id`, `english`, `polish`, `hint`, `subunit` FROM `words` WHERE `unit` = {$unit} ORDER BY `subunit`, `english`;";
            return $query;
        }
    }
    function getSubunitSelectViewQuery($unit)
    {
        if ($unit == "Wszystkie") {
            $query = "SELECT DISTINCT `subunit` FROM `words` ORDER BY `subunit` ASC;";
            return $query;
        } else {
            $query = "SELECT DISTINCT `subunit` FROM `words` WHERE `unit` = {$unit} ORDER BY `subunit` ASC;";
            return $query;
        }
    }
}