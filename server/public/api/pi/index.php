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
    $keys = ["id"];

    // Get all if no specific ID is requested
    if (empty($requestVars))
    {
        // Build the SQL request
        $sql = "select * from pi";
        $results = sql_request_all($db, $sql, NULL);

        // Return results
        echo json_encode($results);
    }
    else
    {
        // verify correct keys are there
        ensure_no_extra_keys($requestVars, $keys);
        found_all_keys($requestVars, $keys);

        // Parse ID from arguments
        $pi_id = $requestVars["id"];


        // If pi_id doesn't exist, throw an error and exit
        if (!pi_id_exists($db, $pi_id))
        {
            header("HTTP/1.1 400");

            $message = "Invalid id";
            $error = array("error_text" => $message);
            echo json_encode($error);
            exit();
        }

        // Build the request:
        $sql = "select * from pi where pi_id = ?";
        $results = sql_request_all($db, $sql, $pi_id);

        // return results
        echo json_encode($results);
    }
}
else
{
    // Handle any other types of requests
    header("HTTP/1.1 400");
    $message = "Request type not supported";
    $error = array("error_text" => $message);
    echo json_encode($error);
    exit();
}