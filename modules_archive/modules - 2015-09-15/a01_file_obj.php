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
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
        )
        ";  
        
        $sqlPg="
        update tbl_a01_invoice set filename = 'PG{$curDate}_{$curTime}.A01' where
        strshrt in 
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
        update tbl_a01_invoice set filename = 'PC{$curDate}_{$curTime}.A01' where
        strshrt in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
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
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ";
        
        $sqlFl="
        update tbl_a01_invoice set filename = 'FL{$curDate}_{$curTime}.A01' where
        strshrt in 
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
	
	function mmsArGetData() {
		$sql="TRUNCATE TABLE ra_customer_trx_all";
		$q1 = $this->execQry($sql);
		
		$sql="INSERT INTO ra_customer_trx_all
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
						AND RA_CUSTOMER_TRX_ALL.CREATION_DATE > to_date(''2015-07-25'') 
				')";
		
		
	}
	
	
}
?>