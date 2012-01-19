<?php namespace Phlaya;

// Minimalistic PHP middleware framework
// --------------
//
// Each middleware returns an $env array which may contain:
// - response
//
// A response is an array of 3 items:
// - HTTP status code (either integer or full text "HTTP/1.0 404: Not found")
// - Array map of headers and header values
// - String body to return

// HELLO_WORLD - generates 3 lines of plain text
function hello_world($env) {
    return array(200, array("Content-type" => "text/plain"), "Hello world\nlorem ipsum dolor\nsit amet");
}

// TEXT_TO_HTML - converts NL to <br/>
function text_to_html($env) {
    $response = $env['response'];
    $body = $response[2];

    $body = str_replace("\n", "<br/>\n", $body);

    $response[2] = $body;
    $response[1]['Content-type'] = "text/html";

    return $response;
}

// BOLD_EVERY_OTHER - emboldens every other word
function bold_every_other($env) {
    $response = $env['response'];
    $body = $response[2];

    $tokens = explode(" ", $body);
    $i = 0;
    foreach($tokens as &$t) {
        if($i % 2 == 0) {
            $t = "<strong>$t</strong>";
        }
        $i++;
    }
    $response[2] = implode(" ", $tokens);

    return $response;
}

function show_server($env) {
    $response = $env['response'];
    $response[2] .= '<pre>' . print_r($_SERVER, true) . '</pre>';

    return $response;
}

// The framework
function run($middleware) {
    $env = array();
    foreach($middleware as $m) {
        $env['response'] = call_user_func($m, $env);
    }

    header("HTTP/1.0 {$env['response'][0]}");
    foreach($env['response'][1] as $key => $val) {
        header("$key: $val");
    }

    echo $env['response'][2];
}

// The entry point
run(array(
    'hello_world',
    'text_to_html',
    'bold_every_other',
    'show_server'
));