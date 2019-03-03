<?php

$githubId = $argv[1];
$json     = $argv[2];
$data     = json_decode($json, true);

foreach ($data as $user) {
    if ($user["github"] === $githubId) {
        print "<@" . $user["slack"] .">";
        exit;
    }
}
