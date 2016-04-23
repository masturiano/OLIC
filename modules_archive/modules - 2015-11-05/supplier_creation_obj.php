<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class supplierObj extends commonObj {    
    
    function viewDataPj($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";
              
        }
        
        $sites = "
        and TBLSTR.STCOMP = 700
        and TBLSTR.STRNAM NOT LIKE 'X%'
        and TBLSTR.STRNUM < 900
        and TBLSTR.STRNUM <> 805
        "; 
            
        $bl = "00";
        $suffix = "PJ";
        $header_stcomp = "700";
        $header_bl = "005";  
        $library = "mm760lib";
            
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPg($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'"; 
        }
        else{
            $filter_store_short = "";   
        }
        
        $sites = "
            and INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mm760lib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            )
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900 
        "; 
        
        $bl = "00";
        $suffix = "PG"; 
        $header_stcomp = "101";
        $header_bl = "004"; 
        $library = "mm760lib";  
        
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataPc($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'"; 
        }
        else{
            $filter_store_short = "";   
        }
        
        $sites = "
            and TBLSTR.STCOMP = 302
            and TBLSTR.STRNAM NOT LIKE 'X%'
            and TBLSTR.STRNUM < 900
            and TBLSTR.STRNUM <> 805
            and TBLSTR.STSHRT <> 'SBCHO' 
        ";
        
        $bl = "0";
        $suffix = "PC";
        $header_stcomp = "302";
        $header_bl = "010";  
        $library = "mm760lib";  
        
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataDi($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'"; 
        }
        else{
            $filter_store_short = "";   
        }
        
        $sites = "            
            and TBLSTR.STCOMP = 810
            and TBLSTR.STRNUM < 900
        ";
        
        $bl = "00";
        $suffix = "DI";  
        $header_stcomp = "810";
        $header_bl = "008";   
        $library = "mmneslib"; 
        
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataFl($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'"; 
        }
        else{
            $filter_store_short = "";   
        }
        
        $sites = "            
            and TBLSTR.STCOMP = 811
            and TBLSTR.STRNUM < 900
        ";
        
        $bl = "00";
        $suffix = "FL"; 
        $header_stcomp = "811";
        $header_bl = "008"; 
        $library = "mmneslib";
        
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function viewDataGt($supplier_number,$store_short) {
        
        if($store_short != ''){
            $filter_store_short = "and TBLSTR.STSHRT = '{$store_short}'"; 
        }
        else{
            $filter_store_short = "";   
        }
        
        $sites = "            
            and TBLSTR.STCOMP = 812   
        ";
        //and TBLSTR.STRNUM < 900
        
        $bl = "00";
        $suffix = "GT"; 
        $header_stcomp = "812";
        $header_bl = "008"; 
        $library = "mm760lib";
        
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
            '{$header_stcomp}' as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$header_bl}' as ADINUM, --Line17
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
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM2, --Line47
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
            cast('{$suffix}{$curDate}_0'+cast(APSUPP.ASNUM as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from {$library}.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from {$library}.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from {$library}.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from {$library}.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            APSUPP.ASNUM = '{$supplier_number}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            {$sites}
            {$filter_store_short}
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
        ";
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function findSupplier($terms){
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql = "
            select TOP 10 asnum,asname from (
                    select asnum,asname from openquery(pgjda,'select asnum,asname from mm760lib.apsupp
                where asnum  LIKE ''$terms%'' or asname  LIKE ''$terms%''
                ')
            ) as apsupp
            ORDER BY asnum
        ";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
    
    function findSite($terms){
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql = "
            select TOP 10 strnum,stshrt,strnam from (
                select strnum,stshrt,strnam from openquery(pgjda,'select strnum,stshrt,strnam from mm760lib.tblstr
                where (strnam NOT LIKE ''X%'')
                and (
                    stcomp = 302
                    or stcomp in (101,102,103,104,105)
                    or stcomp in (801,802,803,804,805,806,807,808,809)
                    or stcomp = 302
                )
                and (strnum < 900)
                ')
                union
                select * from openquery(pgjda,'select strnum,stshrt,strnam from mmneslib.tblstr
                where (strnam NOT LIKE ''X%'')
                and (
                    stcomp = 810
                    or stcomp = 811
                )
                and (strnum < 900)
                ')
            ) as tblstr
            where strnum like '%$terms%' OR strnam like '%$terms%'
            ORDER BY strnum
        ";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        return $this->getArrRes($this->execQry($sql));
    }
}
?>