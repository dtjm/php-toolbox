Phlaya - Microscopic PHP middleware and routing stack
======

*Requires PHP >= 5.3*

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

### Router\{get,post,put,delete}(string $resourcePattern, callback function([array $params]))
Handle the specified HTTP method matching the $resourcePattern to the URI called in `run()`.
If it matches, call the callback function.

### Router\run(string $uri)
Execute the entire routing stack. This will either result in one of your callbacks being run,
or it will return a 404 response.  The response is in the form of a **Phlaya** response:

    array(404, array(), NULL);

`vim: set ft=markdown:`