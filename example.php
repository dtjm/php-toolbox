<?php
// Example Phlaya app
include "./phlaya.php";
include "./router.php";

// HELLO_WORLD - generates 3 lines of plain text
function hello_world($env) {
    return array(
        200,
        array("Content-type" => "text/plain"),
        "Hello world\nlorem ipsum dolor\nsit amet"
    );
}

function rest_app($env) {
    Router\get("/resource/:id/:subid", function($params) {
        return array(
            200,
            array("Content-type" => "text/plain"),
            "id = {$params['id']}, subid = {$params['subid']}"
        );
    });
    Router\get("/resource", function(){
        return array(
            200,
            array("Content-type" => "text/plain"),
            "This is a resource"
        );
    });

    Router\post("/resource", function(){
        return array(
            201,
            array("Content-type" => "text/plain"),
            "Created"
        );
    });

    Router\put("/resource/:id", function($params) {
        return array(
            201,
            array("Location" => "/resource/{$params['id']}"),
            NULL
        );
    });

    $parts = explode("index.php", $_SERVER['REQUEST_URI']);
    $resource = "";
    if(count($parts) == 2) {
        $resource = $parts[1];
    } else {
        $resource = "/";
    }

    return Router\run($resource);
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

// Show the $_SERVER superglobal
function show_server($env) {
    $response = $env['response'];
    $response[2] .= '<pre>' . print_r($_SERVER, true) . '</pre>';

    return $response;
}

// The entry point
Phlaya\run(array(
    'rest_app',
    'text_to_html',
    'bold_every_other',
    // 'show_server'
));

// vim: set ft=php:
