<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class rcrFileObj extends commonObj {

	function clearTblRcrInvoice(){
		$sql = "
		truncate table tbl_rcr_invoice
		";
		return $this->execQry($sql);
	}
	
	function rcrData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40) {

		$sql="
		insert into tbl_rcr_invoice
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
		from         tbl_rcr_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_rcr_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	function viewRcrInv() {
		
		$sql="
		select distinct(Col001) as Col001 from tbl_rcr_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrRcrInv = $this->viewRcrInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_rcr";
		
		if($this->execQry($truncTable)){
		
			foreach($arrRcrInv as $valRcrInv){
				$sql="
				insert into tbl_ap_invoices_all_rcr (INVOICE_NUM,SEGMENT1)
				select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
					'select 
					ap_invoices_all.INVOICE_NUM,
					ap_suppliers.SEGMENT1
					FROM ap_invoices_all
					left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valRcrInv[Col001]}'' 
                    and ap_invoices_all.org_id = ''{$orgId}''
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
		delete from tbl_rcr_invoice 
		where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)
		in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) from tbl_ap_invoices_all_rcr)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
        }else if($orgId == '85'){
            $orgName = "PG";
        }else if($orgId == '133'){
            $orgName = "PC";
        }else if($orgId == '153'){
            $orgName = "DI";
        }else if($orgId == '113'){
            $orgName = "FL";
        }else{
            $orgName = "";   
        }
		
		$curDate =  date('mdy');
		$curTime =  date('his');
		
        /*
		$sql="
		update tbl_rcr_invoice set Col040 = 'PJ{$curDate}_{$curTime}.301' where Col011 = 'PO' and Col015 = '700'  and Col040 like 'PJ%'
            and Col005  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
		update tbl_rcr_invoice set Col040 = 'PG{$curDate}_{$curTime}.301' where Col011 = 'PO' and Col015 = '101'  and Col040 like 'PG%'
            or Col005  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
		update tbl_rcr_invoice set Col040 = 'PC{$curDate}_{$curTime}.301' where Col011 = 'PO' and Col015 = '302'  and Col040 like 'PC%'
		";
        */
        
        $sql="
        update tbl_rcr_invoice set Col040 = '{$orgName}{$curDate}_{$curTime}.301' where Col011 = 'PO'
        ";
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_rcr_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewRcr($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_rcr_invoice
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