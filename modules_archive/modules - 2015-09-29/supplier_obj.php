<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class supplierObj extends commonObj {
	
	function mmsData($orgId) {
        
        if($orgId == '87'){
            $orgName = "PJ";
            $library = "mm760lib";
            $neStartNewNo = "";
            $headerStcomp = "700";
            $headerBl = "005";
        }else if($orgId == '85'){
            $orgName = "PG";
            $library = "mm760lib";
            $neStartNewNo = "";
            $headerStcomp = "101";
            $headerBl = "004";
        }else if($orgId == '133'){
            $orgName = "PC";
            $library = "mm760lib";
            $neStartNewNo = "";
            $headerStcomp = "302";
            $headerBl = "010";
        }else if($orgId == '153'){                                                                                          
            $orgName = "DI";
            $library = "mmneslib";
            $neStartNewNo = "and asnum > 60000";
            $headerStcomp = "810";
            $headerBl = "008";
        }else if($orgId == '113'){
            $orgName = "FL";
            $library = "mmneslib";
            $neStartNewNo = "and asnum > 60000";
            $headerStcomp = "811";
            $headerBl = "008";
        }else{
            $orgName = "";   
        }
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_supplier";
		
		if($this->execQry($truncTable)){
			$sql="
			insert into tbl_supplier
			(asname,asnum)
			select asname,asnum from openquery(pgjda, 'select asname,asnum from {$library}.apsupp') 
            where asname not like '%(NTBU)%' 
            and asname <> 'NOT TO BE USED'
            $neStartNewNo
            order by asnum
			";	
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);
		}
		//exec UPDATE_RCR_INVOICE2 $monYear \n";
	}
	
	function mmsDataRemvSpace() {
		$sql="
		update tbl_supplier set asnum = LTRIM(RTRIM(asnum))
		";
		$this->execQry($sql);
	}
	
	function oracleData($orgId) {
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
		
		$truncTable = "truncate table tbl_ap_suppliers";
		
		if($this->execQry($truncTable)){

			$sql="
			insert into tbl_ap_suppliers (SEGMENT1,VENDOR_NAME)
			select ORAPROD.segment1,ORAPROD.vendor_name from openquery(ORAPROD,
				'
                select distinct ap_suppliers.segment1,ap_suppliers.vendor_name from ap_suppliers
                left join ap_supplier_sites_all on ap_suppliers.vendor_id = ap_supplier_sites_all.vendor_id
                where ap_supplier_sites_all.org_id = ''$orgId''
                '
				) ORAPROD
			";
			$this->execQry($turnOnAnsiNulls);
			$this->execQry($turnOnAnsiWarn);
			$this->execQry($sql);

		}
	}
	
	function viewSupplier() {
		
		$sql="
		select 
            asnum,asname
		from 
            tbl_supplier
		left join 
            tbl_ap_suppliers ON tbl_supplier.asnum = tbl_ap_suppliers.SEGMENT1
		where 
		    tbl_ap_suppliers.SEGMENT1 is null
		    and tbl_supplier.asnum not in ('16112','16314','16328','16593','17200','17494','17537','17564','17575','17584','17599','17810','17863','18339','18419')
            and asname not like '%ntbu%';
		";
		return $this->getArrRes($this->execQry($sql));
	}
    
    function viewDataPj($supnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $exclude = "AND TBLSTR.STSHRT not in ('SHAWDC','HO')";
        $forSetup = "AND TBLSTR.STSHRT not in ('DEPPC','VILPC')"; 
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
            SELECT    
            APSUPP.ASNAME --Line1
            +
            CASE WHEN APSUPP.ASTYPE = '1' THEN ' {TRADE}' ELSE ' {NON TRADE}' END as ASNAME, --Line1
            cast(APSUPP.ASNUM as varchar) as ASNUM, --Line2
            APSUPP.ASTYPE as ASTYPE2, --Line3
            cast(APSUPP.ASTRMS as varchar) as ASTRMS, --Line4
            '' as LOOKUP_CODE, --Line5
            APSUPP.ASCURC as ASCURC, --Line6
            APSUPP.ASCURC as ASCURC2, --Line7
            cast(TXDFT.TXCOD as varchar) as TXCOD, --Line8
            '' as HDR_TERMS_DATE_BASIS, -- Line9
            '' as CALC_FLAG, --Line10
            '' as TAX_FLAG, --Line11
            '' as AWT_FLAG,--Line12
            '' as GROUP_NAME, --Line13
            'CHECK' as METHOD_CODE, --Line14
            '700' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '005' as ADINUM, --Line17
            '' as CODE_BUSINESS, --Line18
            '' as CODE_DEPARTMENT, --Line19
            '' as CODE_SECTION, --Line20
            '' as CODE_ACCOUNT, --Line21
            TBLSTR.STSHRT as STSHRT2, --Line22
            'Y' as PAY_SITE_FLAG, --Line23
            APADDR.AAADD1 as ADDRESS_LINE1, --Line24
            APADDR.AAADD2 as ADDRESS_LINE2, --Line25
            '' as ADDRESS_LINE3, --Line26
            'PH' as DTL_COUNTRY, --Line27
            '' as DTL_PHONE_AREA_CODE, --Line28
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))),8) END as DTL_PHONE_NUMBER, --Line29
            '' as DTL_FAX_AREA_CODE, --Line30
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))),8) END as DTL_FAX_NUMBER, --Line31
            '' as DTL_FAX_NUMBER, --Line32
            cast(TXDFT.TXCOD as varchar) as DTL_VAT_CODE, --Line33
            cast(APSUPP.ASTRMS as varchar) as DTL_TERMS_NAME, --Line34
            '' as DTL_PAY_DATE_BASIS_LOOKUP_CODE, --Line35
            APSUPP.ASCURC as DTL_INVOICE_CURR_CODE, --Line36
            APSUPP.ASCURC as DTL_PAYMENT_CURR_CODE, --Line37
            'Y' as DTL_AUTO_TAX_CALC_FLAG, --Line38
            '' as DTL_AMOUNT_INCLUDES_TAX_FLAG, --Line39
            '' as DTL_PRIMARY_PAY_SITE_FLAG, --Line40
            '' as DTL_PAYMENT_METHOD_CODE, --Line41
            '' as DTl_ALLOW_AWT_FLAG, --Line42
            '' as DTL_AWT_GROUP_NAME, --Line43
            '' as DTL_EMAIL_ADDRESS, --Line44
            '' as DTL_ACCTS_PAY_CODE_COMPANY,--Line45
            '' as DTL_ACCTS_PAY_CODE_LOCATION, --Line46
            '00'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
            '' as DTL_ACCTS_PAY_CODE_BUSINESS, --Line48
            '' as DTL_ACCTS_PAY_CODE_DEPARTMENT, --Line49
            '' as DTL_ACCTS_PAY_CODE_SECTION, --Line50
            '' as DTL_ACCTS_PAY_CODE_ACCOUNT, --Line51
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) END as DTL2_CONT_FIRST_NAME, --Line52
            '.' as DTL2_CONT_MIDDLE_NAME, --Line53
            '.' as DTL2_CONT_LAST_NAME, --Line54
            '.' as DTL2_CONT_PREFIX, --Line55
            '' as DTL2_CONT_TITLE, --Line56
            '' as DTL2_CONT_PHONE_AREA_CODE, --Line57
            '' as DTL2_CONT_PHONE_NUMBER, --Line58
            '' as DTL2_CONT_FAX_AREA_CODE, --Line59
            '' as DTL2_CONT_FAX_NUMBER, --Line60
            '.' as POSTAL_CODE, --Line61
            '.' as CITY, --Line62
            '.' as COUNTY, --Line63
            '.' as STATE,     --Line64
            CASE WHEN ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) END as ASGSTN,  --Line65
            '' as LINE66,  --Line66
            '0' as LINE67, --Line67
            '' as LINE68, --Line68
            '0' as LINE69, --Line69
            cast('PJ{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mm760lib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            apsupp.ASNUM = '{$supnum}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            AND TBLSTR.STSHRT in (
                select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            $exclude
            $forSetup
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPg($supnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $exclude = "AND TBLSTR.STSHRT not in ('SHAWDC','HO')";
        $forSetup = "AND TBLSTR.STSHRT not in ('DEPPC','VILPC')"; 
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
            SELECT    
            APSUPP.ASNAME --Line1
            +
            CASE WHEN APSUPP.ASTYPE = '1' THEN ' {TRADE}' ELSE ' {NON TRADE}' END as ASNAME, --Line1
            cast(APSUPP.ASNUM as varchar) as ASNUM, --Line2
            APSUPP.ASTYPE as ASTYPE2, --Line3
            cast(APSUPP.ASTRMS as varchar) as ASTRMS, --Line4
            '' as LOOKUP_CODE, --Line5
            APSUPP.ASCURC as ASCURC, --Line6
            APSUPP.ASCURC as ASCURC2, --Line7
            cast(TXDFT.TXCOD as varchar) as TXCOD, --Line8
            '' as HDR_TERMS_DATE_BASIS, -- Line9
            '' as CALC_FLAG, --Line10
            '' as TAX_FLAG, --Line11
            '' as AWT_FLAG,--Line12
            '' as GROUP_NAME, --Line13
            'CHECK' as METHOD_CODE, --Line14
            '101' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '004' as ADINUM, --Line17
            '' as CODE_BUSINESS, --Line18
            '' as CODE_DEPARTMENT, --Line19
            '' as CODE_SECTION, --Line20
            '' as CODE_ACCOUNT, --Line21
            TBLSTR.STSHRT as STSHRT2, --Line22
            'Y' as PAY_SITE_FLAG, --Line23
            APADDR.AAADD1 as ADDRESS_LINE1, --Line24
            APADDR.AAADD2 as ADDRESS_LINE2, --Line25
            '' as ADDRESS_LINE3, --Line26
            'PH' as DTL_COUNTRY, --Line27
            '' as DTL_PHONE_AREA_CODE, --Line28
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))),8) END as DTL_PHONE_NUMBER, --Line29
            '' as DTL_FAX_AREA_CODE, --Line30
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))),8) END as DTL_FAX_NUMBER, --Line31
            '' as DTL_FAX_NUMBER, --Line32
            cast(TXDFT.TXCOD as varchar) as DTL_VAT_CODE, --Line33
            cast(APSUPP.ASTRMS as varchar) as DTL_TERMS_NAME, --Line34
            '' as DTL_PAY_DATE_BASIS_LOOKUP_CODE, --Line35
            APSUPP.ASCURC as DTL_INVOICE_CURR_CODE, --Line36
            APSUPP.ASCURC as DTL_PAYMENT_CURR_CODE, --Line37
            'Y' as DTL_AUTO_TAX_CALC_FLAG, --Line38
            '' as DTL_AMOUNT_INCLUDES_TAX_FLAG, --Line39
            '' as DTL_PRIMARY_PAY_SITE_FLAG, --Line40
            '' as DTL_PAYMENT_METHOD_CODE, --Line41
            '' as DTl_ALLOW_AWT_FLAG, --Line42
            '' as DTL_AWT_GROUP_NAME, --Line43
            '' as DTL_EMAIL_ADDRESS, --Line44
            '' as DTL_ACCTS_PAY_CODE_COMPANY,--Line45
            '' as DTL_ACCTS_PAY_CODE_LOCATION, --Line46
            '00'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
            '' as DTL_ACCTS_PAY_CODE_BUSINESS, --Line48
            '' as DTL_ACCTS_PAY_CODE_DEPARTMENT, --Line49
            '' as DTL_ACCTS_PAY_CODE_SECTION, --Line50
            '' as DTL_ACCTS_PAY_CODE_ACCOUNT, --Line51
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) END as DTL2_CONT_FIRST_NAME, --Line52
            '.' as DTL2_CONT_MIDDLE_NAME, --Line53
            '.' as DTL2_CONT_LAST_NAME, --Line54
            '.' as DTL2_CONT_PREFIX, --Line55
            '' as DTL2_CONT_TITLE, --Line56
            '' as DTL2_CONT_PHONE_AREA_CODE, --Line57
            '' as DTL2_CONT_PHONE_NUMBER, --Line58
            '' as DTL2_CONT_FAX_AREA_CODE, --Line59
            '' as DTL2_CONT_FAX_NUMBER, --Line60
            '.' as POSTAL_CODE, --Line61
            '.' as CITY, --Line62
            '.' as COUNTY, --Line63
            '.' as STATE,     --Line64
            CASE WHEN ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) END as ASGSTN,  --Line65
            '' as LINE66,  --Line66
            '0' as LINE67, --Line67
            '' as LINE68, --Line68
            '0' as LINE69, --Line69
            cast('PG{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mm760lib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            apsupp.ASNUM = '{$supnum}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            AND TBLSTR.STSHRT in (
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
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPc($supnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $exclude = "AND TBLSTR.STSHRT not in ('SHAWDC','HO')";
        $forSetup = "AND TBLSTR.STSHRT not in ('DEPPC','VILPC')"; 
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
            SELECT    
            APSUPP.ASNAME --Line1
            +
            CASE WHEN APSUPP.ASTYPE = '1' THEN ' {TRADE}' ELSE ' {NON TRADE}' END as ASNAME, --Line1
            cast(APSUPP.ASNUM as varchar) as ASNUM, --Line2
            APSUPP.ASTYPE as ASTYPE2, --Line3
            cast(APSUPP.ASTRMS as varchar) as ASTRMS, --Line4
            '' as LOOKUP_CODE, --Line5
            APSUPP.ASCURC as ASCURC, --Line6
            APSUPP.ASCURC as ASCURC2, --Line7
            cast(TXDFT.TXCOD as varchar) as TXCOD, --Line8
            '' as HDR_TERMS_DATE_BASIS, -- Line9
            '' as CALC_FLAG, --Line10
            '' as TAX_FLAG, --Line11
            '' as AWT_FLAG,--Line12
            '' as GROUP_NAME, --Line13
            'CHECK' as METHOD_CODE, --Line14
            '302' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '010' as ADINUM, --Line17
            '' as CODE_BUSINESS, --Line18
            '' as CODE_DEPARTMENT, --Line19
            '' as CODE_SECTION, --Line20
            '' as CODE_ACCOUNT, --Line21
            TBLSTR.STSHRT as STSHRT2, --Line22
            'Y' as PAY_SITE_FLAG, --Line23
            APADDR.AAADD1 as ADDRESS_LINE1, --Line24
            APADDR.AAADD2 as ADDRESS_LINE2, --Line25
            '' as ADDRESS_LINE3, --Line26
            'PH' as DTL_COUNTRY, --Line27
            '' as DTL_PHONE_AREA_CODE, --Line28
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))),8) END as DTL_PHONE_NUMBER, --Line29
            '' as DTL_FAX_AREA_CODE, --Line30
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))),8) END as DTL_FAX_NUMBER, --Line31
            '' as DTL_FAX_NUMBER, --Line32
            cast(TXDFT.TXCOD as varchar) as DTL_VAT_CODE, --Line33
            cast(APSUPP.ASTRMS as varchar) as DTL_TERMS_NAME, --Line34
            '' as DTL_PAY_DATE_BASIS_LOOKUP_CODE, --Line35
            APSUPP.ASCURC as DTL_INVOICE_CURR_CODE, --Line36
            APSUPP.ASCURC as DTL_PAYMENT_CURR_CODE, --Line37
            'Y' as DTL_AUTO_TAX_CALC_FLAG, --Line38
            '' as DTL_AMOUNT_INCLUDES_TAX_FLAG, --Line39
            '' as DTL_PRIMARY_PAY_SITE_FLAG, --Line40
            '' as DTL_PAYMENT_METHOD_CODE, --Line41
            '' as DTl_ALLOW_AWT_FLAG, --Line42
            '' as DTL_AWT_GROUP_NAME, --Line43
            '' as DTL_EMAIL_ADDRESS, --Line44
            '' as DTL_ACCTS_PAY_CODE_COMPANY,--Line45
            '' as DTL_ACCTS_PAY_CODE_LOCATION, --Line46
            '0'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
            '' as DTL_ACCTS_PAY_CODE_BUSINESS, --Line48
            '' as DTL_ACCTS_PAY_CODE_DEPARTMENT, --Line49
            '' as DTL_ACCTS_PAY_CODE_SECTION, --Line50
            '' as DTL_ACCTS_PAY_CODE_ACCOUNT, --Line51
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) END as DTL2_CONT_FIRST_NAME, --Line52
            '.' as DTL2_CONT_MIDDLE_NAME, --Line53
            '.' as DTL2_CONT_LAST_NAME, --Line54
            '.' as DTL2_CONT_PREFIX, --Line55
            '' as DTL2_CONT_TITLE, --Line56
            '' as DTL2_CONT_PHONE_AREA_CODE, --Line57
            '' as DTL2_CONT_PHONE_NUMBER, --Line58
            '' as DTL2_CONT_FAX_AREA_CODE, --Line59
            '' as DTL2_CONT_FAX_NUMBER, --Line60
            '.' as POSTAL_CODE, --Line61
            '.' as CITY, --Line62
            '.' as COUNTY, --Line63
            '.' as STATE,     --Line64
            CASE WHEN ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) END as ASGSTN,  --Line65
            '' as LINE66,  --Line66
            '0' as LINE67, --Line67
            '' as LINE68, --Line68
            '0' as LINE69, --Line69
            cast('PC{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mm760lib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mm760lib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            apsupp.ASNUM = '{$supnum}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            AND TBLSTR.STSHRT in (
                select STSHRT from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
                and STSHRT <> 'SBCHO'  
            )
            $exclude
            $forSetup
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataDi($supnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        echo $sql="
            SELECT    
            APSUPP.ASNAME --Line1
            +
            CASE WHEN APSUPP.ASTYPE = '1' THEN ' {TRADE}' ELSE ' {NON TRADE}' END as ASNAME, --Line1
            cast(APSUPP.ASNUM as varchar) as ASNUM, --Line2
            APSUPP.ASTYPE as ASTYPE2, --Line3
            cast(APSUPP.ASTRMS as varchar) as ASTRMS, --Line4
            '' as LOOKUP_CODE, --Line5
            APSUPP.ASCURC as ASCURC, --Line6
            APSUPP.ASCURC as ASCURC2, --Line7
            cast(TXDFT.TXCOD as varchar) as TXCOD, --Line8
            '' as HDR_TERMS_DATE_BASIS, -- Line9
            '' as CALC_FLAG, --Line10
            '' as TAX_FLAG, --Line11
            '' as AWT_FLAG,--Line12
            '' as GROUP_NAME, --Line13
            'CHECK' as METHOD_CODE, --Line14
            '810' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '008' as ADINUM, --Line17
            '' as CODE_BUSINESS, --Line18
            '' as CODE_DEPARTMENT, --Line19
            '' as CODE_SECTION, --Line20
            '' as CODE_ACCOUNT, --Line21
            TBLSTR.STSHRT as STSHRT2, --Line22
            'Y' as PAY_SITE_FLAG, --Line23
            APADDR.AAADD1 as ADDRESS_LINE1, --Line24
            APADDR.AAADD2 as ADDRESS_LINE2, --Line25
            '' as ADDRESS_LINE3, --Line26
            'PH' as DTL_COUNTRY, --Line27
            '' as DTL_PHONE_AREA_CODE, --Line28
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))),8) END as DTL_PHONE_NUMBER, --Line29
            '' as DTL_FAX_AREA_CODE, --Line30
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))),8) END as DTL_FAX_NUMBER, --Line31
            '' as DTL_FAX_NUMBER, --Line32
            cast(TXDFT.TXCOD as varchar) as DTL_VAT_CODE, --Line33
            cast(APSUPP.ASTRMS as varchar) as DTL_TERMS_NAME, --Line34
            '' as DTL_PAY_DATE_BASIS_LOOKUP_CODE, --Line35
            APSUPP.ASCURC as DTL_INVOICE_CURR_CODE, --Line36
            APSUPP.ASCURC as DTL_PAYMENT_CURR_CODE, --Line37
            'Y' as DTL_AUTO_TAX_CALC_FLAG, --Line38
            '' as DTL_AMOUNT_INCLUDES_TAX_FLAG, --Line39
            '' as DTL_PRIMARY_PAY_SITE_FLAG, --Line40
            '' as DTL_PAYMENT_METHOD_CODE, --Line41
            '' as DTl_ALLOW_AWT_FLAG, --Line42
            '' as DTL_AWT_GROUP_NAME, --Line43
            '' as DTL_EMAIL_ADDRESS, --Line44
            '' as DTL_ACCTS_PAY_CODE_COMPANY,--Line45
            '' as DTL_ACCTS_PAY_CODE_LOCATION, --Line46
            '00'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
            '' as DTL_ACCTS_PAY_CODE_BUSINESS, --Line48
            '' as DTL_ACCTS_PAY_CODE_DEPARTMENT, --Line49
            '' as DTL_ACCTS_PAY_CODE_SECTION, --Line50
            '' as DTL_ACCTS_PAY_CODE_ACCOUNT, --Line51
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) END as DTL2_CONT_FIRST_NAME, --Line52
            '.' as DTL2_CONT_MIDDLE_NAME, --Line53
            '.' as DTL2_CONT_LAST_NAME, --Line54
            '.' as DTL2_CONT_PREFIX, --Line55
            '' as DTL2_CONT_TITLE, --Line56
            '' as DTL2_CONT_PHONE_AREA_CODE, --Line57
            '' as DTL2_CONT_PHONE_NUMBER, --Line58
            '' as DTL2_CONT_FAX_AREA_CODE, --Line59
            '' as DTL2_CONT_FAX_NUMBER, --Line60
            '.' as POSTAL_CODE, --Line61
            '.' as CITY, --Line62
            '.' as COUNTY, --Line63
            '.' as STATE,     --Line64
            CASE WHEN ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) END as ASGSTN,  --Line65
            '' as LINE66,  --Line66
            '0' as LINE67, --Line67
            '' as LINE68, --Line68
            '0' as LINE69, --Line69
            cast('DI{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mmneslib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmneslib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmneslib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mmneslib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mmneslib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            apsupp.ASNUM = '{$supnum}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            AND TBLSTR.STSHRT in (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
                and STRNUM < 900
            )
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataFl($supnum) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql="
            SELECT    
            APSUPP.ASNAME --Line1
            +
            CASE WHEN APSUPP.ASTYPE = '1' THEN ' {TRADE}' ELSE ' {NON TRADE}' END as ASNAME, --Line1
            cast(APSUPP.ASNUM as varchar) as ASNUM, --Line2
            APSUPP.ASTYPE as ASTYPE2, --Line3
            cast(APSUPP.ASTRMS as varchar) as ASTRMS, --Line4
            '' as LOOKUP_CODE, --Line5
            APSUPP.ASCURC as ASCURC, --Line6
            APSUPP.ASCURC as ASCURC2, --Line7
            cast(TXDFT.TXCOD as varchar) as TXCOD, --Line8
            '' as HDR_TERMS_DATE_BASIS, -- Line9
            '' as CALC_FLAG, --Line10
            '' as TAX_FLAG, --Line11
            '' as AWT_FLAG,--Line12
            '' as GROUP_NAME, --Line13
            'CHECK' as METHOD_CODE, --Line14
            '811' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '008' as ADINUM, --Line17
            '' as CODE_BUSINESS, --Line18
            '' as CODE_DEPARTMENT, --Line19
            '' as CODE_SECTION, --Line20
            '' as CODE_ACCOUNT, --Line21
            TBLSTR.STSHRT as STSHRT2, --Line22
            'Y' as PAY_SITE_FLAG, --Line23
            APADDR.AAADD1 as ADDRESS_LINE1, --Line24
            APADDR.AAADD2 as ADDRESS_LINE2, --Line25
            '' as ADDRESS_LINE3, --Line26
            'PH' as DTL_COUNTRY, --Line27
            '' as DTL_PHONE_AREA_CODE, --Line28
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAPHON,'.'))),8) END as DTL_PHONE_NUMBER, --Line29
            '' as DTL_FAX_AREA_CODE, --Line30
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))) = '' THEN '.' ELSE left(ltrim(rtrim(ISNULL(APADDR.AAFAXN,'.'))),8) END as DTL_FAX_NUMBER, --Line31
            '' as DTL_FAX_NUMBER, --Line32
            cast(TXDFT.TXCOD as varchar) as DTL_VAT_CODE, --Line33
            cast(APSUPP.ASTRMS as varchar) as DTL_TERMS_NAME, --Line34
            '' as DTL_PAY_DATE_BASIS_LOOKUP_CODE, --Line35
            APSUPP.ASCURC as DTL_INVOICE_CURR_CODE, --Line36
            APSUPP.ASCURC as DTL_PAYMENT_CURR_CODE, --Line37
            'Y' as DTL_AUTO_TAX_CALC_FLAG, --Line38
            '' as DTL_AMOUNT_INCLUDES_TAX_FLAG, --Line39
            '' as DTL_PRIMARY_PAY_SITE_FLAG, --Line40
            '' as DTL_PAYMENT_METHOD_CODE, --Line41
            '' as DTl_ALLOW_AWT_FLAG, --Line42
            '' as DTL_AWT_GROUP_NAME, --Line43
            '' as DTL_EMAIL_ADDRESS, --Line44
            '' as DTL_ACCTS_PAY_CODE_COMPANY,--Line45
            '' as DTL_ACCTS_PAY_CODE_LOCATION, --Line46
            '00'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
            '' as DTL_ACCTS_PAY_CODE_BUSINESS, --Line48
            '' as DTL_ACCTS_PAY_CODE_DEPARTMENT, --Line49
            '' as DTL_ACCTS_PAY_CODE_SECTION, --Line50
            '' as DTL_ACCTS_PAY_CODE_ACCOUNT, --Line51
            CASE WHEN ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APADDR.AACONT,'.'))) END as DTL2_CONT_FIRST_NAME, --Line52
            '.' as DTL2_CONT_MIDDLE_NAME, --Line53
            '.' as DTL2_CONT_LAST_NAME, --Line54
            '.' as DTL2_CONT_PREFIX, --Line55
            '' as DTL2_CONT_TITLE, --Line56
            '' as DTL2_CONT_PHONE_AREA_CODE, --Line57
            '' as DTL2_CONT_PHONE_NUMBER, --Line58
            '' as DTL2_CONT_FAX_AREA_CODE, --Line59
            '' as DTL2_CONT_FAX_NUMBER, --Line60
            '.' as POSTAL_CODE, --Line61
            '.' as CITY, --Line62
            '.' as COUNTY, --Line63
            '.' as STATE,     --Line64
            CASE WHEN ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) = '' THEN '.' ELSE ltrim(rtrim(ISNULL(APSUPP.ASGSTN,'.'))) END as ASGSTN,  --Line65
            '' as LINE66,  --Line66
            '0' as LINE67, --Line67
            '' as LINE68, --Line68
            '0' as LINE69, --Line69
            cast('FL{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mmneslib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmneslib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmneslib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mmneslib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mmneslib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            apsupp.ASNUM = '{$supnum}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            AND TBLSTR.STSHRT in (
                select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
                and STRNUM < 900
            )
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
}
?>