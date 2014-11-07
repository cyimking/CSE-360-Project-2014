<?php

$a = "11:00 PM";
$b = "12:00 AM";

$a = strtotime($a);
$b = strtotime($b);

$c = date('h:i A',$a);

echo $c;

?>