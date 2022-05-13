<?php defined("BASE_PATH") OR die("<h1>You do not have access to this section</h1>");

try {
    $dotenv->required("DATABASE_DSN") -> notEmpty();
    $dotenv->required("BASE_URL") -> notEmpty(); # http://example.com 
    $dotenv->required("MOD") -> allowedValues(['dev', 'pro']); # pro = product & dev = develop
} catch (Dotenv\Exception\ValidationException $dotEnvError) {
    dd("error {$dotEnvError->getCode()}: 
              {$dotEnvError->getMessage()} 
              in line {$dotEnvError->getLine()}
              at {$dotEnvError->getFile()}");
}