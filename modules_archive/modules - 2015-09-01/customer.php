<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("customer_obj.php");

$customerObj = new customerObj();

$arrCustList = $customerObj->viewCustomer();

switch($_POST['action']){
	
	case "mmsData":
		$customerObj->mmsData($_POST['orgId']);
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$customerObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$customerObj->oracleData($_POST['orgId']);
	exit();
	
	case "createTextFile":
    
        if($_POST['orgId'] == '87'){
            $arrData = $customerObj->viewDataPj($_POST['customerNo']);
        }else if($_POST['orgId'] == '85'){
            $arrData = $customerObj->viewDataPg($_POST['customerNo']);
        }else if($_POST['orgId'] == '133'){
            $arrData = $customerObj->viewDataPc($_POST['customerNo']);
        }else if($_POST['orgId'] == '153'){
            $arrData = $customerObj->viewDataDi($_POST['customerNo']);  
        }else if($_POST['orgId'] == '113'){
            $arrData = $customerObj->viewDataFl($_POST['customerNo']);  
        }else{
            $arrData = "Error!";
        }
		
		foreach($arrData as $valData){
			
			$fileName = $valData['FILENAME'];
			
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
			}
			
			$destiFoldr = "../exported_file_customer/".$fileName; 
		
			if (file_exists($destiFoldr)) {
				unlink($destiFoldr);
			}
			
			$handleFromFileName = fopen ($destiFoldr, "x");
			
			fwrite($handleFromFileName, $fCont);
			fclose($handleFromFileName) ;
		}
		
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
        
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.button.js"></script>
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
        
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
				var orgId = $("#cmbOrgId").val();

                if(orgId == '0'){
                    $().toastmessage('showToast', {
                        text: 'Please select company!',
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
					url: 'customer.php',
					type: 'POST',
					data: 'action=mmsData&orgId='+orgId,
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
					},
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copy Customer data success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process2(orgId);
					}
				});	
			}
			
			function process2(orgId)
			{
				$.ajax
				({
					url: 'customer.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Remove spaces of Customer success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process3(orgId);
					}
				});	
			}
			
			function process3(orgId)
			{
				$.ajax
				({
					url: 'customer.php',
					type: 'POST',
					data: 'action=oracleData&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copying of oracle data success!',
							sticky: true,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							document.location.reload();
							}
						});
						jQuery('#activity_pane').hideLoading();
                        $('#process').html('Process');
					}
				});	
			}
            
            function createText(cusnum)
            {
                var orgId = $("#cmbOrgId").val();
                
                if(orgId == '0'){
                    $().toastmessage('showToast', {
                        text: 'Please select company!',
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
                    url: 'customer.php',
                    type: 'POST',
                    data: 'action=createTextFile&orgId='+orgId+'&customerNo='+cusnum,
                    beforeSend: function()
                    {
                        $("#create-"+cusnum).html('CREATING TEXTFILE').css('color','white').css("text-decoration", "blink");
                        $("#create-"+cusnum).attr('disabled','disabled');
                    },
                    success: function(data1){
                        $().toastmessage('showToast', {
                            text: 'Textfile created for customer no. '+cusnum,
                            sticky: true,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            }
                        });
                        $("#create-"+cusnum).html('CREATE TEXTFILE').css("text-decoration", "none");
                        $("#create-"+cusnum).removeAttr('disabled');
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
                $('#customerList').dataTable({
                    "sPaginationType": "full_numbers"
                })   
            });
		</script>
        
        <style type="text/css" title="currentStyle">
			@import "media/jquery/css/demo_page.css";
			@import "media/jquery/css/demo_table_jui.css";
        </style>
        
        <style type="text/css">
			.ui-datepicker-calendar 
			{
				display: none;
			}
			
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
			
			#dataTable{
				width:90%; 
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
            
            .create {
            background: #3498db;
            background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
            background-image: -moz-linear-gradient(top, #3498db, #2980b9);
            background-image: -ms-linear-gradient(top, #3498db, #2980b9);
            background-image: -o-linear-gradient(top, #3498db, #2980b9);
            background-image: linear-gradient(to bottom, #3498db, #2980b9);
            -webkit-border-radius: 2;
            -moz-border-radius: 2;
            font-family: Courier New;
            color: #ffffff;
            font-size: 10px;
            padding: 1px 2px 1px 2px;
            text-decoration: none;
            }

            .create:hover {
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
                width: 220px;
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
            
            #ndtCustomersBorder{
                width:70%
            }
            
            table#customerList {
                border: blue;
            }
            
            table#customerList tr.even td {
            background-color: white;
            }

            table#customerList tr.odd td {
                background-color: skyblue;
            }
            
            table#customerList tr.even:hover td {
                background-color: lightgray;
            }

            table#customerList tr.odd:hover td {
                background-color: lightgray;
            }
		</style>
    </head>    
        <body>
            <div id='header' align="center"></div>
            <? include('menu.php'); ?>
            
                <div id="activity_pane" style="height: 100vh;" align="center">
                <br />       
					<br>
                    <div id="ndtCustomersBorder">
                        <table border="0">
                            <tr>
                                <td colspan="3" align="center">  
                                    <font style="font-family:Lucida Handwriting; font-size: 15px;"> 
                                        <b>MMS Customer not in Oracle</b>
                                    </font>   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">&nbsp;   
                                </td>
                            </tr>
                            <tr>
                                <td><font style="font-size:13px"><b> Company </b></font></td>
                                <td><font style="font-size:13px"><b> : </b></font></td>
                                <td><? $customerObj->DropDownMenu($arrOrgId,'cmbOrgId','','class="selectBox"'); ?></td>
                            </tr>
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
					    
					    <div id="dataTable">
						    <table id="customerList" align="center">
							    <thead>
								    <tr>
                                        <td align="center"><b>CUSTOMER NUMBER</b></td>
                                        <td align="center"><b>CUSTOMER NUMBER</b></td>
                                        <td align="center"><b>ACTION</b></td>
								    </tr>
							    </thead>
						    <tbody>
                                    
								    <?php foreach ($arrCustList as $val) {?>
                                    
								        <tr>
									        <td align="left"><?=$val['customer_number']?></td>
                                            <td align="left"><?=$val['full_name']?></td>
									        <td align="left">
                                                <button class="create" id="<?="create-".$val['customer_number']; ?>" onClick="createText(<? echo $val['customer_number']; ?>);" value="Analyze"> CREATE TEXTFILE </button>
                                                <div id="<?="divCusnum-".$val['customer_number']; ?>" align="center">
                                                </div>
                                            </td>
								        </tr>   
								    <?php }?>
						    </tbody>
						    <tfoot></tfoot>
						</table>   
                 </div>
            </div>
            <br /><br />
        </body>
</html>