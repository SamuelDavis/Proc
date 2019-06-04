# SamuelDavis \ Proc

### Usage

```php
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
```

### output

```
PING 127.0.0.1 (127.0.0.1): 56 data bytes
64 bytes from 127.0.0.1: icmp_seq=0 ttl=64 time=0.055 ms
64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.037 ms
64 bytes from 127.0.0.1: icmp_seq=2 ttl=64 time=0.037 ms

--- 127.0.0.1 ping statistics ---
3 packets transmitted, 3 packets received, 0.0% packet loss
round-trip min/avg/max/stddev = 0.037/0.043/0.055/0.008 ms
PING 127.0.0.1 (127.0.0.1): 56 data bytes
64 bytes from 127.0.0.1: icmp_seq=0 ttl=64 time=0.050 ms
64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.064 ms
64 bytes from 127.0.0.1: icmp_seq=2 ttl=64 time=0.080 ms

--- 127.0.0.1 ping statistics ---
3 packets transmitted, 3 packets received, 0.0% packet loss
round-trip min/avg/max/stddev = 0.050/0.065/0.080/0.012 ms
```
