Phlaya - Microscopic PHP middleware and routing stack
======

How to use the middleware stack:

    function hello_world(array &$env) {
        // Do something, return a response
        return array(
            200,
            array("Content-type" => "text/plain"),
            "Hello world!"
        );
    }

    function text_to_html(array &$env) {
        $response = $env['response'];
        $body = $response[2];

        $body = str_replace("\n", "<br/>\n", $body);

        $response[2] = $body;
        $response[1]['Content-type'] = "text/html";

        return $response;
    }

    Phlaya\run(array(
        'rest_app',
        'text_to_html'
    ));

Types
-----

### Response
An array containing 3 items:
1. HTTP response code (int)
2. array map of response headers
3. A string response body

### Env
The $env variable is an array map passed to each middleware function so it has some
context about what is going on. It may contain:
- `response`

### Middleware function
The `run()` function takes an array of strings which are the names of
middleware functions. Each function should take an $env array map by
reference and return a response.

`vim: set ft=markdown:`
