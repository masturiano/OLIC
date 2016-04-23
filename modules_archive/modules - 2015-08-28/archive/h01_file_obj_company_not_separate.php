<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class h01FileObj extends commonObj {

	function clearTblH01Invoice(){
		$sql = "
		truncate table tbl_h01_invoice
		";
		return $this->execQry($sql);
	}
	
	function h01Data($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18) {

		$sql="
		insert into tbl_h01_invoice
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
		from         tbl_h01_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_h01_invoice set invoice = LTRIM(RTRIM(invoice))
		";
		$this->execQry($sql);
	}
	
	function viewH01Inv() {
		
		$sql="
		select distinct(invoice) as invoice from tbl_h01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData() {
		
		$arrA01Inv = $this->viewH01Inv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_RA_CUSTOMER_TRX_ALL";
		
		if($this->execQry($truncTable)){
		
			foreach($arrA01Inv as $valA01Inv){
				$sql="
				insert into tbl_RA_CUSTOMER_TRX_ALL (trx_number)
				select ORAPROD.trx_number from openquery(ORAPROD,
				'SELECT RA_CUSTOMER_TRX_ALL.trx_number FROM RA_CUSTOMER_TRX_ALL
				JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
				where (RA_CUSTOMER_TRX_ALL.org_id = 87 OR RA_CUSTOMER_TRX_ALL.org_id = 85 OR RA_CUSTOMER_TRX_ALL.org_id = 133 OR RA_CUSTOMER_TRX_ALL.org_id = 89 OR RA_CUSTOMER_TRX_ALL.org_id = 91)
				and RA_CUSTOMER_TRX_ALL.trx_number = ''{$valA01Inv[invoice]}'''
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
		delete from tbl_h01_invoice 
		where invoice
		in (select trx_number from tbl_RA_CUSTOMER_TRX_ALL)
		";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
        /*
        $sql="
        update tbl_h01_invoice set filename = 'PG{$curDate}_{$curTime}.H01' where col008 in ('004','006')
        update tbl_h01_invoice set filename = 'PJ{$curDate}_{$curTime}.H01' where col008 = '005'
        update tbl_h01_invoice set filename = 'PC{$curDate}_{$curTime}.H01' where col008 = '010'
        "; 
        */
        
		$sql="
		update tbl_h01_invoice set filename = 'PG{$curDate}_{$curTime}.H01' where col008 in ('004','006')
            or strshrt  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
		    or strshrt  in ('BSIJR','DMRJR','HABJR')
        update tbl_h01_invoice set filename = 'PJ{$curDate}_{$curTime}.H01' where col008 = '005' 
            and strshrt  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
		    and strshrt  not in ('BSIJR','DMRJR','HABJR')
        update tbl_h01_invoice set filename = 'PC{$curDate}_{$curTime}.H01' where col008 = '010'
		";
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(filename) as filename
		FROM tbl_h01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewH01($fileName) {
		
		$sql="
		SELECT 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		FROM tbl_h01_invoice
		where filename = '{$fileName}'
		GROUP BY 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>