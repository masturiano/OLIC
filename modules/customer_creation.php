<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("customer_creation_obj.php");

$customerObj = new customerObj();

switch($_GET['action']){
    
    case 'searchCustomer':
        $arrResult = array();
            $arrSupplier = $customerObj->findCustomer($_GET['term']);
                foreach($arrSupplier as $val){
                    $arrResult[] = array(
                        "id"=>$val['CUSTOMER_NUMBER'],
                        "label"=>$val['CUSTOMER_NUMBER']." - ".$val['FULL_NAME'],
                        "value" => strip_tags($val['FULL_NAME']));    
                }
        echo json_encode($arrResult);
    exit();    
    break; 
    
    case 'searchStore':
        $arrResult = array();
            $arrStore = $customerObj->findSite($_GET['term']);
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
                               
        $arrData = $customerObj->$_GET['function']($_GET['customerNumber'],$_GET['storeShort']);
        
        $fCont = "";
        $fSeparator = "|";
            
        foreach($arrData as $valFileCont){
            
            $fCont .= trim($valFileCont['CUSTOMER_NUMBER']); #1.Customer number
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STSHRT']); #2.Store short
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['FULL_NAME']); #3.Store short
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS1']); #4.Address1
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS2']); #5.Address2
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS3']); #6.Address3
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ADDRESS4']); #7.Address4
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CITY']); #8.City
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['COUNTRY']); #9.Country
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['ZIP_CODE']); #10.Zip code
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CLASS']); #11.Class
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['STCOMP']); #12.Company
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['BILLTO']); #13.Bill to
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['CUSVATCODE']); #14.Customer vat code
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['SITEVATCODE']); #15.Site vat code
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['TIN']); #16.Tin
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['BL']); #17.Business line
            $fCont .= $fSeparator;
            $fCont .= trim($valFileCont['FILENAME']); #18.Filename
            $fCont .= $fSeparator;
            $fCont .= "\r\n";
        
            $fileName = $valFileCont['FILENAME'];
        }
        
        $destiFoldr = "../exported_file_customer/".$fileName; 
    
        
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
                var txtCustomer = $("#txtCustomer").val();
                var txtCustomerNumber = $("#txtCustomerNum").val();
				var txtStoreShort = $("#txtStoreShort").val();

                if(txtCustomer == ''){
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
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataPj',
                    beforeSend: function()
                    {
                        jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
                    },
                    success: function(data1){
                        processPgTextfile(txtCustomerNumber,txtStoreShort);
                    }
                });    
			}
            
            function processPgTextfile(txtCustomerNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataPg',
                    success: function(data1){
                        processPcTextfile(txtCustomerNumber,txtStoreShort);
                    }
                });   
            }
            
            function processPcTextfile(txtCustomerNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataPc',
                    success: function(data1){
                        processDlTextfile(txtCustomerNumber,txtStoreShort);
                    }
                });   
            }
            
            function processDlTextfile(txtCustomerNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataDi',
                    success: function(data1){
                        processFlTextfile(txtCustomerNumber,txtStoreShort);
                    }
                });   
            }
            
            function processFlTextfile(txtCustomerNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataFl',
                    success: function(data1){
                        processGtTextfile(txtCustomerNumber,txtStoreShort);
                    }
                });   
            }
            
            function processGtTextfile(txtCustomerNumber,txtStoreShort)
            {   
                $.ajax
                    ({
                    url: 'customer_creation.php',
                    type: 'GET',
                    data: 'action=createTextFile&customerNumber='+txtCustomerNumber+'&storeShort='+txtStoreShort+'&function=viewDataGt',
                    success: function(data1){
                        jQuery('#activity_pane').hideLoading();
                        $().toastmessage('showToast', {
                            text: 'Textfile created for supplier number '+txtCustomerNumber,
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
                $("#txtCustomer").autocomplete({
                    source: "customer_creation.php?action=searchCustomer",
                    minLength: 1,
                    select: function(event, ui) {    
                        var content = ui.item.id.split("|");
                        $("#txtCustomerNum").val(content[0]);
                    }
                }); 
            });
            
            $(function(){
                $("#txtStore").autocomplete({
                    source: "customer_creation.php?action=searchStore",
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
                                            <b>MMS Customer Creation</b>
                                        </font>   
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center">&nbsp;   
                                    </td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Customer Search </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtCustomer" id="txtCustomer" class="inputBox" size="50" onclick="(this.value='')"/></td>
                                </tr>
                                <tr>
                                    <td><font style="font-size:13px"><b> Customer Number </b></font></td>
                                    <td><font style="font-size:13px"><b> : </b></font></td>
                                    <td><input type="text" name="txtCustomerNum" id="txtCustomerNum" class="inputBoxClear" size="20" readonly="readonly" /></td>
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