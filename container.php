<?php

use DI\ContainerBuilder;

$definitions = require __DIR__ . '/definitions.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions($definitions);

return $containerBuilder->build();