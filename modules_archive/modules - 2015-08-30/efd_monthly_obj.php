<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class olicDailyObj extends commonObj {
	
	function mmsData($monYear,$orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $library = "mmpgtlib";
        }else if($orgId == '85'){
            $orgName = "PG";
            $library = "mmpgtlib";
        }else if($orgId == '133'){
            $orgName = "PC";
            $library = "mmpgtlib";
        }else if($orgId == '153'){
            $orgName = "DI";
            $library = "mmneslib";
        }else if($orgId == '113'){
            $orgName = "FL";
            $library = "mmneslib";
        }else{
            $orgName = "";   
        }
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_efd_invoice";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_efd_invoice
			(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
			Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
			Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
			select *
			from openquery(pgjda, 'select * from {$library}.orapibk WHERE 
			(wgldte like ''%$monYear'')
			and wdrsrc = ''STS''
            and wfname like ''{$orgName}%''
            ')
			";	  
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_efd_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	function viewOlicInv() {
		
		$sql="
		select Col001,Col004,Col013 as Col013 from tbl_efd_invoice group by Col001,Col004,Col013
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrOlicInv = $this->viewOlicInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_efd";
		
		if($this->execQry($truncTable)){
		
			foreach($arrOlicInv as $valOlicInv){
				$sql="
                insert into tbl_ap_invoices_all_efd (INVOICE_NUM,SEGMENT1)
                select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valOlicInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valOlicInv[Col004]}''
                    and ap_invoices_all.org_id = ''{$orgId}''     
                    and (ap_invoices_all.source = ''STS'' or ap_invoices_all.source = ''Manual Invoice Entry'')  
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
	
		$curDate =  date('mdy');
		$curTime =  date('his');
		
		$sql="
        delete from tbl_efd_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) from tbl_ap_invoices_all_efd)
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
        update tbl_rcr_invoice set Col040 = 'PG{$curDate}_{$curTime}.301' where Col011 = 'PO' and Col015 = '101'  and Col040 like 'PG%'
        update tbl_rcr_invoice set Col040 = 'PC{$curDate}_{$curTime}.301' where Col011 = 'PO' and Col015 = '302'  and Col040 like 'PC%'
        ";
        */
        
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
        update tbl_efd_invoice set Col040 = '{$orgName}{$curDate}_{$curTime}.901' where Col011 = 'RTV'
        ";
        
        $this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_efd_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewOlic($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_efd_invoice
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