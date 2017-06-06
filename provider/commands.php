<?php
// DIC configuration
$container = $app->getContainer();

// GreetCommand
$container['greet'] = function ($c) {
    $cmd = new \App\Commands\GreetCommand($c);

    return $cmd;
};
?>