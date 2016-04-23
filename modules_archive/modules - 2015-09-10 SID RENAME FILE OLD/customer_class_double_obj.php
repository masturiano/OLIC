<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class customerObj extends commonObj {
	
	function mmsData($orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $library = "mmpgtlib";
            $neStartNewNo = "";
        }else if($orgId == '85'){
            $orgName = "PG";
            $library = "mmpgtlib";
            $neStartNewNo = "";
        }else if($orgId == '133'){
            $orgName = "PC";
            $library = "mmpgtlib";
            $neStartNewNo = "";
        }else if($orgId == '153'){
            $orgName = "DI";
            $library = "mmneslib";
            $neStartNewNo = "and customer_number > 600000";
        }else if($orgId == '113'){
            $orgName = "FL";
            $library = "mmneslib";
            $neStartNewNo = "and customer_number > 600000";
        }else{
            $orgName = "";   
        }
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_customer_class";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_customer_class
			select     
            ltrim(rtrim(a.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
            ltrim(rtrim(a.FULL_NAME)) as FULL_NAME,
            b.CUSCLS as CLASS
            from (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMCUS where FULL_NAME not like ''%NTBU%''')) as a
            LEFT JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmpgtlib.ARZMST GROUP BY CUSNUM,CUSCLS') 
            GROUP BY CUSNUM,CUSCLS) as b on a.CUSTOMER_NUMBER = b.CUSNUM
            ORDER BY a.CUSTOMER_NUMBER
			";	
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_customer_class set customer_number = LTRIM(RTRIM(customer_number))
		";
		$this->execQry($sql);
	}   
	
	function viewCustomer() {
		
		$sql="    
        IF OBJECT_ID('tbl_customer_class_double', 'U') IS NOT NULL
        BEGIN
            drop table tbl_customer_class_double

            select 
                customer_number,full_name into tbl_customer_class_double
            from 
                tbl_customer_class
            where 
                customer_number in (select 
                        tbl_customer_class.customer_number
                        from 
                            tbl_customer_class
                        left join 
                            tbl_hz_cust_accounts_class ON tbl_customer_class.customer_number = tbl_hz_cust_accounts_class.account_number
                        where 
                            tbl_customer_class.class <> tbl_hz_cust_accounts_class.class
                        group by tbl_customer_class.customer_number,tbl_customer_class.full_name,tbl_customer_class.class,
                            tbl_hz_cust_accounts_class.class)
            group by 
                customer_number,full_name
            having 
                count(customer_number) > 1
            order by 
                customer_number
        END

        select 
            tbl_customer_class.customer_number,tbl_customer_class.full_name,tbl_customer_class.class
        from 
            tbl_customer_class
        where 
            tbl_customer_class.customer_number in (select customer_number from tbl_customer_class_double)
            and (tbl_customer_class.class <> '' and tbl_customer_class.class is not null)
        order by tbl_customer_class.customer_number
		";
		return $this->getArrRes($this->execQry($sql));
	}
}
?>