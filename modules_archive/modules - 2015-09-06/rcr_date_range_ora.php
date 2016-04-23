<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("rcr_date_range_ora_obj.php");

$rcrDateRangeObj = new rcrDateRangeObj();

switch($_POST['action']){
	
	case "mmsData":
		$rcrDateRangeObj->mmsData($_POST['getTxtDateFrom'],$_POST['getTxtDateTo'],$_POST['orgId']);
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$rcrDateRangeObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$rcrDateRangeObj->oracleData($_POST['orgId']);
	exit();
	break;	
	
	case "remvLoadedInv":
		$rcrDateRangeObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileNameDuplicate":
		$rcrDateRangeObj->updFileNameDuplicate($_POST['orgId']);
	exit();
	break;	
    
    case "updFileNameCancelled":
        $rcrDateRangeObj->updFileNameCancelled($_POST['orgId']);
    exit();
    break;    
    
    case "updFileNameLoad":
        $rcrDateRangeObj->updFileNameLoad($_POST['orgId']);
    exit();
    break;    
	
	case "createTxtFile":
	
		$arrFileName = $rcrDateRangeObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['Col040'];
			
			$arrFileCont = $rcrDateRangeObj->viewRcr($fileName);
			
			$fCont = "";
			
			foreach($arrFileCont as $valFileCont){
				
				$fCont .= trim($valFileCont['Col001'])."|";
				$fCont .= trim($valFileCont['Col002'])."|";
				$fCont .= trim($valFileCont['Col003'])."|";
				$fCont .= trim($valFileCont['Col004'])."|";
				$fCont .= trim($valFileCont['Col005'])."|";
				$fCont .= trim($valFileCont['Col006'])."|";
				$fCont .= trim(stripslashes($valFileCont['Col007']))."|";
				$fCont .= trim($valFileCont['Col008'])."|";
				$fCont .= trim($valFileCont['Col009'])."|";
				$fCont .= trim($valFileCont['Col010'])."|";
				$fCont .= trim($valFileCont['Col011'])."|";
				$fCont .= trim($valFileCont['Col012'])."|";
				$fCont .= trim($valFileCont['Col013'])."|";
				$fCont .= trim($valFileCont['Col014'])."|";
				$fCont .= trim($valFileCont['Col015'])."|";
				$fCont .= trim($valFileCont['Col016'])."|";
				$fCont .= trim($valFileCont['Col017'])."|";
				$fCont .= trim($valFileCont['Col018'])."|";
				$fCont .= trim($valFileCont['Col019'])."|";
				$fCont .= trim($valFileCont['Col020'])."|";
				$fCont .= trim($valFileCont['Col021'])."|";
				$fCont .= trim($valFileCont['Col022'])."|";
				$fCont .= trim($valFileCont['Col023'])."|";
				$fCont .= trim($valFileCont['Col024'])."|";
				$fCont .= trim($valFileCont['Col025'])."|";
				$fCont .= trim($valFileCont['Col026'])."|";
				$fCont .= trim($valFileCont['Col027'])."|";
				$fCont .= trim($valFileCont['Col028'])."|";
				$fCont .= trim($valFileCont['Col029'])."|";
				$fCont .= trim($valFileCont['Col030'])."|";
				$fCont .= trim($valFileCont['Col031'])."|";
				$fCont .= trim($valFileCont['Col032'])."|";
				$fCont .= trim($valFileCont['Col033'])."|";
				$fCont .= trim($valFileCont['Col034'])."|";
				$fCont .= trim($valFileCont['Col035'])."|";
				$fCont .= trim($valFileCont['Col036'])."|";
				$fCont .= trim($valFileCont['Col037'])."|";
				$fCont .= trim($valFileCont['Col038'])."|";
				$fCont .= trim($valFileCont['Col039'])."|";
				$fCont .= trim($valFileCont['Col040'])."|";
				$fCont .= trim($valFileCont['Col041']);
				$fCont .= "\r\n";
			}
			
			$destiFoldr = "../exported_file/".$fileName; 
		
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
        
        <script type="text/javascript">
			function process()
			{
                var orgId = $("#cmbOrgId").val();
                var dteProc = $("#datepicker").val();
				var dteProc2 = $("#datepicker2").val();

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
                
                if(dteProc == '' || dteProc2 == ''){
                    $().toastmessage('showToast', {
                        text: 'Please select date range!',
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
                
                if(dteProc > dteProc2){
                    $().toastmessage('showToast', {
                        text: 'Date to must be greater then date from!',
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
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=mmsData&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
					},
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copy 301 (RCR) data success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process2(dteProc,dteProc2,orgId);
					}
				});	
				
			}
			
			function process2(dteProc,dteProc2,orgId)
			{
				$.ajax
				({
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Removing spaces of invoices success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process3(dteProc,dteProc2,orgId);
					}
				});	
			}
			
			function process3(dteProc,dteProc2,orgId)
			{
				$.ajax
				({
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=oracleData&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copying of oracle data success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process4(dteProc,dteProc2,orgId);
					}
				});	
			}
			
			function process4(dteProc,dteProc2,orgId)
			{
				$.ajax
				({
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=remvLoadedInv&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Removing loaded invoices success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process5(dteProc,dteProc2,orgId);
					}
				});	
			}
			
			function process5(dteProc,dteProc2,orgId)
			{
				$.ajax
				({
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=updFileNameDuplicate&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Updating filename duplicate success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process6(dteProc,dteProc2,orgId);
					}
				});	
			}
            
            function process6(dteProc,dteProc2,orgId)
            {
                $.ajax
                ({
                    url: 'rcr_date_range_ora.php',
                    type: 'POST',
                    data: 'action=updFileNameCancelled&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
                    success: function(data1){
                        $().toastmessage('showToast', {
                            text: 'Updating filename cancelled success!',
                            sticky: false,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            }
                        });
                        process7(dteProc,dteProc2,orgId);
                    }
                });    
            }
            
            function process7(dteProc,dteProc2,orgId)
            {
                $.ajax
                ({
                    url: 'rcr_date_range_ora.php',
                    type: 'POST',
                    data: 'action=updFileNameLoad&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
                    success: function(data1){
                        $().toastmessage('showToast', {
                            text: 'Updating filename for loading success!',
                            sticky: false,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            }
                        });
                        process8(dteProc,dteProc2,orgId);
                    }
                });    
            }
			
			function process8(dteProc,dteProc2,orgId)
			{
				$.ajax
				({
					url: 'rcr_date_range_ora.php',
					type: 'POST',
					data: 'action=createTxtFile&getTxtDateFrom='+dteProc+'&getTxtDateTo='+dteProc2+'&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Textfile created!',
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
			
			$(function(){
				$( "#datepicker" ).datepicker
				({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'dd-M-yy'
				});
			});
            
            $(function(){
                $( "#datepicker2" ).datepicker
                ({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-M-yy'
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
			
		</style>
    </head>    
        <body>
            <div id='header' align="center"></div>
        	<? include('menu.php'); ?>
            <div id="activity_pane" align="center">
                <br />
                <table border="0">
                    <tr>
                        <td colspan="3" align="center">  
                            <font style="font-family:Lucida Handwriting; font-size: 15px;"> 
                                <b>301 - RCR File Date Range Ora</b>
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
                        <td><? $rcrDateRangeObj->DropDownMenu($arrOrgId,'cmbOrgId','','class="selectBox"'); ?></td>
                    </tr>
                    <tr>
                        <td><font style="font-size:13px"><b> MMS File Date From </b></font></td>
                        <td><font style="font-size:13px"><b> : </b></font></td>
                        <td colspan="2">
                            <input type="text" name="datepicker" id="datepicker" class="inputBox" value="<?=$datepick?>" size="17"
                            placeholder="SELECT DATE FROM" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td><font style="font-size:13px"><b> MMS File Date To </b></font></td>
                        <td><font style="font-size:13px"><b> : </b></font></td>
                        <td colspan="2">
                            <input type="text" name="datepicker2" id="datepicker2" class="inputBox" value="<?=$datepick?>" size="17"
                            placeholder="SELECT DATE TO" readonly>
                        </td>
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
            </div>
        </body>
</html>