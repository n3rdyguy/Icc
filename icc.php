<?php
/*
ICC numre: 

Telia:
0401081216091484
0401081216091476

Telenor:
21222958330
89450143050215293576
14214176011

TDC:
89450133081126515831
89450109011601370380

Hi3G:
8945060901870080151

*/

# Luhn check
function is_luhn_valid($number)
{
	$doubledNumber  = "";
	$odd            = false;
	for($i = strlen($number)-1; $i >=0; $i--) {
		$doubledNumber .= ($odd) ? $number[$i]*2 : $number[$i];
		$odd            = !$odd;
	}
	$sum = 0; // Add up each 'single' digit
	for($i = 0; $i < strlen($doubledNumber); $i++) {
		$sum += (int)$doubledNumber[$i];
	}
	return (($sum % 10 ==0) && ($sum != 0));
}

function ICCGetCheckDigit ($icc) {
	$total 	= "";
	$icc 		= substr($icc, 0, -1);			// Remove original cd
	$len 		= strlen($icc);
	$parity = $len%2;
	for ($i=0; $i<$len; $i++) {
		$digit=$icc[$i];
		if ($i % 2 != $parity) {
			$digit*=2;
			if ((int)$digit > 9) {
				$digit-=9;
			}
		}
		$total+=$digit;
	}
	$mod = $total % 10;
	$cd = ($mod ? 10 - $mod : 0);
	return $cd;
}

# ICC validdds
function ICCValidate ($icc) {
	$len 		= strlen($icc);
	$cd			= substr($icc, -1);				// Check Digit
	switch ($len) {
		case 16: // Fix for Telia
			if ($icc[0] == "0") {
				$icc = "8945" . $icc;
			}
		break;
	}
	// original cd og udregnet cd skal være ens
	echo "-- " . $icc . " : " . $cd . " : " . ICCGetCheckDigit($icc) . " -- <br>";
	return $cd == ICCGetCheckDigit($icc);
}

$icc_numre = array (
	"0401081216091484", "0401081216091476", "21222958330",
	"89450143050215293576", "14214176011", "89450133081126515831",
	"8945060901870080151", "89450109011601370380", "89450109011601370380", 
	"89450109011601370398", "352635020886815", "89450100040217633664",
	"89450143050215293576", "10218440718"
);

//echo is_luhn_valid($icc_numre[1]);
/*for ($i=0, $len = count($icc_numre); $i < $len; $i++) {
	echo $icc_numre[$i] . " : " . (validate_icc($icc_numre[$i]) ? "Valid" : "Not Valid") . "<br>";
}
echo "<p>";
*/
for ($i=0, $len = count($icc_numre); $i < $len; $i++) {
	echo $icc_numre[$i] . " : " . (ICCValidate($icc_numre[$i]) ? "YES!" : "Nææ") . "<br>";
	//echo "<br>";
	//echo $icc_numre[$i] . " : " .ICCGetCheckDigit($icc_numre[$i], strlen($icc_numre[$i])). " (" . strlen($icc_numre[$i]) . ")<br>";
}
?>
