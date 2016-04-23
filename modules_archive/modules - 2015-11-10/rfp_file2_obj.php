<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class rfpFileObj extends commonObj {

    function checkReqId($reqId){
        $sql = "
        select count(request_id) as request_id from integrity_check_logs where request_id = '{$reqId}'  
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function insertReqId($reqId){
        $sql = "
        insert into integrity_check_logs (request_id) values('{$reqId}')
        ";
        return $this->execQry($sql);
    }
    
	function clearTblRfpInvoice(){
		$sql = "
		truncate table tbl_rfp_invoice
		";
		return $this->execQry($sql);
	}
	
	function rfpData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40) {

		$sql="
		insert into tbl_rfp_invoice
		(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
		Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
		Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
		VALUES('{$col1}','{$col2}','{$col3}','{$col4}','{$col5}','{$col6}','{$col7}','{$col8}','{$col9}','{$col10}','{$col11}','{$col12}','{$col13}','{$col14}','{$col15}','{$col16}','{$col17}','{$col18}','{$col19}','{$col20}','{$col21}','{$col22}','{$col23}','{$col24}','{$col25}','{$col26}','{$col27}','{$col28}','{$col29}','{$col30}','{$col31}','{$col32}','{$col33}','{$col34}','{$col35}','{$col36}','{$col37}','{$col38}','{$col39}','{$col40}')
		";
		$this->execQry($sql);
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function displayLoadedRec(){
		$sql = "
		select  count(*) as loaded
		from         tbl_rfp_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace($reqId) {
		$sql="
		update tbl_rfp_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		return $this->execQry($sql);
	}
    
    function sumMmsData($reqId) {
        $sql="
        select sum(uniqAmt) as uniqMmsAmt from 
        (select sum(cast(Col013 as float)) as uniqAmt,cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as uniqInv from tbl_rfp_invoice group by cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)) as tbl_rfp_invoice
        ";
        if($uniqMmsAmt = $this->getSqlAssoc($this->execQry($sql))){
            $sql="
            update integrity_check_logs set system_amount = '{$uniqMmsAmt['uniqMmsAmt']}' where request_id = '{$reqId}'
            ";
            return $this->execQry($sql);
        }
        
    }
    
    function countMmsInvData($reqId) {
        $sql="
        select count(uniqInv) uniqMmsInv from 
        (select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as uniqInv from tbl_rfp_invoice group by cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)) as tbl_rfp_invoice
        ";
        if($uniqMmsInv = $this->getSqlAssoc($this->execQry($sql))){
            $sql="
            update integrity_check_logs set system_invoice_count = '{$uniqMmsInv['uniqMmsInv']}' where request_id = '{$reqId}'
            ";
            return $this->execQry($sql);
        }
    }
	
	function viewRfpInv($reqId) {
		
		$sql="
        select Col001,Col004,sum(cast(Col013 as float)) as Col013 from tbl_rfp_invoice group by Col001,Col004
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($reqId) {
		
		$arrRfpInv = $this->viewRfpInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_rfp_amount";
		
		if($this->execQry($truncTable)){
		
			foreach($arrRfpInv as $valRfpInv){
                echo $sql="
                insert into tbl_ap_invoices_all_rfp_amount (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
                select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_AMOUNT
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valRfpInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valRfpInv[Col004]}''
                    and ap_invoices_all.INVOICE_AMOUNT = ''{$valRfpInv[Col013]}''
                    '
                    ) ORAPROD
                ";
                $this->execQry($turnOnAnsiNulls);
                $this->execQry($turnOnAnsiWarn);
                $this->execQry($sql);
            }
            return true;
		}
	}
    
    function sumOracleData($reqId) {
        $sql="
        select sum(INVOICE_AMOUNT) AS INVOICE_AMOUNT from  tbl_ap_invoices_all_rfp_amount
        ";
        if($invAmount =  $this->getSqlAssoc($this->execQry($sql))){
            $sql="
            update integrity_check_logs set oracle_amount = '{$invAmount['INVOICE_AMOUNT']}' where request_id = '{$reqId}'
            ";
            return $this->execQry($sql);
        }
    }
    
    function countOracleInvData($reqId) {
        $sql="
        select count(uniqInv) uniqOracleInv from 
        (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) as uniqInv from tbl_ap_invoices_all_rfp_amount group by cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)) as tbl_ap_invoices_all_rfp_amount
        ";
        if($uniqOracleInv =  $this->getSqlAssoc($this->execQry($sql))){
            $sql2="
            update integrity_check_logs set oracle_invoice_count = '{$uniqOracleInv['uniqOracleInv']}' where request_id = '{$reqId}'
            ";
            if($this->execQry($sql2)){
                $sql3="
                update integrity_check_logs set amount_diff = system_amount - oracle_amount where request_id = '{$reqId}'
                ";
                return $this->execQry($sql3);
            }
        }
    }
	
	function remvLoadedInv() {
		
        $sql="
        delete from tbl_rfp_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_rfp_amount)
        ";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
		$sql="
        update tbl_rfp_invoice set Col040 = 'PJ{$curDate}_{$curTime}.G01' where Col015 = '700' and Col040 like 'PJ%'
            and Col005  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_rfp_invoice set Col040 = 'PG{$curDate}_{$curTime}.G01' where Col015 = '101' and Col040 like 'PG%'
            or Col005  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_rfp_invoice set Col040 = 'PG{$curDate}_{$curTime}.G01' where Col015 = '101' and Col040 like 'ABS%'
        update tbl_rfp_invoice set Col040 = 'PG{$curDate}_{$curTime}.G01' where Col015 = '700' and Col040 like 'ABS%'
        update tbl_rfp_invoice set Col040 = 'PC{$curDate}_{$curTime}.G01' where Col015 = '302' and Col040 like 'PC%'
        ";
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_rfp_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewRfp($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_rfp_invoice
		where Col040 = '{$fileName}'
		GROUP BY 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>