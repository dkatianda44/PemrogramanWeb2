<?php
function repeat($text, $num = 10)
{
   // validasi jumlah
   if (!is_numeric($num) || $num < 1) {
      return "Jumlah harus angka positif!";
   }

   $output = "<ol>\n";
   for($i = 0; $i < $num; $i++)
   {
      $safeText = htmlspecialchars($text);
      $output .= "<li>$safeText</li>\n";
   }
   $output .= "</ol>";

   return $output;
}

// pemanggilan fungsi
echo repeat("I'm the best", 15);
echo repeat("You're the man");
?>