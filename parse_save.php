<?php
// Create Database, Need Setup
$db = new SQLite3('parse_save');
$db->exec('DROP TABLE IF EXISTS people');
$db->exec('CREATE TABLE people (full_name varchar(255), email varchar (255), phone varchar(255), address varchar(255))');

 // Open File
 // Change filename location!!
$filename = './textparse_exercise.txt';
$myfile = fopen( $filename, 'r');
$contents = fread($myfile, filesize($filename));
fclose($myfile);

$array = explode( "\n", $contents);
$array = array_filter($array, function($value) { return $value !== ''; });

foreach( $array as $key => $value) {
  $string = trim($value);
  $name = '';
  $email = '';
  $phone = '';
  $address = '';

  //  Email
  $email = array_filter(filter_var_array(explode(' ', $string), FILTER_VALIDATE_EMAIL));
  $email = reset($email);

  $string = trim(str_replace( $email, '', $string));

  //  Phone
  $phone_regex = "/[(]*\d{3}[)]*\s*[.\-\s]*\d{3}[.\-\s]*\d{4}/";
  preg_match( $phone_regex, $string, $phone_matches);
  $phone = $phone_matches[0];

  $string = trim(str_replace( $phone, '', $string));

  $name_regex = "/(^([a-z]([-']?[a-z]+)*(\s+[a-z]([-']?[a-z]+)*)(\s+[a-z]([-']?[a-z]+)*)?+)|([a-z]([-']?[a-z]+)*(\s+[a-z]([-']?[a-z]+)*)( [a-z]([-']?[a-z]+)*)?+)$)/i";
  $match = preg_match_all( $name_regex, $string, $name_matches);

  if ( $match == 1) {
    $name = $name_matches[0][0];
  } else {
    foreach ($name_matches[0] as $name_match) {
      $avoid_list = ['to'];
      $counter = 0;

      foreach ( $avoid_list as $avoid) {
        if ( strpos( $name_match, $avoid) !== false) {
          $counter++;
          break;
        } else {
          continue;
        }
      }

      if ( $counter == 0 ) {
        $name = $name_match;
      }
    }
  }

  //  Address
  $address = trim(str_replace( $name, '', $string));

  // var_dump(['email' => $email, 'name' => $name, 'phone' => $phone, 'address' => $address]);

  $db->exec("INSERT INTO people (full_name, email, phone, address) VALUES ($name , $email, $phone, $address)");

}
?>
