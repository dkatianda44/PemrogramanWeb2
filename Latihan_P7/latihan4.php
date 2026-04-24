<?php
$var = array(1,2,3,4,5,6,7);
$scan = is_array($var);

if ($scan == false) {
    $status = "bukan";
} else {
    $status = "";
}

echo "\$var = array(1,2,3,4,5,6,7)";
echo "<br>";
echo "Variabel \$var $status merupakan array";
//Project 7.4 By ASEP SURYA AGUSTIN - 221011450392
?>