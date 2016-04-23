<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class j01FileObj extends commonObj {

	function clearTblJ01Invoice(){
		$sql = "
		truncate table tbl_j01_invoice
		";
		return $this->execQry($sql);
	}
	
	function j01Data($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18) {

		$sql="
		insert into tbl_j01_invoice
		(invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,Col012,Col013,amount,Col015,curency,col017,filename,col019)
		VALUES('{$col1}','{$col2}','{$col3}','{$col4}','{$col5}','{$col6}','{$col7}','{$col8}','{$col9}','{$col10}','{$col11}','{$col12}','{$col13}','{$col14}','{$col15}','{$col16}','{$col17}','{$col18}',NULL)
		";
		$this->execQry($sql);
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function displayLoadedRec(){
		$sql = "
		select  count(*) as loaded
		from         tbl_j01_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_j01_invoice set invoice = LTRIM(RTRIM(invoice))
		";
		$this->execQry($sql);
	}
	
	function viewJ01Inv() {
		
		$sql="
		select distinct(invoice) as invoice from tbl_j01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData() {
		
		$arrJ01Inv = $this->viewJ01Inv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_RA_CUSTOMER_TRX_ALL_J01";
		
		if($this->execQry($truncTable)){
		
			foreach($arrJ01Inv as $valJ01Inv){
				$sql="
				insert into tbl_RA_CUSTOMER_TRX_ALL_J01 (trx_number)
				select ORAPROD.trx_number from openquery(ORAPROD,
				'SELECT RA_CUSTOMER_TRX_ALL.trx_number FROM RA_CUSTOMER_TRX_ALL
				JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
				where (RA_CUSTOMER_TRX_ALL.org_id = 87 OR RA_CUSTOMER_TRX_ALL.org_id = 85 OR RA_CUSTOMER_TRX_ALL.org_id = 133)
				and RA_CUSTOMER_TRX_ALL.trx_number = ''{$valJ01Inv[invoice]}'''
				) ORAPROD
				";
				$this->execQry($turnOnAnsiNulls);
				$this->execQry($turnOnAnsiWarn);
				$this->execQry($sql);
			}
		}
	}
	
	function remvLoadedInv() {
		
		$sql="
		delete from tbl_j01_invoice 
		where invoice
		in (select trx_number from tbl_RA_CUSTOMER_TRX_ALL_J01)
		";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		/*
		$sql="
		update tbl_j01_invoice set filename = 'PG{$curDate}_{$curTime}.J01' where col008 in ('004','006')
		update tbl_j01_invoice set filename = 'PJ{$curDate}_{$curTime}.J01' where col008 = '005'
		update tbl_j01_invoice set filename = 'PC{$curDate}_{$curTime}.J01' where col008 = '010'
		";
        */
        $sql="
        update tbl_j01_invoice set filename = 'PG{$curDate}_{$curTime}.J01' where col008 in ('004','006')
            or strshrt  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_j01_invoice set filename = 'PJ{$curDate}_{$curTime}.J01' where col008 = '005'
             and strshrt  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_j01_invoice set filename = 'PC{$curDate}_{$curTime}.J01' where col008 = '010'
        ";
        
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(filename) as filename
		FROM tbl_j01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewJ01($fileName) {
		
		$sql="
		SELECT 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		FROM tbl_j01_invoice
		where filename = '{$fileName}'
		GROUP BY 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>