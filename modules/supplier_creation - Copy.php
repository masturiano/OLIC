<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("supplier_creation_obj.php");

$supplierObj = new supplierObj();

switch($_GET['action']){
    
    case 'searchSupplier':
        $arrResult = array();
            $arrSupplier = $supplierObj->findSupplier($_GET['term']);
                foreach($arrSupplier as $val){
                    $arrResult[] = array(
                        "id"=>$val['asnum'],
                        "label"=>$val['asnum']." - ".$val['asname'],
                        "value" => strip_tags($val['asname']));    
                }
        echo json_encode($arrResult);
    exit();    
    break; 
    
    case 'searchStore':
        $arrResult = array();
            $arrStore = $supplierObj->findSite($_GET['term']);
                foreach($arrStore as $val){
                    $arrResult[] = array(
                        "id"=>$val['stshrt'],
                        "label"=>$val['strnum']." - ".str_replace("-",'-',$val['strnam']),
                        "value" => strip_tags($val['strnam']));    
                }
        echo json_encode($arrResult);
    exit();    
    break; 
	
	case "createTextFile":
                               
        $arrData = $supplierObj->$_GET['function']($_GET['supplierNumber'],$_GET['storeShort']);
        
        $fCont = "";
        $fSeparator = "|";
            
        foreach($arrData as $valFileCont){
            
            $fCont .= trim($valFileCont['ASNAME']); #1.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASNUM']); #2.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASTYPE2']); #3.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASTRMS']); #4.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['LOOKUP_CODE']); #5.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASCURC']); #6.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASCURC2']); #7.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['TXCOD']); #8.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['HDR_TERMS_DATE_BASIS']); #9.
            $fCont .= $fSeparator;     
            $fCont .= trim($valFileCont['CALC_FLAG']); #10.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['TAX_FLAG']); #11.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['AWT_FLAG']); #12.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['GROUP_NAME']); #13.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['METHOD_CODE']); #14.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STCOMP']); #15.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STSHRT']); #16.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADINUM']); #17.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CODE_BUSINESS']); #18.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CODE_DEPARTMENT']); #19.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CODE_SECTION']); #20.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CODE_ACCOUNT']); #21.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STSHRT2']); #22.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['PAY_SITE_FLAG']); #23.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS_LINE1']); #24.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS_LINE2']); #25.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS_LINE3']); #26.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_COUNTRY']); #27.
            $fCont .= $fSeparator;                          
            $fCont .= trim($valFileCont['DTL_PHONE_AREA_CODE']); #28.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_PHONE_NUMBER']); #29.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_FAX_AREA_CODE']); #30.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_FAX_NUMBER']); #31.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_FAX_NUMBER']); #32.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_VAT_CODE']); #33.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_TERMS_NAME']); #34.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_PAY_DATE_BASIS_LOOKUP_CODE']); #35.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_INVOICE_CURR_CODE']); #36.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_PAYMENT_CURR_CODE']); #37.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_AUTO_TAX_CALC_FLAG']); #38.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_AMOUNT_INCLUDES_TAX_FLAG']); #39.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_PRIMARY_PAY_SITE_FLAG']); #40.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_PAYMENT_METHOD_CODE']); #41.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTl_ALLOW_AWT_FLAG']); #42.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_AWT_GROUP_NAME']); #43.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_EMAIL_ADDRESS']); #44.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_COMPANY']); #45.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_LOCATION']); #46.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADINUM2']); #47.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_BUSINESS']); #48.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_DEPARTMENT']); #49.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_SECTION']); #50.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL_ACCTS_PAY_CODE_ACCOUNT']); #51.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_FIRST_NAME']); #52.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_MIDDLE_NAME']); #53.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_LAST_NAME']); #54.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_PREFIX']); #55.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_TITLE']); #56.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_PHONE_AREA_CODE']); #57.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_PHONE_NUMBER']); #58.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_FAX_AREA_CODE']); #59.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['DTL2_CONT_FAX_NUMBER']); #60.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['POSTAL_CODE']); #61.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CITY']); #62.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['COUNTY']); #63.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STATE']); #64.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ASGSTN']); #65.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['LINE66']); #66.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['LINE67']); #67.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['LINE68']); #68.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['LINE69']); #69.
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['FILENAME']); #70.Filename
            $fCont .= $fSeparator;
            
            $fCont .= "\r\n";
            
            $fileName = $valFileCont['FILENAME'];
        }
        
        $destiFoldr = "../exported_file_supplier/".$fileName; 
                   
        if (file_exists($destiFoldr)) {
            unlink($destiFoldr);
        }         
        
        $handleFromFileName = fopen ($destiFoldr, "x");
        
        fwrite($handleFromFileName, $fCont);
        fclose($handleFromFileName) ;
        
    exit();
    break; 
      
}

$arrOrgId = array('0'=>"Select Company",'87'=>"Junior",'85'=>"PPCI",'133'=>"Subic",'153'=>"DCI",'113'=>"FLS");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OLIC</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<html>
	<head>
    	<link rel="stylesheet" href="../includes/style.css" />
        <link rel="stylesheet" href="../includes/skeleton/skeleton.css" />
        <link rel="icon" type="image/ico" href="../includes/images/oraicon.gif">
        
        <meta charset="utf-8">
        <link rel="stylesheet" href="../includes/jquery/development-bundle/themes/base/jquery.ui.all.css">
        <link type="text/css" href="../includes/media/jquery/css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
		<link type="text/css" href="../includes/media/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
        
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/jquery-ui-1.8.16.custom.css"/>
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/demos.css"/>
        
        <script src="../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../includes/bootbox/bootbox.js"></script>
		<script src="../includes/jquery/js/jquery.dataTables.js"></script>
        
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.button.js"></script>
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>

        <script src="../includes/modal/modal.js"></script>
        <link rel="stylesheet" href="../includes/modal/modal.css">
        
        <link href="../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../includes/showLoading/js/jquery.showLoading.js"></script>
        
        <script src="../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
        
        <script src="../includes/jquery/js/jquery.dataTables.min.js"></script>   
        <script src="../includes/jquery/js/dataTables.bootstrap.js"></script>   
        <style type="text/css" title="currentStyle">
            @import "../includes/jquery/css/jquery.dataTables_themeroller.css";
        </style>
        
        <script type="text/javascript">
			function process()
			{
                var txtSupplier = $("#txtSupplier").val();
                var txtSupplierNumber = $("#txtSupplierNum").val();
				var txtStoreShort = $("#txtStoreShort").val();

                if(txtSupplier == ''){
                    $().toastmessage('showToast', {
                        text: 'Please search Supplier!',
                        sticky: true,
                        position: 'middle-center',
                        type: 'error',
                        closeText: '',
                        close: function () 
                        {
                        console.log("toast is closed ...");
                        }
                    });
                    return false;
                }
				
				$.ajax
                ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataPj',
                    beforeSend: function()
                    {
                        jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
                    },
                    success: function(data1){
                        processPgTextfile(txtSupplierNumber,txtStoreShort);
                    }
                });    
			}
            
            function processPgTextfile(txtSupplierNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataPg',
                    success: function(data1){
                        processPcTextfile(txtSupplierNumber,txtStoreShort);
                    }
                });   
            }
            
            function processPcTextfile(txtSupplierNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataPc',
                    success: function(data1){
                        processDlTextfile(txtSupplierNumber,txtStoreShort);
                    }
                });   
            }
            
            function processDlTextfile(txtSupplierNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataDi',
                    success: function(data1){
                        processFlTextfile(txtSupplierNumber,txtStoreShort);
                    }
                });   
            }
            
            function processFlTextfile(txtSupplierNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataFl',
                    success: function(data1){
                        processGtTextfile(txtSupplierNumber,txtStoreShort);
                    }
                });   
            }
            
            function processGtTextfile(txtSupplierNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'supplier_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&supplierNumber='+txtSupplierNumber+'&storeShort='+txtStoreShort+'&function=viewDataGt',
                    success: function(data1){
                        jQuery('#activity_pane').hideLoading();
                        $().toastmessage('showToast', {
                            text: 'Textfile created for supplier number '+txtSupplierNumber,
                            sticky: true,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            $('#process').html('Process');
                            document.location.reload(false);
                            }
                        }); 
                    }
                });   
            }
			
			$(function(){
				$('#txtMonYr').datepicker
				({
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true,
					dateFormat: 'M-yy',
					onClose: function(dateText, inst) 
					{ 
						var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						$(this).datepicker('setDate', new Date(year, month, 1));
					}
				});	
			});
			
			$(function(){
				$( "#datepicker" ).datepicker
				({
					changeMonth: true,
					changeYear: true
				});
			});
			
			$('document').ready(function(){
                $('#supplierList').dataTable({
                    "sPaginationType": "full_numbers"
                })  
            });
            
            $(function(){
                $("#txtSupplier").autocomplete({
                    source: "supplier_creation.php?action=searchSupplier",
                    minLength: 1,
                    select: function(event, ui) {    
                        var content = ui.item.id.split("|");
                        $("#txtSupplierNum").val(content[0]);
                    }
                }); 
            });
            
            $(function(){
                $("#txtStore").autocomplete({
                    source: "supplier_creation.php?action=searchStore",
                    minLength: 1,
                    select: function(event, ui) {    
                        var content = ui.item.id.split("|");
                        $("#txtStoreShort").val(content[0]);
                    }
                }); 
            }); 
		</script>
        
        <style type="text/css" title="currentStyle">
			@import "media/jquery/css/demo_page.css";
			@import "media/jquery/css/demo_table_jui.css";
        </style>
        
        <style type="text/css">
			.textBox 
            {
                border: solid 1px #222; 
                border-width: 1px; 
                width:130px; 
                height:18px;
                font-size: 11px;
            }
            
            .selectBox 
            {
                border: 1px solid #222; 
                width:132px; 
                height:22px;
                font-size: 11px;
            }
            
            .hd 
            {
                font-size: 11px;
                font-family: Verdana;
                font-weight: bold;
            }
            
            .btn {
            background: #3498db;
            background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
            background-image: -moz-linear-gradient(top, #3498db, #2980b9);
            background-image: -ms-linear-gradient(top, #3498db, #2980b9);
            background-image: -o-linear-gradient(top, #3498db, #2980b9);
            background-image: linear-gradient(to bottom, #3498db, #2980b9);
            -webkit-border-radius: 60;
            -moz-border-radius: 60;
            border-radius: 60px;
            font-family: Courier New;
            color: #ffffff;
            font-size: 20px;
            padding: 10px 20px 10px 20px;
            text-decoration: none;
            }

            .btn:hover {
            background: #3cb0fd;
            text-decoration: none;
            }
            
            .selectBox {
                background: skyblue;
                width: 230px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 25px;
                -webkit-appearnace: none;
            }
            
            .inputBox {
                background: skyblue;
                width: 320px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 15px;
                -webkit-appearnace: none;
            } 
            
            .inputBoxClear {
                background: transparent;
                width: 320px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 15px;
                -webkit-appearnace: none;
            } 
            
            #activity_pane {
                width: 100%;
                height: 100%;
                border: 0px solid #CCCCCC;
                background-color:none;
                padding-top: 0px;
                overflow: visible;   
            }
            
		</style>
    </head>    
        <body>
            <div id='header' align="center"></div>
            <? include('menu.php'); ?>
            <div id="activity_pane" align="center">

                <div id="activity_pane" style="height: 100vh;">
                    <br>
                    <div id="ndtSuppliersBorder">
                        <form>
                            <table border="0">
                                <tr>
                                    <td colspan="3" align="center">  
                                        <font style="font-family:Lucida Handwriting; font-size: 15px;"> 
                                            <b>MMS Supplier Creation</b>
                                        </font>   
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center">&nbsp;   
                                    </td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Supplier Search </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtSupplier" id="txtSupplier" class="inputBox" size="50" onclick="(this.value='')"/></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Supplier Number </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtSupplierNum" id="txtSupplierNum" class="inputBoxClear" size="20" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Store Search </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtStore" id="txtStore" class="inputBox" size="50" onclick="(this.value='')"/></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Store Short </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtStoreShort" id="txtStoreShort" class="inputBoxClear" size="20" readonly="readonly" /></td>
                                </tr>
                            </table>
                        </form>
                            <table border="0">        
                                <tr>
                                    <td colspan="3">
                                        <center>
                                            <br />
                                            <button class="btn btn-success" id="process" onClick="process();" class="btn" value="Analyze"> Process </button>
                                        </center>
                                    </td>
                                </tr>
                            </table>
                        
                        <br>
  
                </div>

            </div>    
            <br />
            <br />
        </body>
</html>