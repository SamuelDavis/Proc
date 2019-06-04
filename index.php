<?php

use SamuelDavis\Proc\Proc;

require __DIR__ . '/vendor/autoload.php';

$ping = new Proc('ping 127.0.0.1 -c 3');
call_user_func($ping);

while ($ping->isRunning()) {
    // wait...
}

// and print it all at once.
echo implode(PHP_EOL, iterator_to_array($ping)) . PHP_EOL;

// or, restart it...
call_user_func($ping);

// and print it in real time
foreach ($ping as $out) {
    echo $out . PHP_EOL;
}
