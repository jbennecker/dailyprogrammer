#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use App\DecodeCommand;
use App\EncodeCommand;
use App\Fhg;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new DecodeCommand());
$application->add(new EncodeCommand());
$application->add(new Fhg());
$application->run();
