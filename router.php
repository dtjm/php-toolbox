<?php namespace Router;

// Sinatra-like router that can handle static resource names//
// as well as do some pattern matching:
// - /resource
// - /resource/:id

// USAGE
// -----
// Router\get('/resource', function() {
//   echo "Matched the /resource!";
// }
//
// Router\get('/resource/:id', function($params) {
//   print_r($params);
// }
//
// Router\run($_SERVER['REQUEST_URI'];);

$routes = array(
            'GET' => array(),
            'POST' => array(),
            'PUT' => array(),
            'DELETE' => array()
        );

function addRoute($method, $pattern, $callback) {
    global $routes;
        $routes[$method][$pattern] = $callback;
}

function get($pattern, $callback) {
    addRoute("GET", $pattern, $callback);
}

function post($pattern, $callback) {
    addRoute("POST", $pattern, $callback);
}

function put($pattern, $callback) {
    addRoute("PUT", $pattern, $callback);
}

function run($resource) {
    global $routes;

    // Get HTTP method
    $method = $_SERVER['REQUEST_METHOD'];

    // Find matching pattern
    $patterns = $routes[$method];

    foreach($patterns as $pattern => $callback) {
        $matches = array();

        // Pattern has a matcher in it
        if(preg_match_all('(:[^/]+)', $pattern, $matches) !== 0) {
            $paramNames = $matches[0];
            $regex = "@^$pattern@";

            // Replace the named parameters with regular expressions and
            // store the parameter names without the colon prefix
            foreach($paramNames as &$param) {
                $regex = str_replace($param, "([^/]+)", $regex);
                $param = substr($param, 1);
            }

            $params = array();

            // Pull the values out of the resource URI and put them into the 
            // `params` array
            if(preg_match_all($regex, $resource, $matches) !== 0) {

                // First element of `$matches` has the entire string, so 
                // slice it off
                $values = array_slice($matches, 1);

                // Populate the params array with values from the URI
                for($i = 0; $i < count($paramNames); $i++) {
                    $params[$paramNames[$i]] = $values[$i][0];
                }

                // Call the callback with 
                return $callback($params);
            }
        }

        // Plain string match at the base of the resource
        if($resource === $pattern) {
            return $callback();
        }
    }

    return array(
        500,
        array(),
        NULL
    );
}
