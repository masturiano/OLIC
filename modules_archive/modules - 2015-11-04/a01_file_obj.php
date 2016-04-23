<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class a01FileObj extends commonObj {

	function clearTblA01Invoice(){
		$sql = "
		truncate table tbl_a01_invoice
		";
		return $this->execQry($sql);
	}
	
	function a01Data($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18) {

		$sql="
		insert into tbl_a01_invoice
		(invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,Col012,Col013,amount,Col015,curency,col017,filename,col019)
		VALUES('{$col1}','{$col2}','{$col3}','{$col4}','{$col5}','{$col6}','{$col7}','{$col8}','{$col9}','{$col10}','{$col11}','{$col12}','{$col13}','{$col14}','{$col15}','{$col16}','{$col17}','{$col18}',NULL)
		";
		$this->execQry($sql);
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function displayLoadedRec(){
		$sql = "
		select  count(*) as loaded
		from         tbl_a01_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_a01_invoice set invoice = LTRIM(RTRIM(invoice))
		";
		$this->execQry($sql);
	}
	
	function viewA01Inv() {
		
		$sql="
		select distinct(invoice) as invoice from tbl_a01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData($orgId) {
		
		$arrA01Inv = $this->viewA01Inv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_RA_CUSTOMER_TRX_ALL_A01";
		
		if($this->execQry($truncTable)){
		
			foreach($arrA01Inv as $valA01Inv){
				$sql="
				insert into tbl_RA_CUSTOMER_TRX_ALL_A01 (trx_number)
				select ORAPROD.trx_number from openquery(ORAPROD,
				'SELECT RA_CUSTOMER_TRX_ALL.trx_number FROM RA_CUSTOMER_TRX_ALL
				JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
				where RA_CUSTOMER_TRX_ALL.trx_number = ''{$valA01Inv[invoice]}''
                and RA_CUSTOMER_TRX_ALL.org_id = ''{$orgId}'' 
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
		delete from tbl_a01_invoice 
		where invoice
		in (select trx_number from tbl_RA_CUSTOMER_TRX_ALL_A01)
		";
		$this->execQry($sql);
	}
	
	function updFileName($orgId) {
		
		$curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj="
        update tbl_a01_invoice set filename = 'PJ{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from sql_mmpgtlib.dbo.tblstr where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";  
        
        $sqlPg="
        update tbl_a01_invoice set filename = 'PG{$curDate}_{$curTime}.A01' where
        strshrt in 
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
        update tbl_a01_invoice set filename = 'PC{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from sql_mmpgtlib.dbo.tblstr where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ";
        
        $sqlDi="
        update tbl_a01_invoice set filename = 'DI{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from sql_mmneslib.dbo.tblstr where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_a01_invoice set filename = 'FL{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from sql_mmneslib.dbo.tblstr where STCOMP = 811
            and STRNUM < 900
        )
        ";
        
        $sqlDc="
        update tbl_a01_invoice set filename = 'DC{$curDate}_{$curTime}.A01' where filename like 'DC%'
        ";
        
        $sqlDs="
        update tbl_sts_invoice set Col040 = 'DS{$curDate}_{$curTime}.401' where filename like 'DS%'
        ";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        if($this->execQry($sqlPj)){
            if($this->execQry($sqlPg)){
                if($this->execQry($sqlPc)){
                    if($this->execQry($sqlDi)){
                        if($this->execQry($sqlFl)){
                            if($this->execQry($sqlDc)){
                                $this->execQry($sqlDs);    
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
		distinct(filename) as filename
		FROM tbl_a01_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewA01($fileName) {
		
		$sql="
		SELECT 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		FROM tbl_a01_invoice
		where filename = '{$fileName}'
		GROUP BY 
		invoice,invoice_date,trxn_type,strshrt,col005,strshrt2,seq,col008,type,gl_date,
		col011,col012,col013,amount,col015,curency,col017,filename,col019
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function mmsArGetData($mnthYr) {
		$sql="TRUNCATE TABLE ar_tmp_mms";
		$q1 = $this->execQry($sql);
		$this->execQry("SET ANSI_NULLS ON");
		$this->execQry("SET ANSI_WARNINGS ON");
		
		$sql2="INSERT INTO ar_tmp_mms SELECT * FROM openquery(pgjda,'Select * FROM mm760lib.oraribk where WGLDTE LIKE ''%".$mnthYr."'' ')";
		$q2 = $this->execQry($sql2);
		
		if($q2){
			$sql3="INSERT INTO ar_tmp_mms SELECT * FROM openquery(pgjda,'Select * FROM mmneslib.oraribk where WGLDTE LIKE ''%".$mnthYr."'' ')";
			$q3 = $this->execQry($sql3);
		}else{
			return FALSE;
		}
		
		if($q3){
			return TRUE; 
		}else{
			return FALSE;
		}
		
	}
	
	function compareARlocaltoOracle(){
	
		$nqry = "SELECT
					A.WINVNO,
					A.WINVDT,
					A.WPAYTP,
					A.WBRHCD,
					A.WCUSNO,
					A.WCUSCD,
					A.WPYTRM,
					A.WBULIN,
					A.WBTSRC,
					A.WGLDTE,
					A.WSRCRF,
					CASE WHEN A.WLIDSC = ''
						THEN	
							'.'
					ELSE
						A.WLIDSC
					END AS WLIDSC,
					A.WDTQTY,
					A.WDTAMT,
					A.WTXCOD,
					A.WINCUR,
					A.WINVAP,
					A.WFNAME

					FROM
					dbo.ar_tmp_mms AS A
					LEFT JOIN dbo.ra_customer_trx_all AS B ON A.WINVNO = B.TRX_NUMBER AND A.WCUSNO = B.ACCOUNT_NUMBER AND A.WBRHCD = B.LOCATION 
					WHERE B.TRX_NUMBER IS NULL

					ORDER BY A.WFNAME,A.WINVNO";
		$cqry = $this->execQry($nqry);
		
		//UNLINK EXISTING FILE
		$ext = array(1=>'PG',2=>'PJ',3=>'PC',4=>'DI',5=>'FL');
		
		for($i =1; $i <= 5; $i++){
			$unFilen = $ext[$i].date('mdy').'_000000.A01';
			$fromFileName="../exported_file/".$unFilen;
			if(file_exists($fromFileName)){
				unlink($fromFileName);
			}
			
		}
		
		while($x = mssql_fetch_array($cqry))
		{
			
			$month = explode('-',$x[1]);
			$month = strtoupper($month[1]);

			
				$xcontentx .= trim($x['WINVNO'])."|";
				$xcontentx .= trim($x['WINVDT'])."|";
				$xcontentx .= trim($x['WPAYTP'])."|";
				$xcontentx .= trim($x['WBRHCD'])."|";
				$xcontentx .= trim($x['WCUSNO'])."|";
				$xcontentx .= trim($x['WCUSCD'])."|";
				$xcontentx .= trim($x['WPYTRM'])."|";
				$xcontentx .= trim($x['WBULIN'])."|";
				$xcontentx .= trim($x['WBTSRC'])."|";
				$xcontentx .= trim($x['WGLDTE'])."|";
				$xcontentx .= trim($x['WSRCRF'])."|";
				if(trim($x['WLIDSC']) == ""){
					$xcontentx .= ".|";
				}else{
					$xcontentx .= trim($x['WLIDSC'])."|";
				}
				$xcontentx .= trim($x['WDTQTY'])."|";
				$xcontentx .= trim($x['WDTAMT'])."|";
				$xcontentx .= trim($x['WTXCOD'])."|";
				$xcontentx .= trim($x['WINCUR'])."|";
				$xcontentx .= trim($x['WINVAP'])."|";
		
			$filen = trim($x['WFNAME']);
			
			$pref = substr($filen,0,2);
			if($pref == 'PJ'){
				$filen = 'PJ'.date('mdy').'_000000.A01';
			}elseif($pref == 'PC'){
				$filen = 'PC'.date('mdy').'_000000.A01';
			}elseif($pref == 'DI'){
				$filen = 'DI'.date('mdy').'_000000.A01';
			}elseif($pref == 'FL'){
				$filen = 'FL'.date('mdy').'_000000.A01';
			}elseif($pref == 'PG'){
				$filen = 'PG'.date('mdy').'_000000.A01';
			}else{
				$filen = 'PG'.date('mdy').'_000000.A01';
			}
				
				$xcontentx .= $filen."|";
				$xcontentx .= "\r\n";
			
			$fromFileName="../exported_file/".$filen;
			$handleFromFileName = fopen ($fromFileName, "a");
	
			
			fwrite($handleFromFileName, $xcontentx);
			fclose($handleFromFileName) ;
			$xcontentx = "";
		}
		
		return TRUE;
		
	}
	
	function oracleArGetData($frm,$to) {
		$frmD = date('Y-m-d',strtotime($frm));
		$toD = date('Y-m-d',strtotime($to));
		$sql="TRUNCATE TABLE ra_customer_trx_all";
		$q1 = $this->execQry($sql);
		$this->execQry("SET ANSI_NULLS ON");
		$this->execQry("SET ANSI_WARNINGS ON");
		$sql2="
			INSERT INTO ra_customer_trx_all
			SELECT * FROM OPENQUERY(ORAPROD,'

			SELECT DISTINCT 
						RA_CUSTOMER_TRX_ALL.TRX_NUMBER,RA_CUSTOMER_TRX_ALL.TRX_DATE,RA_CUSTOMER_TRX_ALL.CREATION_DATE,RA_CUSTOMER_TRX_ALL.ORG_ID,RA_CUSTOMER_TRX_ALL.INTERFACE_HEADER_CONTEXT,RA_CUSTOMER_TRX_ALL.INVOICE_CURRENCY_CODE,ar_payment_schedules_all.AMOUNT_DUE_ORIGINAL,RA_CUSTOMER_TRX_ALL.ATTRIBUTE11,
					  HZ_CUST_ACCOUNTS.ACCOUNT_NUMBER,
					  HZ_PARTIES.PARTY_NAME,
					  HZ_CUST_SITE_USES_ALL.LOCATION
					
					FROM (((ar_payment_schedules_all
					LEFT JOIN ra_customer_trx_all
					ON ar_payment_schedules_all.CUSTOMER_TRX_ID = ra_customer_trx_all.CUSTOMER_TRX_ID)
					LEFT JOIN ar_cash_receipts_all
					ON ar_payment_schedules_all.CASH_RECEIPT_ID = ar_cash_receipts_all.CASH_RECEIPT_ID)
					LEFT JOIN HZ_CUST_ACCOUNTS
					ON ar_payment_schedules_all.CUSTOMER_ID = HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID)
					LEFT JOIN hz_customer_profiles
					ON HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID               = hz_customer_profiles.CUST_ACCOUNT_ID
					AND ar_payment_schedules_all.CUSTOMER_SITE_USE_ID = hz_customer_profiles.SITE_USE_ID
					LEFT JOIN hz_cust_profile_classes
					ON hz_cust_profile_classes.PROFILE_CLASS_ID = hz_customer_profiles.PROFILE_CLASS_ID
					LEFT JOIN HZ_CUST_SITE_USES_ALL
					ON ar_payment_schedules_all.CUSTOMER_SITE_USE_ID   = HZ_CUST_SITE_USES_ALL.SITE_USE_ID
					LEFT JOIN HZ_PARTIES
					ON  HZ_CUST_ACCOUNTS.PARTY_ID = HZ_PARTIES.PARTY_ID

					LEFT JOIN RA_CUST_TRX_TYPES_all
					ON ra_customer_trx_all.cust_trx_type_id = RA_CUST_TRX_TYPES_all.cust_trx_type_id
					AND ra_customer_trx_all.ORG_ID = RA_CUST_TRX_TYPES_all.ORG_ID
					
					LEFT JOIN AR_RECEIPT_METHOD_ACCOUNTS_ALL
					ON ar_cash_receipts_all.RECEIPT_METHOD_ID = AR_RECEIPT_METHOD_ACCOUNTS_ALL.RECEIPT_METHOD_ID
					AND ar_cash_receipts_all.ORG_ID = AR_RECEIPT_METHOD_ACCOUNTS_ALL.ORG_ID


					LEFT JOIN gl_code_combinations
					ON RA_CUST_TRX_TYPES_all.gl_id_rec = gl_code_combinations.code_combination_id 
					OR AR_RECEIPT_METHOD_ACCOUNTS_ALL.ON_ACCOUNT_CCID = gl_code_combinations.code_combination_id

					
					WHERE 
					ar_payment_schedules_all.ORG_ID              in (85,87,133,153,113)
					AND RA_CUSTOMER_TRX_ALL.CREATION_DATE BETWEEN to_date(''$frmD'') AND to_date(''$toD'') 
			')
		";
		
		$q2 = $this->execQry($sql2);
		
		if($q2){
			return true;
		}else{
			return false;
		}
		
		
	}
	
	
}
?>