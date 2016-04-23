<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class oracleDataObj extends commonObj {

	function clearTblStsInvoice(){
		$sql = "
		truncate table tbl_oracle_invoice
		";
		return $this->execQry($sql);
	}
	
	function oraData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40) {

		$sql="
		insert into tbl_oracle_invoice
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
		from         tbl_oracle_invoice
		";
		return $this->getSqlAssoc($this->execQry($sql));
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_oracle_invoice set Col001 = LTRIM(RTRIM(Col001))
		";
		$this->execQry($sql);
	}
	
	function viewOracleDataInv() {
		//Mam mates 
        //select RTV_No as Col001,Vendor_No as Col002 from RTV_FromMamTina2015_JanFeb group by RTV_No,Vendor_No
		$sql="
        select InvNumber as Col001,VenCode as Col002 from [20150826_EFD INVOICE_FOR VERIFICATION_JUNE_2015_2] group by InvNumber,VenCode
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function oracleData() {
		
		$arrStsInv = $this->viewOracleDataInv();
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table EFD_Report_Jessica_Audit";
		
		if($this->execQry($truncTable)){
		
			foreach($arrStsInv as $valConInv){
				$sql="
				insert into EFD_Report_Jessica_Audit (Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009)
				select ORAPROD.INVOICE_NUM,ORAPROD.CHECK_NUMBER,ORAPROD.CHECK_DATE,ORAPROD.AMOUNT_PAID,ORAPROD.SEGMENT1,ORAPROD.DESCRIPTION,ORAPROD.CREATION_DATE,ORAPROD.ORG_ID,ORAPROD.SOURCE from openquery(ORAPROD,
					' select ap_invoices_all.invoice_num,ap_checks_all.check_number,ap_checks_all.check_date,ap_invoices_all.amount_paid,ap_suppliers.SEGMENT1,
                      ap_invoices_all.description,ap_invoices_all.creation_date,ap_invoices_all.org_id,ap_invoices_all.source 
                      from ap_invoices_all 
                      left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                      left join ap_invoice_payments_all on ap_invoices_all.invoice_id = ap_invoice_payments_all.invoice_id
                      left join ap_checks_all on ap_checks_all.check_id = ap_invoice_payments_all.check_id
                      where ap_invoices_all.org_id in (87,85,133,113,154) 
                      and ap_invoices_all.invoice_num  = ''{$valConInv[Col001]}''
                      and ap_suppliers.SEGMENT1 = ''{$valConInv[Col002]}'' 
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
		delete from tbl_oracle_invoice 
		where Col001
		in (select INVOICE_NUM from tbl_ap_invoices_all_oracle_data)
		";
		$this->execQry($sql);
	}
	
	function updFileName() {
		
		$curDate =  date('mdy');
		$curTime =  date('hms');
		
		$sql="
		update tbl_oracle_invoice set Col040 = 'PJ{$curDate}_{$curTime}.401' where Col025 = '700' and Col040 like 'PJ%'
		update tbl_oracle_invoice set Col040 = 'PG{$curDate}_{$curTime}.401' where Col025 in ('101','102','103','104','105') and Col040 like 'PG%
		update tbl_oracle_invoice set Col040 = 'PC{$curDate}_{$curTime}.401' where Col025 = '302' 
		";
		$this->execQry($sql);
	}
	
	function viewFileName() {
		
		$sql="
		SELECT 
		distinct(Col040) as Col040
		FROM tbl_oracle_invoice
		";
		return $this->getArrRes($this->execQry($sql));
	}
	
	function viewSts($fileName) {
		
		$sql="
		SELECT 
		Col001,Col002,Col003,Col004,Col005,Col006,Col007,Col008,Col009,Col010,
		Col011,Col012,Col013,Col014,Col015,Col016,Col017,Col018,Col019,Col020,
		Col021,Col022,Col023,Col024,Col025,Col026,Col027,Col028,Col029,Col030,
		Col031,Col032,Col033,Col034,Col035,Col036,Col037,Col038,Col039,Col040,
		Col041
		FROM tbl_oracle_invoice
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