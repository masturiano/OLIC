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
	
	function olicData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40) {

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
		select Col001,Col004,Col013 as Col013 from tbl_rcr_invoice group by Col001,Col004,Col013
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrRcrInv = $this->viewRcrInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_rcr_amount";
		
		if($this->execQry($truncTable)){
		
			foreach($arrRcrInv as $valRcrInv){
				$sql="
				insert into tbl_ap_invoices_all_rcr_amount (INVOICE_NUM,SEGMENT1,INVOICE_AMOUNT)
				select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1,ORAPROD.INVOICE_AMOUNT from openquery(ORAPROD,
					'select 
					ap_invoices_all.INVOICE_NUM,
					ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_AMOUNT
					FROM ap_invoices_all
					left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valRcrInv[Col001]}'' 
                    and ap_suppliers.SEGMENT1 = ''{$valRcrInv[Col004]}'' 
                    and ap_invoices_all.org_id = ''{$orgId}'' 
                    and (ap_invoices_all.source = ''PO'' or ap_invoices_all.source = ''Manual Invoice Entry'')
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
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)+'='+cast(Col006 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar)+'='+cast(INVOICE_AMOUNT as nvarchar) from tbl_ap_invoices_all_rcr_amount
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_rcr_invoice set Col040 = 'PJ{$curDate}_duplicate.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        
        $sqlPg="
        update tbl_rcr_invoice set Col040 = 'PG{$curDate}_duplicate.301' where Col011 = 'PO' 
        and Col005 in 
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
        update tbl_rcr_invoice set Col040 = 'PC{$curDate}_duplicate.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlDi="
        update tbl_rcr_invoice set Col040 = 'DI{$curDate}_duplicate.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_rcr_invoice set Col040 = 'FL{$curDate}_duplicate.301' where Col011 = 'PO'
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
    
    function updFileNameCancelled($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_rcr_invoice set Col040 = 'PJ{$curDate}_cancelled.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.invoice_amount = '0'
        )
        ";
        
        $sqlPg="
        update tbl_rcr_invoice set Col040 = 'PG{$curDate}_cancelled.301' where Col011 = 'PO' 
        and Col005 in 
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
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.invoice_amount = '0'
        )
        ";
        
        $sqlPc="
        update tbl_rcr_invoice set Col040 = 'PC{$curDate}_cancelled.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.invoice_amount = '0'
        )
        ";
        
        $sqlDi="
        update tbl_rcr_invoice set Col040 = 'DI{$curDate}_cancelled.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.invoice_amount = '0'
        )
        ";
        
        $sqlFl="
        update tbl_rcr_invoice set Col040 = 'FL{$curDate}_cancelled.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.invoice_amount = '0'
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
    
    function updFileNameLoad($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_rcr_invoice set Col040 = 'PJ{$curDate}_{$curTime}.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.INVOICE_NUM is null and b.SEGMENT1 is null and b.INVOICE_AMOUNT is null
        )
        ";
        
        $sqlPg="
        update tbl_rcr_invoice set Col040 = 'PG{$curDate}_{$curTime}.301' where Col011 = 'PO' 
        and Col005 in 
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
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.INVOICE_NUM is null and b.SEGMENT1 is null and b.INVOICE_AMOUNT is null
        )
        ";
        
        $sqlPc="
        update tbl_rcr_invoice set Col040 = 'PC{$curDate}_{$curTime}.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.INVOICE_NUM is null and b.SEGMENT1 is null and b.INVOICE_AMOUNT is null
        )
        ";
        
        $sqlDi="
        update tbl_rcr_invoice set Col040 = 'DI{$curDate}_{$curTime}.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.INVOICE_NUM is null and b.SEGMENT1 is null and b.INVOICE_AMOUNT is null
        )
        ";
        
        $sqlFl="
        update tbl_rcr_invoice set Col040 = 'FL{$curDate}_{$curTime}.301' where Col011 = 'PO'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        and cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar) in (
            select cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) from tbl_rcr_invoice a
            left join tbl_ap_invoices_all_rcr_amount b
            on 
            cast(a.Col001 as nvarchar)+'-'+cast(a.Col004 as nvarchar) = cast(b.INVOICE_NUM as nvarchar)+'-'+cast(b.SEGMENT1 as nvarchar)
            where b.INVOICE_NUM is null and b.SEGMENT1 is null and b.INVOICE_AMOUNT is null
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