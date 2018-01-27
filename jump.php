<?php
declare(strict_types=1);

// error_reporting(0);

spl_autoload_register(function ($class) {
    require_once __DIR__ . "/src/{$class}" . '.php';
});

for ($i = 1; ; $i++) {
    $start = microtime(true);

    (new Game())->run();

    echo "第{$i}跳: ", intval((microtime(true) - $start) * 1000), " ms \n";

    usleep(1500000);
}
