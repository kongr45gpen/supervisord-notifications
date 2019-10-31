<?php

abstract class Colour
{
    const White = 0;
    const Black = 1;
    const Blue = 2;
    const Green = 3;
    const Red = 4;
    const Brown = 5;
    const Purple = 6;
    const Orange = 7;
    const Yellow = 8;
    const LimeGreen = 9;
    const Turquise = 10;
    const Cyan = 11;
    const LightBlue = 12;
    const Pink = 13;
    const Grey = 14;
    const LightGrey = 15;
    const Transparent = -1;
}

abstract class ControlCode {
    const Bold            = 0x02;     /**< Bold */
    const Color           = 0x03;     /**< Color */
    const Italic          = 0x09;     /**< Italic */
    const StrikeThrough           = 0x13;     /**< Strike-Through */
    const Reset           = 0x0f;     /**< Reset */
    const Underline2       = 0x15;     /**< Underline */
    const Underline      = 0x1f;     /**< Underline */
    //const Underline      = 037;     /**< Underline */
    const Reverse         = 0x16;     /**< Reverse */
};

function ircColour($string, $foreground, $background=NULL) {
    if ($background === NULL)
        return ircControl(sprintf('%02d%s', $foreground, $string), ControlCode::Color);
    return ircControl(sprintf('%02d,%02d%s', $foreground, $background, $string), ControlCode::Color);
}

function ircControl($string, $control) {
    return chr($control) . $string . chr(ControlCode::Reset);
}


function sendIrcMessage($message,$channel='++alezakos') {
$channel =  str_replace ('#', '+', $channel);
$fields_string="";

// REPLACE THIS! Path to the supybot-github URL
// See https://github.com/kongr45gpen/supybot-github
$url = 'http://example.com:8093/typeYourPasswordHere/' . $channel;

$fields = array( );


//$url=urlencode($url);
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

$json = 'payload=' . urlencode(json_encode(["message"=>$message]));

//echo "$message\n";

/*********************
 color codes
 0 = black 1 = white 2 = blue 3 = green
 4 = red 5 = brown 6 = purple 7 = orange
 8 = yellow 9 = limegreen 10 = turquise
 11 = cyan 12 = lightblue 13 = pink
 14 = grey 15 = lightgrey
*********************/


//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_USERPWD, "???");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);


//close connection
curl_close($ch);

//echo "\n\n";
}
