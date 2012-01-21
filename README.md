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

Functions
---------

### Phlaya\run(array $middlewareFunctionNames)
The `run()` function takes an array of strings which are the names of
middleware functions. Each function should take an $env array map by
reference and return a response.

Router
======
The Router library gives you a Sinatra-like routing controller. Routes are defined using the 
`get()`, `post()`, `put()`, and `delete()` functions. Then the route is run using the `run()`
function.  Routes are matched in the order they are defined -- first one wins!

Patterns can be literal like `/resource` or they can be contain named parameters like 
`/resource/:id`.  In this case, the callback would supply a `$params` array map containing the `id`
key.

Functions
---------

### Router\get(string $resourcePattern, callback function([array $params]))
### Router\post(string $resourcePattern, callback function([array $params]))
### Router\put(string $resourcePattern, callback function([array $params]))
### Router\delete(string $resourcePattern, callback function([array $params]))
### Router\run(string $uri)


`vim: set ft=markdown:`