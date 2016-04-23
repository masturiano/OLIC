<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class rtvDailyObj extends commonObj {
	
	function mmsData($monYear) {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_rtv_invoice";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_rtv_invoice
			(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
			Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
			Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
			select *
			from openquery(pgjda, 'select * from mmpgtlib.orapibk WHERE 
			(wgldte = ''{$monYear}'')
			and wdrsrc = ''RTV''')
			";	
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_rtv_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	/*function oracleData() {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_ap_invoices_all (INVOICE_NUM,SEGMENT1)
			select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
				'select 
				ap_invoices_all.INVOICE_NUM,
				ap_suppliers.SEGMENT1
				FROM ap_invoices_all
				left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
				where ap_invoices_all.source = ''PO''
				and ap_invoices_all.Creation_Date >= to_date(''01-Feb-2013'')'
				) ORAPROD
			where ORAPROD.INVOICE_NUM in (select distinct(Col001) from tbl_rtv_invoice)
			";
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
	}*/
	
	function viewRtvInv() {
		
		$sql="
		select distinct(Col001) as Col001 from tbl_rtv_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData() {
		
		$arrRtvInv = $this->viewRtvInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_rtv";
		
		if($this->execQry($truncTable)){
		
			foreach($arrRtvInv as $valRtvInv){
				$sql="
				insert into tbl_ap_invoices_all_rtv (INVOICE_NUM,SEGMENT1)
				select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
					'select 
					ap_invoices_all.INVOICE_NUM,
					ap_suppliers.SEGMENT1
					FROM ap_invoices_all
					left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
					where ap_invoices_all.INVOICE_NUM = ''{$valRtvInv[Col001]}'''
					) ORAPROD
				";
				$this->execQry($turnOnAnsiNulls);
				$this->execQry($turnOnAnsiWarn);
				$this->execQry($sql);
			}
		}
	}
	
	function remvLoadedInv() {
	
		$curDate =  date('mdy');
		$curTime =  date('hms');
		// or tbl_rtv_invoice.Col040 like '%{$curDate}%'
		
		$sql="
		delete from tbl_rtv_invoice 
		where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)
		in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) from tbl_ap_invoices_all_rtv)
		";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
        /*
		$sql="
		update tbl_rtv_invoice set Col040 = 'PJ{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '700'  and Col040 like 'PJ%'
		update tbl_rtv_invoice set Col040 = 'PG{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '101'  and Col040 like 'PG%'
		update tbl_rtv_invoice set Col040 = 'PC{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '302'  and Col040 like 'PC%'
		";
        */
        
        $sql="
        update tbl_rtv_invoice set Col040 = 'PJ{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '700'  and Col040 like 'PJ%'
            and Col005  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_rtv_invoice set Col040 = 'PG{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '101'  and Col040 like 'PG%'
            or Col005  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_rtv_invoice set Col040 = 'PC{$curDate}_{$curTime}.901' where Col011 = 'RTV' and Col025 = '302'  and Col040 like 'PC%'
        ";
        
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_rtv_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewRtv($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_rtv_invoice
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