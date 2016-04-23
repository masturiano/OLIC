<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class fhilipObj extends commonObj {
	
	function fhilipData() {
	
		$sql="SELECT Column_ FROM [dbo].[__X] where len(Column_)<13";
			  
		$arr =  $this->getArrRes($this->execQry($sql));
	
	
		 foreach($arr as $val){ 
		 
			 $aaaa = $val["Column_"];
			 $even = $aaaa[0]+$aaaa[2]+$aaaa[4]+$aaaa[6]+$aaaa[8]+$aaaa[10];
			 $odd = ($aaaa[1]+$aaaa[3]+$aaaa[5]+$aaaa[7]+$aaaa[9]+$aaaa[11]) * 3;
			 
			 
			   $total =  $even+ $odd ;
			  $lenlen = strlen( $total) - 1;
			   $check =10- substr($total,-1);
		
			if( $check == 10){
				 $check = 0;
			}
		
			  //$sqlsss="update NE_mall_last set checkdigit = '$check' where barcode = '".$val["barcode"]."'";
			  $sqlsss="insert into checkDigitSirGilbert_NE (checkdigit,barcode) values ('".$check."','".$val["Column_"]."')";
			  
			$this->execQry($sqlsss);
		
		}
	}
}
?>