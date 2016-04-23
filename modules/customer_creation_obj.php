<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class customerObj extends commonObj {    
    
    function viewDataPj($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "00";
        $suffix = "PJ";
        $library = "mm760lib";
            
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (
                    select STSHRT from sql_mmpgtlib..TBLSTR where STCOMP = 700
                    and STRNAM NOT LIKE 'X%'
                    and STRNUM < 900
                    and STRNUM <> 805
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        }  
    }
    
    function viewDataPg($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "00";
        $suffix = "PG";
        $library = "mm760lib";
            
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (       
                select STSHRT from sql_mmpgtlib..TBLSTR TBLSTR 
                LEFT JOIN
                tbl_customer_temp_INVADIx1 INVADIx1
                ON  TBLSTR.STRNUM = INVADIx1.STRNUM
                where INVADIx1.ADINUM in (4,5,6)
                and TBLSTR.STSHRT not in (
                    select stshrt from sql_mmpgtlib..TBLSTR where stcomp = 700
                    and STRNAM NOT LIKE 'X%'
                    and STRNUM < 900
                    and STRNUM <> 805
                )
                and TBLSTR.STRNAM NOT LIKE 'X%'
                and TBLSTR.STRNUM < 900  
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        }   
    }
    
    function viewDataPc($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "0";
        $suffix = "PC";
        $library = "mm760lib";
            
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (
                select STSHRT from sql_mmpgtlib..TBLSTR where STCOMP = 302
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
                and STSHRT <> 'SBCHO'
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        } 
    }
    
    function viewDataDi($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "00";
        $suffix = "DI";
        $library = "mmneslib";
            
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (
                select STSHRT from sql_mmpgtlib..TBLSTR where STCOMP = 810
                and STRNUM < 900
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        }
    }
    
    function viewDataFl($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "00";
        $suffix = "FL";
        $library = "mmneslib";
            
        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (
                select STSHRT from sql_mmpgtlib..TBLSTR where STCOMP = 811
                and STRNUM < 900
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        }
    }
    
    function viewDataGt($customer_number,$store_short) {
        
        $exclude = "AND b.STSHRT not in ('SHAWDC','HO','ANGPC')";
        
        if($store_short != ''){
            $filter_store_short = "and b.STSHRT = '{$store_short}'";        
        }
        else{   
            $filter_store_short = "";   
        }
            
        $bl = "0";
        $suffix = "GT";
        $library = "mm760lib";

        $curDate =  date('mdy');
        $curTime =  date('his');
        
        $sql_trunc_CIMCUS = "
            IF OBJECT_ID('tbl_customer_temp_CIMCUS', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_customer_temp_CIMCUS
            END
        ";
        $sql_insert_CIMCUS = "
            select 
                DISTINCT * into tbl_customer_temp_CIMCUS  
            from 
                openquery(pgjda, 'select * from {$library}.CIMCUS  where CUSTOMER_NUMBER = ''{$customer_number}''') CIMCUS
        ";
        
        $sql_trunc_INVADIx1 = "
            IF OBJECT_ID('tbl_customer_temp_INVADIx1', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_INVADIx1
            END
        ";
        $sql_insert_INVADIx1 = "
            select DISTINCT * into tbl_customer_temp_INVADIx1  from openquery(pgjda, 'select * from {$library}.INVADIx1') INVADIx1
        "; 
        
        $sql_trunc_ARZMST = "  
            IF OBJECT_ID('tbl_customer_temp_ARZMST', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_ARZMST
            END
        ";
        $sql_insert_ARZMST = "
            select DISTINCT * into tbl_customer_temp_ARZMST  from openquery(pgjda, 'select * from {$library}.ARZMST where CUSNUM = ''{$customer_number}''') CIMCUS
        ";

        $sql_trunc_CIMMBR = "
            IF OBJECT_ID('tbl_customer_temp_CIMMBR', 'U') IS NOT NULL
            BEGIN
            drop table tbl_customer_temp_CIMMBR
            END
        ";
        $sql_insert_CIMMBR = "
            SELECT DISTINCT * into tbl_customer_temp_CIMMBR FROM openquery(pgjda, 'select * from {$library}.CIMMBR where MBRNUM = ''{$customer_number}''')
        ";   
        
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
            '{$bl}'+ltrim(rtrim(a.ADINUM)) as BL, 
            cast('{$suffix}{$curDate}_'+ltrim(rtrim(c.CUSTOMER_NUMBER))+'.201' as nvarchar) as FILENAME
            from tbl_customer_temp_INVADIx1 as a 
            JOIN (select * from sql_mmpgtlib..TBLSTR) as b ON a.strnum = b.strnum
            CROSS JOIN tbl_customer_temp_CIMCUS as c
            JOIN (select CUSNUM,CUSCLS FROM tbl_customer_temp_ARZMST GROUP BY CUSNUM,CUSCLS) as d on c.CUSTOMER_NUMBER = d.CUSNUM
            left join (SELECT * FROM tbl_customer_temp_CIMMBR) as t on t.mbrnum = c.customer_number
            WHERE 
            c.CUSTOMER_NUMBER = '{$customer_number}'
            AND b.STSHRT in 
            (
                select STSHRT from sql_mmpgtlib..TBLSTR where STCOMP = 812  
                and STRNUM < 900
            )
            {$filter_store_short}
            {$exclude}
            ORDER BY b.STSHRT
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);      
        
        if($this->execQry($sql_trunc_CIMCUS)){
            if($this->execQry($sql_insert_CIMCUS)){
                if($this->execQry($sql_trunc_INVADIx1)){
                    if($this->execQry($sql_insert_INVADIx1)){
                        if($this->execQry($sql_trunc_ARZMST)){
                            if($this->execQry($sql_insert_ARZMST)){
                                if($this->execQry($sql_trunc_CIMMBR)){
                                    if($this->execQry($sql_insert_CIMMBR)){
                                        return $this->getArrRes($this->execQry($sql));
                                    }     
                                } 
                            } 
                        } 
                    } 
                } 
            }    
        }
    }
    
    function findCustomer($terms){
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $sql = "
            select 
                DISTINCT TOP 10  CUSTOMER_NUMBER,FULL_NAME
            from 
                (
                    select 
                        CUSTOMER_NUMBER,FULL_NAME
                    from 
                        openquery(pgjda,'select CUSTOMER_NUMBER,FULL_NAME from mm760lib.cimcus
                    where 
                        CUSTOMER_NUMBER  LIKE ''$terms%'' or FULL_NAME  LIKE ''%$terms%''          
                    ')
                    union all
                    select 
                        CUSTOMER_NUMBER,FULL_NAME
                    from 
                        openquery(pgjda,'select CUSTOMER_NUMBER,FULL_NAME from mmneslib.cimcus
                    where 
                        CUSTOMER_NUMBER  LIKE ''$terms%'' or FULL_NAME  LIKE ''%$terms%''          
                    ')
                ) as cimcus
            ORDER BY 
            CUSTOMER_NUMBER
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
                    or stcomp = 812
                    or stcomp = 700
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