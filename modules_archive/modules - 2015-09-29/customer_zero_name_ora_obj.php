<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class customerObj extends commonObj {
	
	function oracleData() {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $library = "mm760lib";
            $neStartNewNo = "";
        }else if($orgId == '85'){
            $orgName = "PG";
            $library = "mm760lib";
            $neStartNewNo = "";
        }else if($orgId == '133'){
            $orgName = "PC";
            $library = "mm760lib";
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
		
		$truncTable = "truncate table tbl_hz_cust_accounts_zero_name";
		
		if($this->execQry($truncTable)){

			$sql="
			insert into tbl_hz_cust_accounts_zero_name (account_number,party_name)
			select ORAPROD.account_number,ORAPROD.party_name from openquery(ORAPROD,
				'
                SELECT 
                CUST_ACCT.ACCOUNT_NUMBER,
                CUST.PARTY_NAME
                FROM HZ_PARTIES CUST,
                HZ_CUST_ACCOUNTS CUST_ACCT
                WHERE 
                CUST.PARTY_ID = CUST_ACCT.PARTY_ID
                AND CUST.PARTY_NAME like ''0 %''
                
                '
				) ORAPROD
			";
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);

		}
	}       
	
	function viewCustomer() {
		
		$sql="    
        select 
            account_number,party_name 
        from 
            tbl_hz_cust_accounts_zero_name
        where 
            party_name like '0 %'
            and party_name not like '%NTBU%'
		";
		return $this->getArrRes($this->execQry($sql));
	}
    
    function viewDataPj($cusnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO')";
        $forSetup = "AND b.STSHRT not in ('DEPPC','VILPC')"; 
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
        select     
        ltrim(rtrim(c.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
        ltrim(rtrim(b.STSHRT)) as STSHRT,
        SUBSTRING(ltrim(rtrim(c.FULL_NAME)), 3, 500) as FULL_NAME,
        CASE WHEN rtrim(ISNULL(c.ADDRESS_LINE_1,'.')) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(c.ADDRESS_LINE_1,'.'))) END as ADDRESS1,
        '' as ADDRESS2,
        '' as ADDRESS3,
        '' as ADDRESS4,
        ltrim(rtrim(c.CITY_NAME)) as CITY,
        'PH' as COUNTRY,
        c.POSTAL_ZIP_CODE as ZIP_CODE,
        d.CUSCLS as CLASS,
        cast(b.STCOMP as varchar) as STCOMP,
        'Bill_To' as BILLTO,
        '' as CUSVATCODE,
        '' as SITEVATCODE,
        case when t.mbrtin is null then '.' when t.mbrtin = '' then '.' when t.mbrtin is not null then t.mbrtin end as TIN,
        '00'+ltrim(rtrim(a.ADINUM)) as BL, 
        cast('PJ{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
        from openquery(pgjda, 'select * from mm760lib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mm760lib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mm760lib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
                select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
        )
        $exclude
        $forSetup
        ORDER BY b.STSHRT
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPg($cusnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO')";
        $forSetup = "AND b.STSHRT not in ('DEPPC','VILPC')"; 
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
        select     
        ltrim(rtrim(c.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
        ltrim(rtrim(b.STSHRT)) as STSHRT,
        SUBSTRING(ltrim(rtrim(c.FULL_NAME)), 3, 500) as FULL_NAME,
        CASE WHEN rtrim(ISNULL(c.ADDRESS_LINE_1,'.')) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(c.ADDRESS_LINE_1,'.'))) END as ADDRESS1,
        '' as ADDRESS2,
        '' as ADDRESS3,
        '' as ADDRESS4,
        ltrim(rtrim(c.CITY_NAME)) as CITY,
        'PH' as COUNTRY,
        c.POSTAL_ZIP_CODE as ZIP_CODE,
        d.CUSCLS as CLASS,
        cast(b.STCOMP as varchar) as STCOMP,
        'Bill_To' as BILLTO,
        '' as CUSVATCODE,
        '' as SITEVATCODE,
        case when t.mbrtin is null then '.' when t.mbrtin = '' then '.' when t.mbrtin is not null then t.mbrtin end as TIN,
        '00'+ltrim(rtrim(a.ADINUM)) as BL, 
        cast('PG{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
        from openquery(pgjda, 'select * from mm760lib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mm760lib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mm760lib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
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
        $exclude
        $forSetup
        ORDER BY b.STSHRT
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPc($cusnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
        select     
        ltrim(rtrim(c.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
        ltrim(rtrim(b.STSHRT)) as STSHRT,
        SUBSTRING(ltrim(rtrim(c.FULL_NAME)), 3, 500) as FULL_NAME,
        CASE WHEN rtrim(ISNULL(c.ADDRESS_LINE_1,'.')) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(c.ADDRESS_LINE_1,'.'))) END as ADDRESS1,
        '' as ADDRESS2,
        '' as ADDRESS3,
        '' as ADDRESS4,
        ltrim(rtrim(c.CITY_NAME)) as CITY,
        'PH' as COUNTRY,
        c.POSTAL_ZIP_CODE as ZIP_CODE,
        d.CUSCLS as CLASS,
        cast(b.STCOMP as varchar) as STCOMP,
        'Bill_To' as BILLTO,
        '' as CUSVATCODE,
        '' as SITEVATCODE,
        case when t.mbrtin is null then '.' when t.mbrtin = '' then '.' when t.mbrtin is not null then t.mbrtin end as TIN,
        '0'+ltrim(rtrim(a.ADINUM)) as BL, 
        cast('PC{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
        from openquery(pgjda, 'select * from mm760lib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mm760lib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mm760lib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mm760lib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
            select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO'
        )
        ORDER BY b.STSHRT
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataDi($cusnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
        select     
        ltrim(rtrim(c.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
        ltrim(rtrim(b.STSHRT)) as STSHRT,
        SUBSTRING(ltrim(rtrim(c.FULL_NAME)), 3, 500) as FULL_NAME,
        CASE WHEN rtrim(ISNULL(c.ADDRESS_LINE_1,'.')) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(c.ADDRESS_LINE_1,'.'))) END as ADDRESS1,
        '' as ADDRESS2,
        '' as ADDRESS3,
        '' as ADDRESS4,
        ltrim(rtrim(c.CITY_NAME)) as CITY,
        'PH' as COUNTRY,
        c.POSTAL_ZIP_CODE as ZIP_CODE,
        d.CUSCLS as CLASS,
        cast(b.STCOMP as varchar) as STCOMP,
        'Bill_To' as BILLTO,
        '' as CUSVATCODE,
        '' as SITEVATCODE,
        case when t.mbrtin is null then '.' when t.mbrtin = '' then '.' when t.mbrtin is not null then t.mbrtin end as TIN,
        '00'+ltrim(rtrim(a.ADINUM)) as BL, 
        cast('DI{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
        from openquery(pgjda, 'select * from mmneslib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mmneslib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mmneslib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmneslib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mmneslib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
        )
        ORDER BY b.STSHRT
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataFl($cusnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
        select     
        ltrim(rtrim(c.CUSTOMER_NUMBER)) as CUSTOMER_NUMBER,
        ltrim(rtrim(b.STSHRT)) as STSHRT,
        SUBSTRING(ltrim(rtrim(c.FULL_NAME)), 3, 500) as FULL_NAME,
        CASE WHEN rtrim(ISNULL(c.ADDRESS_LINE_1,'.')) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(c.ADDRESS_LINE_1,'.'))) END as ADDRESS1,
        '' as ADDRESS2,
        '' as ADDRESS3,
        '' as ADDRESS4,
        ltrim(rtrim(c.CITY_NAME)) as CITY,
        'PH' as COUNTRY,
        c.POSTAL_ZIP_CODE as ZIP_CODE,
        d.CUSCLS as CLASS,
        cast(b.STCOMP as varchar) as STCOMP,
        'Bill_To' as BILLTO,
        '' as CUSVATCODE,
        '' as SITEVATCODE,
        case when t.mbrtin is null then '.' when t.mbrtin = '' then '.' when t.mbrtin is not null then t.mbrtin end as TIN,
        '00'+ltrim(rtrim(a.ADINUM)) as BL, 
        cast('FL{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
        from openquery(pgjda, 'select * from mmneslib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mmneslib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mmneslib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmneslib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mmneslib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
        )
        ORDER BY b.STSHRT
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
}
?>