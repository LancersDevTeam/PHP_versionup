<?php

$githubId   = $argv[1];
$json       = $argv[2];
$data       = json_decode($json, true);
printf(
    '[picon:%s]',
    $data[$githubId]['chatwork']['userId']
);
