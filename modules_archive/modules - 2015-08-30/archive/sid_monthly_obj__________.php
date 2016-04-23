<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class sidDailyObj extends commonObj {
	
	function mmsData($monYear) {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_sid_invoice";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_sid_invoice
			(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
			Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
			Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
			select *
			from openquery(pgjda, 'select * from mmpgtlib.orapibk WHERE 
			(wgldte like ''%$monYear'')
			and wdrsrc = ''SID''')
			";	
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_sid_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	function viewSidInv() {
		/* naka sum
		$sql="
        select Col001,Col004,sum(cast(Col013 as float)) as Col013 from tbl_sid_invoice group by Col001,Col004
        ";
        */
        $sql="
        select Col001,Col004,Col013 as Col013 from tbl_sid_invoice group by Col001,Col004,Col013
        ";
        
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($monYear) {
		
		$arrSidInv = $this->viewSidInv();
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $truncTable = "truncate table tbl_ap_invoices_all_sid_amount";
        
        if($this->execQry($truncTable)){
        
            foreach($arrSidInv as $valSidInv){
                $sql="
                insert into tbl_ap_invoices_all_sid_amount (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
                select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_AMOUNT
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valSidInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valSidInv[Col004]}''
                    and ap_invoices_all.INVOICE_AMOUNT = ''{$valSidInv[Col013]}''
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
	
	function remvLoadedInv() {
	
		$curDate =  date('mdy');
		$curTime =  date('hms');
		// or tbl_sid_invoice.Col040 like '%{$curDate}%'
		
		$sql="
        delete from tbl_sid_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_sid_amount)
        ";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
		/*
		$sql="
		update tbl_sid_invoice set Col040 = 'PJ{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%'
		update tbl_sid_invoice set Col040 = 'PG{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%'
		update tbl_sid_invoice set Col040 = 'PC{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%'
		";
		*/
		
        /*
		$sql="
		update tbl_sid_invoice set Col040 = 'PJ{$curDate}_{$curTime}.001' where (Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%' and Col007 not like '%CAS') OR 
		(Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%' and Col007 like '%CAS' and cast(Col006 as float) >= -999.99)
		update tbl_sid_invoice set Col040 = 'PG{$curDate}_{$curTime}.001' where (Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%' and Col007 not like '%CAS') OR
		(Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%' and Col007 like '%CAS' and cast(Col006 as float) >= -999.99)
		update tbl_sid_invoice set Col040 = 'PC{$curDate}_{$curTime}.001' where (Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%' and Col007 not like '%CAS') OR
		(Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%' and Col007 like '%CAS' and cast(Col006 as float) >= -999.99)
		update tbl_sid_invoice set Col040 = 'PJ{$curDate}_XXXXXX.001' where Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%' and Col007 like '%CAS' and cast(Col006 as float) < -999.99
		update tbl_sid_invoice set Col040 = 'PG{$curDate}_XXXXXX.001' where Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%' and Col007 like '%CAS' and cast(Col006 as float) < -999.99
		update tbl_sid_invoice set Col040 = 'PC{$curDate}_XXXXXX.001' where Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%' and Col007 like '%CAS' and cast(Col006 as float) < -999.99
		";
		*/
        
        $sql="
        update tbl_sid_invoice set Col040 = 'PJ{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%'
            and Col005  not in 
            ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_sid_invoice set Col040 = 'PG{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%'
            or Col005  in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        update tbl_sid_invoice set Col040 = 'PC{$curDate}_{$curTime}.001' where Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%'
        ";
        
        $sql_duplic="
        update tbl_sid_invoice set Col040 = 'PJ{$curDate}__duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' and Col025 = '700' and Col040 like 'PJ%'
        and Col005 not in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        update tbl_sid_invoice set Col040 = 'PG{$curDate}__duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' and Col025 = '101' and Col040 like 'PG%'
        or Col005 in ('DBAJR','TAGEX','CONJR','PANEX','TAYJR','PROJR','AMPEX','PHIEX','VISPC','GBAEX','STEEX','AHIEX','ANTEX','DCAJR','KALJR','HABJR','TALJR')
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        update tbl_sid_invoice set Col040 = 'PC{$curDate}__duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' and Col025 = '302' and Col040 like 'PC%'
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        ";
        
		if($this->execQry($sql)){
            $this->execQry($sql_duplic);   
        }
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_sid_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewSid($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_sid_invoice
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