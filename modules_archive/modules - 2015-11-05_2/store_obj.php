<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class storeObj extends commonObj {    
    
    function updateStore() {
            
        $sql="
            truncate table tbl_supplier_temp_TBLSTR    
        ";
        
        $sql2="
            insert into tbl_supplier_temp_TBLSTR
            select *  from openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR
        ";
        
        $sql3="
            delete from tbl_supplier_temp_TBLSTR where strnum in 
            (select strnum from openquery(pgjda, 'select * from mm760lib.tblstr where strnum in (
            ''855'',''856'',''857'',''858'',''859'',''860'',''861'',''862'',''863'',
            ''990'',''991'')'))
        ";
        
        $sql4="
            insert into tbl_supplier_temp_TBLSTR
            select *  from openquery(pgjda, 'select * from mmneslib.TBLSTR') TBLSTR
        ";
        
        $turnOnAnsiNulls = "SET ANSI_NULLS ON";
        $turnOnAnsiWarn = "SET ANSI_WARNINGS ON";
        
        $this->execQry($turnOnAnsiNulls);
        $this->execQry($turnOnAnsiWarn);
        if($this->execQry($sql)){  
            if($this->execQry($sql2)){
                if($this->execQry($sql3)){   
                    if($this->execQry($sql4)){
                        echo "Done all Queries";
                    }
                    else{
                        echo "Error Query 4";
                    }   
                }
                else{
                    echo "Error Query 3";
                }   
            }
            else{
                echo "Error Query 2";
            }  
        }
        else{
            echo "Error Query 1";
        }
    }
}
?>