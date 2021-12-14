<?php
// Library of PHP functions & classes

/**
 * Simple sql request to specific DB.
 */
function sql_request($db, $sql, $sqlVar)
{
    // prepare the statement
    $statement = $db->prepare($sql);

    // Execute the query based in var type
    if (is_null($sqlVar))
    {
        $statement->execute();
    }
    elseif (is_array($sqlVar))
    {
        $statement->execute($sqlVar);
    }
    else
    {
        $statement->execute([$sqlVar]);
    }

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * SQL Request using FetchAll.
 */
function sql_request_all($db, $sql, $sqlVar)
{
        $statement = $db->prepare($sql);

        if (is_null($sqlVar))
        {
            $statement->execute();
        }
        elseif (is_array($sqlVar))
        {
            $statement->execute($sqlVar);
        }
        else
        {
            $statement->execute([$sqlVar]);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Verifies all keys are in array.
 */
function found_all_keys($inputs, $keys)
{
	$found_all = true;
	$msgs = array();

	foreach($keys as $key)
	{
		if(!array_key_exists($key, $inputs))
		{
			$found_all = false;

			array_push($msgs, "$key is missing");
		}
	}

	if(!$found_all)
	{
		$message = implode($msgs, ", ");

		$error = array("error_text" => $message);
		echo json_encode($error);
		exit();
	}
}

/**
 * Makes sure no extra keys are in array.
 */
function ensure_no_extra_keys($inputs, $keys)
{
	$msg = array();

	//check that there are no extra input parameters besides what is in keys
	foreach($inputs as $param => $value)
	{
		$param = trim($param);

		if(!in_array($param, $keys))
		{
			array_push($msg, "$param not a valid parameter");
		}
	}

	if(count($msg))
	{
		$message = implode($msg, ", ");
		$error = array("error_text" => $message);
		echo json_encode($error);
		exit();
	}
}

/**
 * Retrieves the highest count_id in counts.
 */
function get_highest_count_id($db)
{
    $sql = "SELECT MAX(count_id) FROM counts";

    $request = sql_request($db, $sql, NULL);

    return doubleval($request["max"]);
}


/**
 * Checks to see if customer's email already exists in db
 */
function pi_id_exists($db, $id)
{
    $unique = false;

    $sql = "SELECT * FROM pi WHERE pi_id = ?";

    $results = sql_request($db, $sql, $id);

    if (is_array($results))
    {
        if (sizeof($results) > 0)
        {
            $unique = true;
        }
    }

    return $unique;
}


/**
 * Checks if given id exists in table of given type.
 */
function id_exists_in_db($db, $type, $id)
{
    $contains = false;
    $idType = get_id_col_name_from_type($type);

    $sql = "SELECT * FROM $type WHERE $idType = ?";

    $results = sql_request($db, $sql, $id);

    if (is_array($results) && sizeof($results) > 0)
    {
        $contains = true;
    }

    return $contains;
}


/*
 * Returns the average for each pi as {id => avg}
*/
function average_with_offsets($db, $LIBRARY_OFFSET, $EHS_OFFSET, $CAFE_OFFSET, $DEN_OFFSET)
{
    $sql = 'select * from avg_counts order by source_pi_id';
    $result = sql_request_all($db, $sql, NULL);

    $r = [];

    // Watchout- result is off by an index of 1
    $r["1"] = $result[0]['avg'] - $LIBRARY_OFFSET;
    $r["2"] = $result[1]['avg'] - $EHS_OFFSET;
    $r["3"] = $result[2]['avg'] - $CAFE_OFFSET;
    $r["4"] = $result[3]['avg'] - $DEN_OFFSET;

    return $r;
}


/**
 * Connect to pgsql db.
 */
function connect_to_db()
{
	$db = new PDO("pgsql:dbname=NAME host=localhost password=PASSWORD user=USERNAME");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
}


/**
 *  Returns the String value of the live attribute passed as $val.
 */
function get_string_value($val, $live_library, $live_ehs, $live_cafe, $live_den) {
    if ($val === $live_library){
        return "Library";
    }
    else if ($val === $live_ehs){
        return "EHS";
    }
    else if ($val === $live_cafe){
        return "Cafe";
    }
    else if ($val === $live_den){
        return "Den";
    }
}


/**
 * Returns the hourly average for today for all campus
 */
function get_chart_data($db)
{
    $sql = 'select all_campus_avg from all_campus_today_avg';
    $result = sql_request_all($db, $sql, null);

    $r = [];

    foreach ($result as $res) {
        array_push($r, $res['all_campus_avg']);
    }

    return $r;
}

/**
 * Returns the hourly average for today for all campus
 */
function get_yesterday_chart_data($db)
{
    $sql = 'select all_campus_avg from all_campus_yesterday_avg';
    $result = sql_request_all($db, $sql, null);

    $r = [];

    foreach ($result as $res) {
        array_push($r, $res['all_campus_avg']);
    }

    return $r;
}


?>