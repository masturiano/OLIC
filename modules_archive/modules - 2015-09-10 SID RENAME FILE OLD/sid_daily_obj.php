<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class olicDailyObj extends commonObj {
	
	function mmsData($monYear,$orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $library = "mm760lib";
        }else if($orgId == '85'){
            $orgName = "PG";
            $library = "mm760lib";
        }else if($orgId == '133'){
            $orgName = "PC";
            $library = "mm760lib";
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
		
		$truncTable = "truncate table tbl_sid_invoice";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_sid_invoice
			(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
			Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
			Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
			select *
			from openquery(pgjda, 'select * from {$library}.orapibk WHERE 
			(wgldte = ''{$monYear}'')
			and wdrsrc = ''SID''
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
		update tbl_sid_invoice set Col001 = LTRIM(RTRIM(Col001))
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
			where ORAPROD.INVOICE_NUM in (select distinct(Col001) from tbl_rcr_invoice)
			";
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
	}*/
	
	function viewOlicInv() {
		
		$sql="
		select Col001,Col004,Col006 as Col006 from tbl_sid_invoice group by Col001,Col004,Col006
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrOlicInv = $this->viewOlicInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_sid_amount";
		
		if($this->execQry($truncTable)){
		
			foreach($arrOlicInv as $valOlicInv){
                $sql="
                insert into tbl_ap_invoices_all_sid_amount (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
                select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_AMOUNT
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valOlicInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valOlicInv[Col004]}''
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
		delete from tbl_sid_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_sid_amount)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_sid_invoice set Col040 = 'PJ{$curDate}_{$curTime}.001' where Col011 = 'SID'  
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        
        $sqlPjDup="
        update tbl_sid_invoice set Col040 = 'PJ{$curDate}_duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' 
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";  
        
        $sqlPg="
        update tbl_sid_invoice set Col040 = 'PG{$curDate}_{$curTime}.001' where Col011 = 'SID' 
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mm760lib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900    
        )
        ";
        
        $sqlPgDup="
        update tbl_sid_invoice set Col040 = 'PG{$curDate}_duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' 
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mm760lib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900    
        )
        ";
        
        $sqlPc="
        update tbl_sid_invoice set Col040 = 'PC{$curDate}_{$curTime}.001' where Col011 = 'SID'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlPcDup="
        update tbl_sid_invoice set Col040 = 'PC{$curDate}_duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' 
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlDi="
        update tbl_sid_invoice set Col040 = 'DI{$curDate}_{$curTime}.001' where Col011 = 'SID'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlDiDup="
        update tbl_sid_invoice set Col040 = 'DI{$curDate}_duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' 
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_sid_invoice set Col040 = 'FL{$curDate}_{$curTime}.001' where Col011 = 'SID'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        ";
        
        $sqlFlDup="
        update tbl_sid_invoice set Col040 = 'FL{$curDate}_duplic.001' where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
        select cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) as a from (
        select count(cast(Col001 as nvarchar)+'-'+cast(Col005 as nvarchar)) as duplicate,Col001,Col004 from tbl_sid_invoice
        where Col011 = 'SID' 
        group by Col001,Col004) as doubleInvoice
        where duplicate > 1)
        and Col005 in 
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
                        if($this->execQry($sqlFl)){
                            if($this->execQry($sqlPjDup)){
                                if($this->execQry($sqlPgDup)){
                                    if($this->execQry($sqlPcDup)){
                                        if($this->execQry($sqlDiDup)){
                                             if($this->execQry($sqlFlDup));    
                                        }    
                                    }    
                                }    
                            }
                        }
                    }
                }
            }    
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
	
	function viewOlic($fileName) {
		
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