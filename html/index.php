<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\ApiController;

$controller = new ApiController();

$controller->get();
