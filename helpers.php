<?php

/**
 * A collection of useful helper functions
 */

 /**
  * Detect request method by accessing global
  * $_SERVER variable
  * @return string
  */
function detectRequestMethod()
{
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * Retrieve global variable gived a request method
 * @param string
 * @return array
 */
function requestInput(string $method)
{
    $input = [];
    switch (strtoupper($method)) {
        case 'GET':
            $input = $_GET;
            break;
        case 'POST':
            $input = $_POST;
            break;
        case 'PUT':
            parse_str(file_get_contents("php://input"), $_PUT);
            $input = $_PUT;
            break;
        default:
            break;
    }
    return $input;
}

/**
 * Validate request input
 * @param array, string
 * @return array
 */
function validateRequestInput(array $requiredInput, string $method)
{
    $check = true;
    $inputs = requestInput($method);
    for ($i = 0; $i < count($requiredInput); $i++) {
        if (!array_key_exists($requiredInput[$i], $inputs)) {
            $check = false;
        }
    }
    return $check ? $inputs : false;
}

/**
 * Http header response, not allowed method
 * @param string
 */
function httpMethodNotAllowedResponse(string $msg)
{
    header("HTTP/1.0 405 Method Not Allowed");
    header("Content-Type: text/html; charset=UTF-8");
    echo $msg;
}

/**
 * Http json response
 * @param string, array
 */
function httpJsonResponse(string $header, array $obj)
{
    header($header);
    header('Content-Type: application/json');
    echo json_encode($obj);
}
