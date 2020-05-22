<?php

//SimpleSubstitution 

function Cipher($char,$key)
{   //check if the character is Capital or not 

	$asciiValue=ord($char);
	
	$var=$asciiValue+(int)$key;
	//90=Z && 65=A
	if($var>90)
	{
		$var=(($var%90)-1)+65; // rounded Substitution
	}
    return chr($var);
}



function Encrypt($text,$key)
{
	$text=strtoupper($text);
	$chipertext="";
	$charArray = str_split($text);
	foreach ($charArray as $char)
	{
	  	
	  $chipertext.= Cipher($char,$key);	
	}
	return $chipertext;
}

function Decrypt($text,$key)
{
	$text=strtoupper($text);
	$plaintext="";
	$charArray = str_split($text);
	foreach ($charArray as $char)
	{
	  $plaintext.= Cipher($char,26-($key));	
	  $x=(int)26-$key;
	  //echo"$x<br>";
       	  
	}
		return $plaintext;

}





// DoubleTranspostions

class Pair
{
	public $Key;
	public $Value;
}

function compare($val1, $val2) {
	return strcmp($val1->Value, $val2->Value);
}

function ShiftIndexes($key)
{
	$lenOfkey = strlen($key);
	$indexes = array();
	$sortedarray = array();
    //make a pair using the key 
	for ($i = 0; $i < $lenOfkey; ++$i) {
		$pair = new Pair();
		$pair->Key = $i;
		$pair->Value = $key[$i];
		$sortedarray[] = $pair;
	}
    //sort Sortedarray 
	usort($sortedarray, 'compare');

    // return thr postions of the sorted key 
	for ($i = 0; $i < $lenOfkey; ++$i)
		$indexes[$sortedarray[$i]->Key] = $i;

	return $indexes;
}

function Encrypt2($text, $key)
{
	$lenoftext = strlen($text);
	$lenOfkey = strlen($key);
	// adjust the text replace every space with (-)
	$text = ($lenoftext % $lenOfkey == 0) ? $text : str_pad($text, $lenoftext - ($lenoftext % $lenOfkey) + $lenOfkey,"-", STR_PAD_RIGHT);
	$lenoftext = strlen($text);
	$numofcols = $lenOfkey;
	$numofrows = ceil($lenoftext / $numofcols);
	$rowmatrix1 = array(array());
	$colmatrix2 = array(array());
	$sortedcolmatrix2 = array(array());
	$shiftIndexes = ShiftIndexes($key);

	for ($i = 0; $i < $lenoftext; ++$i)
	{
		$currentRow = $i / $numofcols;
		$currentColumn = $i % $numofcols;
		$rowmatrix1[$currentRow][$currentColumn] = $text[$i];
	}

	for ($i = 0; $i < $numofrows; $i++)
	{
		for ($j = 0; $j < $numofcols; $j++)
		{
			$colmatrix2[$j][$i] = $rowmatrix1[$i][$j];
		}
	}
	for ($i = 0; $i < $numofcols; $i++)
	{
		for ($j = 0; $j < $numofrows; $j++)
		{
			$sortedcolmatrix2[$shiftIndexes[$i]][$j] = $colmatrix2[$i][$j];
		}
	}
	$ciphertext="";
	for ($i = 0; $i < $lenoftext; $i++)
	{
		$currentRow = $i / $numofrows;
		$currentColumn = $i % $numofrows;
		$ciphertext .= $sortedcolmatrix2[$currentRow][$currentColumn];
	}

	return $ciphertext;
}

function Decrypt2($text, $key)
{
	
	$lenOfkey = strlen($key);
	$lenoftext = strlen($text);
	$numofcols = ceil($lenoftext / $lenOfkey);
	$numofrows = $lenOfkey;
	$rowmatrix1 = array(array());
	$colmatrix2 = array(array());
	$unsortedcolmatrix2 = array(array());
	$shiftIndexes = ShiftIndexes($key);

	for ($i = 0; $i < $lenoftext; ++$i)
	{
		$currentRow = $i / $numofcols;
		$currentColumn = $i % $numofcols;
		$rowmatrix1[$currentRow][$currentColumn] = $text[$i];
	}

	for ($i = 0; $i < $numofrows; $i++)
	{
		for ($j = 0; $j < $numofcols; $j++)
		{
			$colmatrix2[$j][$i] = $rowmatrix1[$i][$j];
		}
	}
	
	for ($i = 0; $i < $numofcols; $i++)
	{
		for ($j = 0; $j < $numofrows; $j++)
		{
			$unsortedcolmatrix2[$i][$j] = $colmatrix2[$i][$shiftIndexes[$j]];
		}
	}
    $plaintext = "";
	for ($i = 0; $i < $lenoftext; $i++)
	{
		$currentRow = $i / $numofrows;
		$currentColumn = $i % $numofrows;
		$plaintext .= $unsortedcolmatrix2[$currentRow][$currentColumn];
	}

	return $plaintext;
}
echo"Sample for DoubleTranspostions<br>";
$x=Encrypt2("Hello from the other world","hi");
echo"$x<br>";
$x=Decrypt2(Encrypt2("Hello from the other world","hi"),"hi");
echo"$x<br>";
function RC4($text,$key)
{
	$lenOfkey=strlen($key);
	$lenOftext=strlen($text);
	$data1=array();
	$data2=array();
	$arrayofkey=array();
    $index1=0;$index2=0; $index3=0;$index4 = 0; 
	for($i=0;$i<$lenOfkey;$i++)
	{
		$data1[]=ord($key{$i});
		//echo ord($key{$i});
	}
	
	for($i=0;$i<$lenOftext;$i++)
	{
	   $data2[]=ord($text{$i});
	}
	
	for($i=0;$i<256;$i++)
	{
		$arrayofkey[]=$i;
	}
	
	$len = count($data1);
	for($i=0;$i<256;$i++)
	{
		$index1=($data1[$index2]+$arrayofkey[$i]+$index1)%256;
		//swap
		$temp=$arrayofkey[$i];
		$arrayofkey[$i]=$arrayofkey[$index1];
		$arrayofkey[$index1]=$temp;
	    $index2 =($index2 + 1) % $len; 
	}
	
	$len2 = count($data2);
    $index3 = $index4 = 0; 
    for ($i = 0; $i < $len2; $i++) 
    {
        $index3 = ($index3 + 1) % 256;
        $index4 = ($arrayofkey[$index3] + $index4) % 256;
		//swap
        $temp = $arrayofkey[$index3];
        $arrayofkey[$index3] = $arrayofkey[$index4]; 
        $arrayofkey[$index4] = $temp;
        $data2[$i] ^= $arrayofkey[($arrayofkey[$index3] + $arrayofkey[$index4]) % 256];
    }
	// getting the text
	$plaintext = "";
    for ($i=0;$i<$len2;$i++) 
    {
        $plaintext .=chr($data2[$i]);
    }
    return $plaintext;
} 
echo"Sample for RC4<br>";
$x=RC4("ahmed","hi");
echo$x."<br>";

$x=RC4($x,"hi");
echo$x."<br>";

?>