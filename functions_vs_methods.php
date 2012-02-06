<?php
// Benchmark the performance difference between static methods, instance 
// methods, and plain functions.

// CONFIGURATION
// -------------
$ITERATIONS = 1000000;

class MyClass {
    // Static function
    static function go() {}

    // Instance method
    function instance() {}
}

// Plain function
function go() {}


// BENCHMARKS
// ----------
// Benchmark functions
$start = microtime(TRUE);
for($i = 0; $i < $ITERATIONS; $i++) {
    go();
}
$elapsed = (microtime(TRUE) - $start);
echo "Function             $elapsed s\n";

// Benchmark static class methods
$start = microtime(TRUE);
for($i = 0; $i < $ITERATIONS; $i++) {
    MyClass::go();
}
$elapsed = (microtime(TRUE) - $start);
echo "Static class method  $elapsed s\n";

// Benchmark instance methods
$start = microtime(TRUE);
for($i = 0; $i < $ITERATIONS; $i++) {
    $obj = new MyClass;
    $obj->instance();
}
$elapsed = (microtime(TRUE) - $start);
echo "Instance method      $elapsed s\n";

