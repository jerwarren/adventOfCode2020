<?php
$passwordRules = file_get_contents("passwordRules.txt");

//           min       max     chars    password
$regexp = "/([0-9]*)-([0-9]*) ([a-z]): (.*)/";

$minRanges = [];
$maxRanges = [];
$characters = [];
$passwords = [];

$validPasswords = [];
$invalidPasswords = [];

// regex match all lines, splitting them into
preg_match_all($regexp, $passwordRules, $matches);


foreach ($matches[1] as $minRange){
    // replace any - with , so they can become valid regex ranges
    $minRanges[] = str_replace("-", ",",$minRange);
}

foreach ($matches[2] as $maxRange){
    // replace any - with , so they can become valid regex ranges
    $maxRanges[] = str_replace("-", ",",$maxRange);
}

foreach ($matches[3] as $character){
    $characters[] = $character;
}

foreach ($matches[4] as $password){
    $passwords[] = $password;
}
// loop through, constructing a new regex applying the range to the character
for ($i = 0; $i<count($minRanges); $i++) {
    $matchRegexp = "/".$characters[$i]."{".$minRanges[$i].",".$maxRanges[$i]."}/";
    //if the password matches the constructed regex, it's good. if not it's not.
    
    //we care about total number of characters, not characters in a row, so let's strip out all the ones we don't care about
    $cleanPassword = preg_replace("/[^".$characters[$i]."]/", "", $passwords[$i]);
    
    if (preg_match($matchRegexp, $cleanPassword) && (strlen($cleanPassword) <= $maxRanges[$i])){
        echo "MATCH: $matchRegexp ".$passwords[$i].", $cleanPassword\n";        
        $validPasswords[] = $passwords[$i];
    } else {
        echo "no match: $matchRegexp ".$passwords[$i].", $cleanPassword\n";
        $invalidPasswords[] = $passwords[$i];
    }
}
echo "\n\n\nvalid passwords: ".count($validPasswords)."\n";
echo "invalid passwords: ".count($invalidPasswords)."\n";