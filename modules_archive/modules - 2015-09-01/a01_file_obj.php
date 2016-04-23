<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class a01FileObj extends commonObj {

	function clearTblA01Invoice(){
		$sql = "
		truncate table tbl_a01_invoice
		";
		return $this->execQry($sql);
	}
	
	function a01Data($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18) {

		$sql="
		insert into tbl_a01_invoice
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
		from         tbl_a01_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_a01_invoice set invoice = LTRIM(RTRIM(invoice))
		";
		$this->execQry($sql);
	}
	
	function viewA01Inv() {
		
		$sql="
		select distinct(invoice) as invoice from tbl_a01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrA01Inv = $this->viewA01Inv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_RA_CUSTOMER_TRX_ALL_A01";
		
		if($this->execQry($truncTable)){
		
			foreach($arrA01Inv as $valA01Inv){
				$sql="
				insert into tbl_RA_CUSTOMER_TRX_ALL_A01 (trx_number)
				select ORAPROD.trx_number from openquery(ORAPROD,
				'SELECT RA_CUSTOMER_TRX_ALL.trx_number FROM RA_CUSTOMER_TRX_ALL
				JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
				where RA_CUSTOMER_TRX_ALL.trx_number = ''{$valA01Inv[invoice]}''
                and RA_CUSTOMER_TRX_ALL.org_id = ''{$orgId}'' 
                '
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
		delete from tbl_a01_invoice 
		where invoice
		in (select trx_number from tbl_RA_CUSTOMER_TRX_ALL_A01)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
		
		$curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_a01_invoice set filename = 'PJ{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";  
        
        $sqlPg="
        update tbl_a01_invoice set filename = 'PG{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from mmpgtlib.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mmpgtlib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900    
        )
        ";
        
        $sqlPc="
        update tbl_a01_invoice set filename = 'PC{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlDi="
        update tbl_a01_invoice set filename = 'DI{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_a01_invoice set filename = 'FL{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        ";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        if($this->execQry($sqlPj)){
            if($this->execQry($sqlPg)){
                if($this->execQry($sqlPc)){
                    if($this->execQry($sqlDi)){
                        if($this->execQry($sqlFl));
                    }
                }
            }    
        }
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(filename) as filename
		FROM tbl_a01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewA01($fileName) {
		
		$sql="
		SELECT 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		FROM tbl_a01_invoice
		where filename = '{$fileName}'
		GROUP BY 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>