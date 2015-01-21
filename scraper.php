<?php
function get_data($url) {
	$ch = curl_init();
	$timeout = 10;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
 
$dati_arpa = get_data("http://www.arpa.puglia.it/pentaho/ViewAction?solution=ARPAPUGLIA&path=portal/export&action=getCentraline_export_per_inquinante.xaction");
$riga = explode("\n", $dati_arpa);

$i=0;
$j=0;

$arr_dati=array();
$return=array();

foreach ($riga as $value) {
		
			list ($a, $b, $c, $d, $e) = split ('[;]', $value);
			if (strpos($a,'ARPA Puglia') !== false) { 
				$datadelgiorno=$e;
			}
			
			if (strpos($a,'Inquinante') !== false) { 
				$inquinante=substr($a, 11);
			}
		 
			if ($i>8){
				
				if ((strpos($a,'NomeCentralina') !== false) || (strpos($a,'Inquinante:') !== false) || (trim($a) == '')){ 	
				}
				else{
					$arr_dati['Data']=trim($datadelgiorno);
					$arr_dati['Inquinante']=trim($inquinante); 
					$arr_dati['Centralina']=trim($a);
					$arr_dati['Comune']=trim($b);
					$arr_dati['Provincia']=trim($c);
					$arr_dati['Valore']=$d;
		
					//print_r($arr_dati);
					$return[$j]=$arr_dati;
					$j++;
				}
			}
	
		$i++;
		   
}
//print_r($return);
echo json_encode($return);
?>
