<?php namespace Phlaya;

// Minimalistic PHP middleware framework
// --------------
// Each middleware returns an $env array which may contain:
// * response
//
// A response is an array of 3 items:
// [0] HTTP status code (either integer or full text "HTTP/1.0 404: Not found")
// [1] Array map of headers and header values
// [2] String body to return

// The framework
function run(array $middlewareArray) {

    // Initialize local state
    $env = array();

    // Run the stack
    foreach($middlewareArray as $m) {
        $env['response'] = call_user_func($m, $env);
    }

    // Render the headers
    header("HTTP/1.0 {$env['response'][0]}");
    foreach($env['response'][1] as $key => $val) {
        header("$key: $val");
    }

    // Render the body
    echo $env['response'][2];
}

// vim: set ft=php: