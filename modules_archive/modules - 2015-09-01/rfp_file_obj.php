<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class olicDailyObj extends commonObj {

	function clearTblOlicInvoice(){
		$sql = "
		truncate table tbl_rfp_invoice
		";
		return $this->execQry($sql);
	}
	
	function olicData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40) {

		$sql="
		insert into tbl_rfp_invoice
		(Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
		Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
		Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
		VALUES('{$col1}','{$col2}','{$col3}','{$col4}','{$col5}','{$col6}','".trim(str_replace("'","",$col7))."','{$col8}','{$col9}','{$col10}','{$col11}','{$col12}','{$col13}','{$col14}','{$col15}','{$col16}','{$col17}','{$col18}','{$col19}','{$col20}','{$col21}','{$col22}','{$col23}','{$col24}','{$col25}','{$col26}','{$col27}','{$col28}','{$col29}','{$col30}','{$col31}','{$col32}','{$col33}','{$col34}','{$col35}','{$col36}','{$col37}','{$col38}','".trim(str_replace("'","",$col039))."','{$col40}')
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
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_rfp_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	function viewOlicInv() {
		
		$sql="
		select Col001,Col004,Col013 as Col013 from tbl_rfp_invoice group by Col001,Col004,Col013
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrOlicInv = $this->viewOlicInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_invoices_all_rfp";
		
		if($this->execQry($truncTable)){
		
			foreach($arrOlicInv as $valOlicInv){
				$sql="
                insert into tbl_ap_invoices_all_rfp (INVOICE_NUM,SEGMENT1)
                select ORAPROD.INVOICE_NUM,ORAPROD.SEGMENT1 from openquery(ORAPROD,
                    'select 
                    ap_invoices_all.INVOICE_NUM,
                    ap_suppliers.SEGMENT1
                    FROM ap_invoices_all
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    where ap_invoices_all.INVOICE_NUM = ''{$valOlicInv[Col001]}''
                    and ap_suppliers.SEGMENT1 = ''{$valOlicInv[Col004]}''
                    and ap_invoices_all.org_id = ''{$orgId}'' 
                    and (ap_invoices_all.source = ''RFP'' or ap_invoices_all.source = ''Manual Invoice Entry'') 
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
        delete from tbl_rfp_invoice 
        where cast(Col001 as nvarchar)+'-'+cast(Col004 as nvarchar)
        in (select cast(INVOICE_NUM as nvarchar)+'-'+cast(SEGMENT1 as nvarchar) from tbl_ap_invoices_all_rfp)
        ";
		$this->execQry($sql);
	}
    
    function viewStoreShortPj() {
        
        $sql="
        SELECT  distinct Col016 as shortName
        FROM tbl_rfp_invoice
        WHERE Col016 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewStoreShortPg() {
        
        $sql="
        SELECT  distinct Col016 as shortName
        FROM tbl_rfp_invoice
        WHERE Col016 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from MM760LIB.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900    
        )
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewStoreShortPc() {
        
        $sql="
        SELECT  distinct Col016 as shortName
        FROM tbl_rfp_invoice
        WHERE Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewStoreShortDi() {
        
        $sql="
        SELECT  distinct Col016 as shortName
        FROM tbl_rfp_invoice
        WHERE Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewStoreShortFl() {
        
        $sql="
        SELECT  distinct Col016 as shortName
        FROM tbl_rfp_invoice
        WHERE Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function updBl($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $storeShortPj = $this->viewStoreShortPj();
        $storeShortPg = $this->viewStoreShortPg();
        $storeShortPc = $this->viewStoreShortPc();
        $storeShortDi = $this->viewStoreShortDi();
        $storeShortFl = $this->viewStoreShortFl();
        
        foreach($storeShortPj as $valStoreShortPj){
            $sqlPj="
            update tbl_rfp_invoice SET Col017 =
            (select CONCAT('00',INVADIx1.ADINUM) from openquery(pgjda, 'select * from MM760LIB.TBLSTR where STSHRT = ''{$valStoreShortPj['shortName']}''') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM)
            ";       
            
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $this->execQry($sqlPj);
                
        }  
        
        foreach($storeShortPg as $valStoreShortPg){
            $sqlPg="
            update tbl_rfp_invoice SET Col017 =
            (select CONCAT('00',INVADIx1.ADINUM) from openquery(pgjda, 'select * from MM760LIB.TBLSTR where STSHRT = ''{$valStoreShortPg['shortName']}''') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM)
            ";   
            
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $this->execQry($sqlPg);
                
        }  
        
        foreach($storeShortPc as $valStoreShortPc){
            $sqlPc="
            update tbl_rfp_invoice SET Col017 =  
            (select CONCAT('0',INVADIx1.ADINUM) from openquery(pgjda, 'select * from MM760LIB.TBLSTR where STSHRT = ''{$valStoreShortPc['shortName']}''') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM)
            ";
            
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $this->execQry($sqlPc);
                
        }  
        
        foreach($storeShortDi as $valStoreShortDi){
            $sqlDi="
            update tbl_rfp_invoice SET Col017 = 
            (select CONCAT('00',INVADIx1.ADINUM) from openquery(pgjda, 'select * from MM760LIB.TBLSTR where STSHRT = ''{$valStoreShortDi['shortName']}''') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM)
            ";
            
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $this->execQry($sqlDi);    
        }   
            
        foreach($storeShortFl as $valStoreShortFl){
            $sqlFl="
            update tbl_rfp_invoice SET Col017 = 
            (select CONCAT('00',INVADIx1.ADINUM) from openquery(pgjda, 'select * from MM760LIB.TBLSTR where STSHRT = ''{$valStoreShortFl['shortName']}''') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM)
            ";     
            
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $this->execQry($sqlFl);    
        }         
    }
	
	function updFileName($orgId) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_rfp_invoice set Col040 = 'PJ{$curDate}_{$curTime}.G01' where Col011 = 'RFP'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        
        $sqlPg="
        update tbl_rfp_invoice set Col040 = 'PG{$curDate}_{$curTime}.G01' where Col011 = 'RFP' 
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from MM760LIB.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from MM760LIB.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900    
        )
        ";
        
        $sqlPc="
        update tbl_rfp_invoice set Col040 = 'PC{$curDate}_{$curTime}.G01' where Col011 = 'RFP'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from MM760LIB.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlDi="
        update tbl_rfp_invoice set Col040 = 'DI{$curDate}_{$curTime}.G01' where Col011 = 'RFP'
        and Col005 in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_rfp_invoice set Col040 = 'FL{$curDate}_{$curTime}.G01' where Col011 = 'RFP'
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
    
    function viewFileName() {
        
        $sql="
        SELECT 
        distinct(Col040) as Col040
        FROM tbl_rfp_invoice
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