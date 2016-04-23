<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class supplierObj extends commonObj {
    
    function viewData($strShort) {
        
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sqlPj = "
            select count(*) as count from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT = '$strShort'
        ";
        
        $sqlPg = "
            select count(*) as count from openquery(pgjda, 'select * from mmpgtlib.TBLSTR') TBLSTR 
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
            and STSHRT = '$strShort'   
        ";  
        
        $sqlPc = "
            select count(*) as count from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO' 
            and STSHRT = '$strShort'  
        "; 
        
        $sqlDi = "
            select count(*) as count from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900
            and STSHRT = '$strShort'  
        "; 
        
        $sqlFl = "
            select count(*) as count from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900
            and STSHRT = '$strShort'  
        "; 
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);    
        $sqlPjCount = $this->getSqlAssoc($this->execQry($sqlPj));
        
        if($sqlPjCount['count'] > 0){
            $bl = "00";
            $suffix = "PJ";    
        }else{
            $this->execQry($turnOnAnsiNulls);
            $this->execQry($turnOnAnsiWarn);
            $sqlPgCount = $this->getSqlAssoc($this->execQry($sqlPg));
            if($sqlPgCount['count'] > 0){
                $bl = "00";
                $suffix = "PG";      
            }else{
                $this->execQry($turnOnAnsiNulls);
                $this->execQry($turnOnAnsiWarn);
                $sqlPcCount = $this->getSqlAssoc($this->execQry($sqlPc));
                if($sqlPcCount['count'] > 0){
                    $bl = "0";
                    $suffix = "PC";     
                }else{
                    $this->execQry($turnOnAnsiNulls);
                    $this->execQry($turnOnAnsiWarn);
                    $sqlDiCount = $this->getSqlAssoc($this->execQry($sqlDi));
                    if($sqlDiCount['count'] > 0){
                        $bl = "00";
                        $suffix = "DI";      
                    }else{
                        $this->execQry($turnOnAnsiNulls);
                        $this->execQry($turnOnAnsiWarn);
                        $sqlFlCount = $this->getSqlAssoc($this->execQry($sqlFl));
                        if($sqlFlCount['count'] > 0){
                            $bl = "00";
                            $suffix = "FL";      
                        }    
                    }    
                }    
            }    
        }
        
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
            cast(TBLSTR.STCOMP as varchar) as STCOMP, --Line15
            cast(TBLSTR.STSHRT as varchar) as STSHRT, --Line16
            '{$bl}'+cast(INVADIx1.ADINUM as varchar) as ADINUM, --Line17
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
            cast('{$suffix}{$curDate}_'+cast(TBLSTR.STSHRT as varchar)+'.101' as nvarchar) as FILENAME
            FROM  openquery(pgjda, 'select * from mmpgtlib.APSUPP') APSUPP LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmpgtlib.TXDFT')  TXDFT ON apsupp.ASNUM = TXDFT.TXNUM LEFT OUTER JOIN
                        openquery(pgjda, 'select * from mmpgtlib.APADDR') APADDR ON apsupp.ASNUM = APADDR.AANUM INNER JOIN
                        OPENQUERY(pgjda, 'select * from mmpgtlib.INVADIx1') INVADIx1 CROSS JOIN
                        openquery(pgjda, 'select * from mmpgtlib.TBLSTR') TBLSTR ON INVADIx1.STRNUM = TBLSTR.STRNUM
            WHERE   
            TBLSTR.STSHRT = '{$strShort}'
            AND APSUPP.ASNUM <> '9993'
            AND APSUPP.ASNAME not like '%NTBU%'
            ORDER BY APSUPP.ASNUM,TBLSTR.STSHRT ASC
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
                select strnum,stshrt,strnam from openquery(pgjda,'select strnum,stshrt,strnam from mmpgtlib.tblstr
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