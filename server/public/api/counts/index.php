<?php

include_once("../lib.php");
include_once("../rest.php");
header("HTTP/1.1 200");


$request = new RestRequest();
$db = connect_to_db();
$requestVars = $request->getRequestVariables();
$errors = [];
date_default_timezone_set('America/Los_Angeles');

if ($request->isGet())
{
    // Get by pi_id
    if (array_key_exists("pi_id", $requestVars))
    {
        // Get by Pi_id
        $pi_id = $requestVars["pi_id"];

        // If pi_id doesn't exist, throw an error and exit
        if (!pi_id_exists($db, $pi_id))
        {
            header("HTTP/1.1 400");

            $message = "Invalid pi_id";
            $error = array("error_text" => $message);
            echo json_encode($error);
            exit();
        }

        $sql = "select * from counts where source_pi_id = ?";
        $results = sql_request_all($db, $sql, $pi_id);
        echo json_encode($results);
    }

    elseif (array_key_exists("location", $requestVars))
    {
        // Get by location
        $location = $requestVars["location"];
        $sql = "select * from counts join pi on source_pi_id = pi_id where pi_location = ?";
        $results = sql_request_all($db, $sql, $location);
        echo json_encode($results);
    }
    else
    {
        $sql = "select * from counts";
        $results = sql_request_all($db, $sql, NULL);
        echo json_encode($results);
    }
}

elseif($request->isPost())
{
    $keys = ["source_pi_id", "device_count"];

    found_all_keys($requestVars, $keys);
    ensure_no_extra_keys($requestVars, $keys);

    $source_pi_id = $requestVars["source_pi_id"];
    $device_count = $requestVars["device_count"];

    $newCountId = get_highest_count_id($db) + 1;
    $date_time = date("Y-m-d H:i:s");

    $queryVars = [$newCountId, $source_pi_id, $device_count, $date_time];

    $sql = "insert into counts values (?, ?, ?, ?)";
    $results = sql_request_all($db, $sql, $queryVars);

    echo json_encode($results);
}


