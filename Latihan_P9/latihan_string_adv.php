<?php
$kalimat = "   Belajar PHP di Pamijahan   ";

// trim
echo "Trim: '" . trim($kalimat) . "'<br>";

// substr
echo "Substr: " . substr($kalimat, 3, 7) . "<br>";

// substr_count
echo "Jumlah 'PHP': " . substr_count($kalimat, "PHP") . "<br>";

// strpos
echo "Posisi 'PHP': " . strpos($kalimat, "PHP") . "<br>";

// addcslashes
echo "Addcslashes: " . addcslashes("Hello World", "W") . "<br>";

// stripslashes
echo "Stripslashes: " . stripslashes("Hello\\World") . "<br>";

// strip_tags
echo "Strip_tags: " . strip_tags("<b>Hello</b> <i>World</i>") . "<br>";

// htmlentities
echo "Htmlentities: " . htmlentities("<b>Hello World</b>") . "<br>";

// strrev
echo "Strrev: " . strrev("Pamijahan") . "<br>";

// str_replace
echo "Str_replace: " . str_replace("PHP", "JavaScript", $kalimat) . "<br>";
// Project 9.6 By ASEP SURYA AGUSTIN - 221011450392
?>