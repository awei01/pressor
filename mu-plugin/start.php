<?php

if (!function_exists('app')) throw new RuntimeException('Laravel framework is not installed or loaded');

$app = app();
if (!isset($app['pressor'])) throw new RuntimeException('Pressor framework is not installed or its service provider is not registered');

$app['pressor']->boot();

