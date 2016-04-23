<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class logsApObj extends commonObj {

	function viewLogs() {
		
		$sql="
		select request_id,system_amount,system_invoice_count,oracle_amount,oracle_invoice_count,amount_diff from integrity_check_logs order by request_id
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>