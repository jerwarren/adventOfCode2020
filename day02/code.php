<?php
$passwordRules = file_get_contents("passwordRules.txt");

//           ranges          chars    password
$regexp = "/([0-9]*-[0-9]*) ([a-z]): (.*)/";

$ranges = [];
$characters = [];
$passwords = [];

$validPasswords = [];
$invalidPasswords = [];

// regex match all lines, splitting them into
preg_match_all($regexp, $passwordRules, $matches);


foreach ($matches[1] as $range){
    // replace any - with , so they can become valid regex ranges
    $ranges[] = str_replace("-", ",",$range);
}

foreach ($matches[2] as $character){
    $characters[] = $character;
}

foreach ($matches[3] as $password){
    $passwords[] = $password;
}

// loop through, constructing a new regex applying the range to the character
for ($i = 0; $i<count($ranges); $i++) {
    $matchRegexp = "/".$characters[$i]."{".$ranges[$i]."}/";
    //if the password matches the constructed regex, it's good. if not it's not.
    if (preg_match($matchRegexp,$passwords[$i])){
        $validPasswords[] = $passwords[$i];
    } else {
        $invalidPasswords[] = $passwords[$i];
    }

}
echo "valid passwords: ".count($validPasswords)."\n";
echo "invalid passwords: ".count($invalidPasswords)."\n";