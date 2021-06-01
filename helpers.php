<?php

function detectRequestMethod()
{
    return $_SERVER['REQUEST_METHOD'];
}

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

function httpJsonResponse(string $header, array $obj)
{
    header($header);
    header('Content-Type: application/json');
    echo json_encode($obj);
}
