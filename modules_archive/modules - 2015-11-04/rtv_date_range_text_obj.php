<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class olicDateRangeObj extends commonObj {
	
	function mmsData($dateFrom,$dateTo,$orgId) {
        
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
        
        $dateProcessCheckRange = strtotime($dateFrom);
        $dateProcessCheckRange2 = strtotime($dateTo);

        $min_date = min($dateProcessCheckRange, $dateProcessCheckRange2);
        $max_date = max($dateProcessCheckRange, $dateProcessCheckRange2);
        $i = 0;
        
        while (($min_date = strtotime("+1 day", $min_date)) <= $max_date) {
            $i++;
        }
        
        $dateProcessStart = $dateFrom;

        $truncTable = "truncate table tbl_rtv_invoice_{$orgId}";
            
        if($this->execQry($truncTable)){    
            for($x=0;$x<=$i;$x++){  
            
                $checkDateProcess = date("mdy", strtotime("$dateProcessStart +{$x} day", time()));
                
                $turnOnAnsiNulls = "SET ANSI_NULLS ON";
                $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";   
                
                $sql="
                insert into tbl_rtv_invoice_{$orgId}
                (Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,Col011,Col012,Col013,Col014,Col015,
                Col016,Col017,Col018,Col019,Col020,Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,Col031,
                Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040)
                select *
                from openquery(pgjda, 'select * from {$library}.orapibk WHERE 
                wfname like ''{$orgName}{$checkDateProcess}%''  
                and wdrsrc = ''RTV''
                ')
                ";    
                $this->execQry($turnOnAnsiNulls);
                $this->execQry($turnOnAnsiWarn);
                $this->execQry($sql);  
            }   
        }
               
	}
	
	function mmsDataRemvSpace($orgId) {
		$sql="
		update tbl_rtv_invoice_{$orgId} set Col001 = LTRIM(RTRIM(Col001))
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
            select STSHRT from sql_mmpgtlib.dbo.tblstr where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";
        
        $sqlPg="
        update tbl_rtv_invoice_{$orgId} set Col040 = 'PG{$curDate}_{$curTime}.901' where Col011 = 'RTV' 
        and Col005 in 
        (
            select STSHRT from sql_mmpgtlib.dbo.tblstr 
            LEFT JOIN
            sql_mmpgtlib.dbo.INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from sql_mmpgtlib.dbo.tblstr  where stcomp = 700
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
            select STSHRT from sql_mmpgtlib.dbo.tblstr where STCOMP = 302
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
            select STSHRT from sql_mmneslib.dbo.tblstr where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_rtv_invoice_{$orgId} set Col040 = 'FL{$curDate}_{$curTime}.901' where Col011 = 'RTV'
        and Col005 in 
        (
            select STSHRT from sql_mmneslib.dbo.tblstr where STCOMP = 811
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
	
	function viewOlic($orgId,$fileName) {
		
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