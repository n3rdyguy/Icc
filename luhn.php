<?php
/***
Komplette tekster  	icc 	imsi 	pin1 	puk1 	pin2 	puk2 	gsm 	custid
	Ret 	Slet 	0 	089238029710000000 	  	  	  	  	0 	1029623724
	Ret 	Slet 	1 	089238029710000001 	  	  	  	  	0 	1029623724
	Ret 	Slet 	2 	089238029710000002 	  	  	  	  	0 	1029623724
	Ret 	Slet 	3 	089238029710000003 	  	  	  	  	0 	1029623724
	Ret 	Slet 	4 	089238029710000004 	  	  	  	  	0 	1029623724
	Ret 	Slet 	5 	089238029710000005 	  	  	  	  	0 	1029623724
	Ret 	Slet 	6 	089238029710000006 	  	  	  	  	0 	1029623724
	Ret 	Slet 	7 	089238029710000007 	  	  	  	  	0 	1029623724
	Ret 	Slet 	8 	089238029710000008 	  	  	  	  	0 	1029623724
	Ret 	Slet 	9 	089238029710000009 	  	  	  	  	0 	1029623724
	Ret 	Slet 	8945020185620000102 	089238029710000010 	0000 	48391102 	2222 	86078660 	4581101014 	1029623724
	Ret 	Slet 	8945020185620000110 	089238029710000011 	0000 	08629261 	2222 	06557814 	4581101013 	1029623724
	Ret 	Slet 	8945020185620000128 	089238029710000012 	0000 	66157922 	2222 	77439036 	4581101012 	1029623724
	Ret 	Slet 	8945020185620000136 	089238029710000013 	0000 	98956585 	2222 	54262122 	4581101020 	1029623724
	Ret 	Slet 	8945020185620000144 	089238029710000014 	0000 	87082610 	2222 	80089609 	4581101016 	1029623724
	Ret 	Slet 	8945020185620000151 	089238029710000015 	0000 	68550559 	2222 	86125749 	4581101011 	1029623724
	Ret 	Slet 	8945020185620000169 	089238029710000016 	0000 	47210744 	2222 	54790151 	4581101018 	1029623724
	Ret 	Slet 	8945020185620000177 	089238029710000017 	0000 	54277675 	2222 	93795709 	4521764406 	1025640977

   */
function GetICCFromIMSI ($imsi) {
	$iPiICCRange = "894502018562";
	$IMSIRange = "89238029710";
	if ($imsi[0] == "0")
		$imsi = substr($imsi, 1);
	$imsi = str_replace($IMSIRange, "", $imsi);
	return $iPiICCRange . $imsi;
}
function GetFullICCWithoutCD ($icc) {
	$iPiICCRange = "89450201";
	return (strlen($icc) == 10 ? $iPiICCRange . $icc : $icc);
}
function GetFullICCWithCD ($icc) {
	$iPiICCRange = "89450201";
	return (strlen($icc) == 11 ? $iPiICCRange . substr($icc, 0, -1) : substr($icc, 0, -1));
}
function GetICCCheckDigitFromICC ($icc, $icclen, $return_full_icc = 0) {
	$total = "";
	if (strlen($icc) == $icclen)
		$icc = GetFullICCWithCD($icc);
	else
		$icc = GetFullICCWithoutCD($icc);
	$icc = preg_replace('/\D/', '', $icc);
	$len = strlen($icc);
	$parity = $len%2;
	//echo $icc . PHP_EOL;
	for ($i=0; $i<$len; $i++) {
		$digit=$icc[$i];
		if ($i % 2 != $parity) {
			$digit*=2;
			if ((int)$digit > 9) {
				//echo "Adding $digit (double)" . PHP_EOL;
				$digit-=9;
			} else {
				//echo "Adding $digit (parity)" . PHP_EOL;
			}
		} else {
			//echo "Adding $digit (other)" . PHP_EOL;
		}
		$total+=$digit;
	}
	$mod = $total % 10;
	$cd = ($mod ? 10 - $mod: 0);
	if ($return_full_icc)
		return $icc . $cd;
	return $cd;
}
$imsi = array (	'089238029710000000',
				'089238029710000001',
				'089238029710000002',
				'089238029710000003',
				'089238029710000004',
				'089238029710000005',
				'089238029710000006',
				'089238029710000007',
				'089238029710000008',
				'089238029710000009',
				);
//echo GetICCCheckDigitFromICC(GetICCFromIMSI("089238029710000010"), 19, 1);
/*for ($i=0; $i < sizeof($imsi); $i++) {
	echo "IMSI: " . $imsi[$i] . " ICC: " . GetICCCheckDigitFromICC(GetICCFromIMSI($imsi[$i]), 19, 1) . "<br>";
}*/










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

//echo ICCGetCheckDigit("11601370380", 0);
?>
