PHP Toolbox - PHP web framework tools for PHP 5.3+
======

PHP Toolbox provides a set of microscopic libraries you can use 
when you are stuck with PHP (5.3+) and you want to whip up a quick web service.

At it's core is a [http://rack.rubyforge.org/](Rack)-inspired middleware stack which starts you off
with just a little bit of structure. On the request side, just use 
what PHP already provides (read: use the `$_SERVER` and `$_REQUEST` superglobals).

It also provides a [http://sinatrarb.org](Sinatra)-like routing engine in case you are 
into that sort of thing.

A few design goals:
- **Namespaced functions over static methods:** Why inherit from abstract classes when you have anonymous functions?
  Why put a controller in a class? Now that we have namespaces, just use namespaced functions.
- **Composition over inheritance:** Include the tools you need, and don't load what you don't need.
- **Minimalist, i.e. be like Arch Linux:** Start with a bare environment and add things as you need them.
- **Get out of the way:** Don't force any URL-rewriting style.                        
- **Idiomatic PHP:** Don't do anything that PHP has already done.                                        

Middleware stack usage
----------------------

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

    // Each function in the middleware stack must return a
    // Response array as defined below. They can communicate with
    // each other through the Env array which is passed from one
    // to the next by reference.
    Middleware\run(array(
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

### Middleware\run(array $middlewareFunctionNames)
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

Router usage
------------
See [https://github.com/dtjm/phlaya/blob/master/example.php](example.php).

Functions
---------

### Router\\{get,post,put,delete}(string $resourcePattern, callback function([array $params]))
Handle the specified HTTP method matching the $resourcePattern to the URI called in `run()`.
If it matches, call the callback function.

### Router\run(string $uri)
Execute the entire routing stack. This will either result in one of your callbacks being run,
or it will return a 500 response.  The response is in the form of a **Phlaya** response:

    array(500, array(), NULL);

`vim: set ft=markdown:`