<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsObj extends commonObj {

	function mmsData($orgId) {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_sts_invoice";
        
        if($orgId == '89'){
            $orgName = "where Col005 = 'DC'";
        }else if($orgId == '91'){
            $orgName = "where Col005 = 'DS'";
        }else{
            $orgName = "";   
        }
		
		if($this->execQry($truncTable)){
			$sql="
			insert into  tbl_sts_invoice
			select *
			from openquery(PGPAYSVR, 'select * from ora.dtsloop_x')
            $orgName
			";	
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
	}
	
	function mmsDataRemvSpace($orgId) {
		$sql="
		update tbl_sts_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}

	function viewStsInv() {
		
		$sql="
        select Col001,Col004,Col013 as Col013 from tbl_sts_invoice group by Col001,Col004,Col013
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrStsInv = $this->viewStsInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_sts";
        
        if($orgId == '89'){
            $orgName = "and ap_invoices_all.org_id = ''89''";
        }else if($orgId == '91'){
            $orgName = "and ap_invoices_all.org_id = ''91''";
        }else{
            $orgName = "";   
        }
		
		if($this->execQry($truncTable)){
		
			foreach($arrStsInv as $valStsInv){
				$sql="
				insert into tbl_ap_invoices_all_sts (INVOICE_NUM,SEGMENT1)
				select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
					'select 
					ap_invoices_all.INVOICE_NUM,
					ap_suppliers.SEGMENT1
					FROM ap_invoices_all
					left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
					where ap_invoices_all.INVOICE_NUM = ''{$valStsInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valStsInv[Col004]}''
                    and (ap_invoices_all.source = ''STS'' or ap_invoices_all.source = ''Manual Invoice Entry'') 
                    $orgName'
					) ORAPROD
				";
				$this->execQry($turnOnAnsiNulls);
				$this->execQry($turnOnAnsiWarn);
				$this->execQry($sql);
			}
		}
	}
	
	function remvLoadedInv($orgId) {
		
		$sql="
        delete from tbl_sts_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) from tbl_ap_invoices_all_sts)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
		$sql="
		update tbl_sts_invoice set Col040 = 'DC{$curDate}_{$curTime}.401' where Col011 = 'STS' and Col025 = '1001'
		update tbl_sts_invoice set Col040 = 'DS{$curDate}_{$curTime}.401' where Col011 = 'STS' and Col025 = '1002'
		";
		$this->execQry($sql);
	}
	
	function viewFileName($orgId) {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_sts_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewSts($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_sts_invoice
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