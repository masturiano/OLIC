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
	
	function oracleData($orgId) {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_hz_cust_accounts_class";
		
		if($this->execQry($truncTable)){

			$sql="
			insert into tbl_hz_cust_accounts_class (account_number,party_name,class)
			select ORAPROD.account_number,ORAPROD.party_name,ORAPROD.class from openquery(ORAPROD,
				'
                SELECT
                    CUST.PARTY_NAME,
                    CUST_ACCT.ACCOUNT_NUMBER,
                    CPCLASS.NAME as CLASS
                FROM HZ_PARTIES CUST,
                    HZ_CUST_ACCOUNTS CUST_ACCT,
                    HZ_CUST_ACCT_SITES_ALL CUST_SITE,
                    HZ_CUST_SITE_USES_ALL CUST_USES,
                    HZ_LOCATIONS CUST_LOC,
                    hz_customer_profiles CPROF,
                    hz_cust_profile_classes CPCLASS
                WHERE CUST_ACCT.CUST_ACCOUNT_ID = CUST_SITE.CUST_ACCOUNT_ID
                    AND CUST_SITE.CUST_ACCT_SITE_ID = CUST_USES.CUST_ACCT_SITE_ID
                    AND CUST_USES.CUST_ACCT_SITE_ID = CUST_LOC.LOCATION_ID(+)
                    AND CUST.PARTY_ID               = CUST_ACCT.PARTY_ID
                    AND CUST_ACCT.CUST_ACCOUNT_ID   = CPROF.CUST_ACCOUNT_ID
                    AND CPROF.PROFILE_CLASS_ID      = CPCLASS.PROFILE_CLASS_ID
                    AND CUST_USES.SITE_USE_ID       = CPROF.SITE_USE_ID
                    AND CUST_SITE.ORG_ID = ''$orgId''
                GROUP BY 
                    CUST.PARTY_NAME,
                    CUST_ACCT.ACCOUNT_NUMBER,
                    CPCLASS.NAME
                ORDER BY 
                    CUST_ACCT.ACCOUNT_NUMBER
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
        IF OBJECT_ID('tbl_customer_class_single', 'U') IS NOT NULL
        BEGIN
            drop table tbl_customer_class_single

            select 
                customer_number,full_name into tbl_customer_class_single
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
                count(customer_number) < 2
            order by 
                customer_number
        END

        select 
            tbl_customer_class.customer_number,tbl_customer_class.full_name,tbl_customer_class.class,
            tbl_hz_cust_accounts_class.class as class_ora
        from 
            tbl_customer_class
        left join 
            tbl_hz_cust_accounts_class ON tbl_customer_class.customer_number = tbl_hz_cust_accounts_class.account_number
        where 
            tbl_customer_class.class <> tbl_hz_cust_accounts_class.class
            and tbl_customer_class.customer_number in (select customer_number from tbl_customer_class_single)
            and (tbl_customer_class.class <> '' and tbl_customer_class.class is not null)
        order by tbl_customer_class.customer_number
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
        ltrim(rtrim(c.FULL_NAME)) as FULL_NAME,
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
        from openquery(pgjda, 'select * from mmpgtlib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mmpgtlib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmpgtlib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
                select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
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
        ltrim(rtrim(c.FULL_NAME)) as FULL_NAME,
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
        from openquery(pgjda, 'select * from mmpgtlib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mmpgtlib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmpgtlib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
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
        ltrim(rtrim(c.FULL_NAME)) as FULL_NAME,
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
        from openquery(pgjda, 'select * from mmpgtlib.INVADIx1') as a JOIN (SELECT * FROM openquery(pgjda,'SELECT * FROM mmpgtlib.tblstr')) as b ON
        a.strnum = b.strnum
        CROSS JOIN (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMCUS')) as c
        JOIN (select CUSNUM,CUSCLS FROM openquery(pgjda,'select CUSNUM,CUSCLS from mmpgtlib.ARZMST GROUP BY CUSNUM,CUSCLS') GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
        left join (SELECT * FROM openquery(pgjda, 'select * from mmpgtlib.CIMMBR')) as t on t.mbrnum = c.customer_number
        WHERE 
        c.CUSTOMER_NUMBER = '{$cusnum}'
        AND b.STSHRT in 
        (
            select STSHRT from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
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
        ltrim(rtrim(c.FULL_NAME)) as FULL_NAME,
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
        ltrim(rtrim(c.FULL_NAME)) as FULL_NAME,
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