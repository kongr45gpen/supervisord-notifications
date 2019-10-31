<?php

/**
 * The file run by supervisorctl that is responsible for handling all events.
 * This is the entry point of the application
 */

set_time_limit(0);

require 'mmLink.php';

$reportObvious = false; // Don't report RUNNING or STOPPED 
$reportAnnoying = false; // Don't report STARTING or STOPPING

$cache = [];
$time  = [];

while (true) {
    echo "READY\n";

    $line = fgets(STDIN);
    if (false === $line) {
        break;
    }

    $info = process(trim($line));
    $length = (int) $info['len'];
    $data = ($length > 0) ? process(trim(fread(STDIN, $length))) : [];
    $state = explode('_', $info['eventname'])[2];

    $report = true;
    if ($data['processname'] === 'IRC') {
        $report = false;
    } elseif ($state !== 'RUNNING' && substr($state, -3) === 'ING' && !$reportAnnoying) {
        $report = false;
    } elseif (($state === 'RUNNING' || $state === 'STOPPED') && !$reportObvious) {
        $report = false;
    }

    if ($report) {
        sendIrcMessage(sprintf("%s[%s]: %s",
            $data['groupname'],
            ircColour($data['processname'], Colour::Cyan),
//            strtolower($data['from_state']),
            stylise(ucfirst(strtolower($state)))
        ), '++chanel'); // Replace '++channel' with the channel receiving the message
    }

    echo "RESULT 2\nOK";
}

function process($data) {
    $data = explode(' ', $data);
    $values = [];

    foreach ($data as $item) {
        $item = explode(':', $item);
        $values[$item[0]] = $item[1];
    }

    return $values;
}

function stylise($state) {
    switch (strtolower($state)) {
        case 'stopped':
        case 'stopping':
            return ircColour($state, Colour::LightBlue);
        case 'starting':
        case 'running':
            return ircColour($state, Colour::Green);
        case 'backoff':
        case 'exited':
        case 'fatal':
        case 'unknown':
        default:
            return ircColour(ircControl($state, ControlCode::Bold), Colour::Red);
    }

}

exit(0);
