<?php

use App\Controller\RestCountriesController;

require_once __DIR__ . '/vendor/autoload.php';


$request = new RestCountriesController($argv);
$result  = $request->getResult();

print_r($result);

