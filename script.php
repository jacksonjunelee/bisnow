<?php

function checkNumber($number) {
  if ( $number > 0 && $number <= 1000  ) {
    if( $number % 3 == 0 && $number % 5 == 0 ) {
      return json_encode(['text' => 'Bisnow Media', 'status' => 'success']);
    } else if ($number % 3 == 0) {
      return json_encode(['text' => 'Bisnow', 'status' => 'success']);
    } else if ($number % 5 == 0) {
      return json_encode(['text' => 'Media', 'status' => 'success']);
    } else {
      return json_encode(['text' => 'Not a multiple of 3 or 5', 'status' => 'fail']);
    }
  } else {
    return json_encode(['text' => 'Error. Not a number or number is not between 1-1000', 'status' => 'fail']);
  }
}

$number = (int) $_GET['number'];
echo checkNumber($number);

?>
