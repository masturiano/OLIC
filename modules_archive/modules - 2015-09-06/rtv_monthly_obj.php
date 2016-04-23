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
		
		$truncTable = "truncate table tbl_rtv_invoice_{$orgId}";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_rtv_invoice_{$orgId}
			(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
			Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
			Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
			select *
			from openquery(pgjda, 'select * from {$library}.orapibk WHERE 
			(wgldte like ''%$monYear'')
			and wdrsrc = ''RTV''
            and wfname like ''{$orgName}%''
            ')
			";	  
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace($orgId) {
		$sql="
		update tbl_rtv_invoice_{$orgId} set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
    
    function remvLoadedInvFirst($orgId) {
    
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql="
        delete from tbl_rtv_invoice_{$orgId} 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_rtv_amount_{$orgId})
        ";
        $this->execQry($sql);
    }
	
	function viewOlicInv($orgId) {
		
		$sql="
		select Col001,Col004,Col006 as Col006 from tbl_rtv_invoice_{$orgId} group by Col001,Col004,Col006
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId,$clearOra) {
		
		$arrOlicInv = $this->viewOlicInv($orgId);
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";     
        
        if($clearOra == 'false'){
            foreach($arrOlicInv as $valOlicInv){
                $sql="
                    insert into tbl_ap_invoices_all_rtv_amount_{$orgId} (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
                    select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_AMOUNT
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valOlicInv[Col001]}''
                    and ap_invoices_all.org_id = ''{$orgId}'' 
                    and ap_suppliers.SEGMENT1 = ''{$valOlicInv[Col004]}''
                    and ap_invoices_all.INVOICE_AMOUNT = ''{$valOlicInv[Col006]}''
                    and (ap_invoices_all.source = ''RTV'' or ap_invoices_all.source = ''Manual Invoice Entry'')
                    '
                    ) ORAPROD
                    ";
                $this->execQry($turnOnAnsiNulls);
                $this->execQry($turnOnAnsiWarn);
                $this->execQry($sql);
            } 
        }else{  
            $truncTable = "truncate table tbl_ap_invoices_all_rtv_amount_{$orgId}";
            
            if($this->execQry($truncTable)){
            
                foreach($arrOlicInv as $valOlicInv){
                    $sql="
                    insert into tbl_ap_invoices_all_rtv_amount_{$orgId} (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
                    select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
                        'select 
                        ap_invoices_all.INVOICE_NUM,
                        ap_suppliers.SEGMENT1,
                        ap_invoices_all.INVOICE_AMOUNT
                        FROM ap_invoices_all
                        left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                        where ap_invoices_all.INVOICE_NUM = ''{$valOlicInv[Col001]}''
                        and ap_invoices_all.org_id = ''{$orgId}''
                        and ap_invoices_all.INVOICE_AMOUNT = ''{$valOlicInv[Col006]}''
                        and (ap_invoices_all.source = ''RTV'' or ap_invoices_all.source = ''Manual Invoice Entry'') 
                        '
                        ) ORAPROD
                    ";
                    $this->execQry($turnOnAnsiNulls);
                    $this->execQry($turnOnAnsiWarn);
                    $this->execQry($sql);
                }
            }    
        }   
	}
	
	function remvLoadedInv($orgId) {
	
		$curDate =  date('mdy');
		$curTime =  date('his');
		
		$sql="
        delete from tbl_rtv_invoice_{$orgId} 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_rtv_amount_{$orgId})
        ";
        $this->execQry($sql);
	}
	
	function updFileName($orgId) {
		
		$curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_rtv_invoice_{$orgId} set Col040 = 'PJ{$curDate}_{$curTime}.901' where Col011 = 'RTV'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        
        $sqlPg="
        update tbl_rtv_invoice_{$orgId} set Col040 = 'PG{$curDate}_{$curTime}.901' where Col011 = 'RTV' 
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
        update tbl_rtv_invoice_{$orgId} set Col040 = 'PC{$curDate}_{$curTime}.901' where Col011 = 'RTV'
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
        update tbl_rtv_invoice_{$orgId} set Col040 = 'DI{$curDate}_{$curTime}.901' where Col011 = 'RTV'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_rtv_invoice_{$orgId} set Col040 = 'FL{$curDate}_{$curTime}.901' where Col011 = 'RTV'
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
                        if($this->execQry($sqlFl));
                    }
                }
            }    
        }
	}
	
	function viewFileName($orgId) {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_rtv_invoice_{$orgId}
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewOlic($fileName,$orgId) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_rtv_invoice_{$orgId}
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