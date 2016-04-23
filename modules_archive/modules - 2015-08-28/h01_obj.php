<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class h01Obj extends commonObj {

	function clearTblH01Invoice(){
		$sql = "
		truncate table tbl_h01_invoice
		";
		return $this->execQry($sql);
	}
	
	function h01Data($orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $sites = "
            where strshrt in 
            (
                select stshrt from openquery(pgjda, 'select * from mmpgtlib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            ";
        }else if($orgId == '85'){
            $orgName = "PG";
            $sites = "
            where strshrt in 
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
        }else if($orgId == '133'){
            $orgName = "PC";
            $sites = "
            where strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
                and STSHRT <> 'SBCHO'
            )
            ";
        }else if($orgId == '153'){
            $orgName = "DI";
            $sites = "
            where strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
                and STRNUM < 900
            )
            ";
        }else if($orgId == '113'){
            $orgName = "FL";
            $sites = "
            where strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
                and STRNUM < 900
            )
            ";
        }else{
            $orgName = "";   
        }

		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$sql="
		insert into  tbl_h01_invoice
		select invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,col011,col012,col013,amount,col015,curency,col017,filename,col019
		from openquery([192.168.200.229], 'select invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,col011,col012,col013,amount,col015,curency,col017,filename,col019 from ORA.dbo.ar_invoice') CRMREP
		{$sites}
        ";
		$this->execQry($turnOnAnsiNulls);
		$this->execQry($turnOnAnsiWarn);
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
	
	function oracleData($orgId) {
		
		$arrH01Inv = $this->viewH01Inv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_RA_CUSTOMER_TRX_ALL_H01";
		
		if($this->execQry($truncTable)){
		
			foreach($arrH01Inv as $valH01Inv){
				$sql="
				insert into tbl_RA_CUSTOMER_TRX_ALL_H01 (trx_number)
				select ORAPROD.trx_number from openquery(ORAPROD,
				'SELECT RA_CUSTOMER_TRX_ALL.trx_number FROM RA_CUSTOMER_TRX_ALL
				JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
				where RA_CUSTOMER_TRX_ALL.trx_number = ''{$valH01Inv[invoice]}''
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
		delete from tbl_h01_invoice 
		where invoice
		in (select trx_number from tbl_RA_CUSTOMER_TRX_ALL_H01)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
		
		$curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        if($orgId == '87'){
            $orgName = "PJ";
            
            $sql="
            update tbl_h01_invoice set filename = '{$orgName}{$curDate}_{$curTime}.H01' where type = 'STS'
            and strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            ";
            
        }else if($orgId == '85'){
            $orgName = "PG";
            
            $sql="
            update tbl_h01_invoice set filename = '{$orgName}{$curDate}_{$curTime}.H01' where type = 'STS' 
            and strshrt in 
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
            
        }else if($orgId == '133'){
            $orgName = "PC";
            
            $sql="
            update tbl_h01_invoice set filename = '{$orgName}{$curDate}_{$curTime}.H01' where type = 'STS'
            and strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
                and STSHRT <> 'SBCHO'
            )
            ";
            
        }else if($orgId == '153'){
            $orgName = "DI";
            
            $sql="
            update tbl_h01_invoice set filename = '{$orgName}{$curDate}_{$curTime}.H01' where type = 'STS'
            and strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
                and STRNUM < 900
            )
            ";
            
        }else if($orgId == '113'){
            $orgName = "FL";
            
            $sql="
            update tbl_h01_invoice set filename = '{$orgName}{$curDate}_{$curTime}.H01' where type = 'STS'
            and strshrt in 
            (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
                and STRNUM < 900
            )
            ";
            
        }else{
            $orgName = "";  
            $sql="
            error
            "; 
        }
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
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