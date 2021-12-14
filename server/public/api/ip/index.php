<?php

include_once("../lib.php");
include_once("../rest.php");
header("HTTP/1.1 200");


$request = new RestRequest();
$db = connect_to_db();
$requestVars = $request->getRequestVariables();
$errors = [];
date_default_timezone_set('America/Los_Angeles');

if($request->isPost())
{
    $keys = ["source_pi_id", "ip"];

    found_all_keys($requestVars, $keys);
    ensure_no_extra_keys($requestVars, $keys);

    $source_pi_id = $requestVars["source_pi_id"];
    $ip = $requestVars["ip"];

    $queryVars = [$ip, $source_pi_id];

    $sql = "update pi_ip set ip = ? where source_pi_id = ?";
    $results = sql_request_all($db, $sql, $queryVars);

    echo json_encode($results);
}
