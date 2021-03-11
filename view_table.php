<?php

/**
 * Payir table implementation.
 * @author  Rabist
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class view_table extends table_sql
{

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid)
    {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('transid', 'factornumber', 'amount', 'description', 'cardnumber', 'timeupdated');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(get_string('buyid', 'enrol_payir'), get_string('factornumber', 'enrol_payir'), get_string('amounttoman', 'enrol_payir'), get_string('description'), get_string('cardnumber', 'enrol_payir'), get_string('date'));
        $this->define_headers($headers);
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * 
     */
    function col_description($values)
    {
        if ($this->is_downloading()) {
            return $values->description;
        } else {
            $d = explode('-', $values->description);
            return "<a href='$CFG->wwwroot/course/view.php?id=$values->courseid'>$d[0]</a>-<a href='$CFG->wwwroot/user/profile.php?id=$values->userid'>$d[1]</a>";
        }
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * 
     */
    function col_amount($values)
    {
        return number_format($values->amount);
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * 
     */
    function col_cardnumber($values)
    {
        if ($this->is_downloading()) {
            return $values->cardnumber;
        } else {
            return "<span dir='ltr'>$values->cardnumber</span>";
        }
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * 
     */
    function col_timeupdated($values)
    {
        return userdate($values->timeupdated, '%H:%M - %Y/%m/%d');
    }
}
