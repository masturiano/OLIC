<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("rtv_daily_obj.php");

$rtvDailyObj = new rtvDailyObj();

switch($_POST['action']){
	
	case "mmsData":
		$rtvDailyObj->mmsData($_POST['getTxtDate']);
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$rtvDailyObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$rtvDailyObj->oracleData();
	exit();
	break;	
	
	case "remvLoadedInv":
		$rtvDailyObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileName":
		$rtvDailyObj->updFileName();
	exit();
	break;	
	
	case "createTxtFile":
	
		$arrFileName = $rtvDailyObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['Col040'];
			
			$arrFileCont = $rtvDailyObj->viewRtv($fileName);
			
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
				var dteProc = $( "#datepicker" );

				if(dteProc.val() == ''){
					$().toastmessage('showToast', {
						text: 'Please select date!',
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
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=mmsData&getTxtDate='+dteProc.val(),
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
					},
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copy 901 (RTV) data success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process2(dteProc);
					}
				});	
				
			}
			
			function process2(dteProc)
			{
				$.ajax
				({
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace&getTxtDate='+dteProc.val(),
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
						process3(dteProc);
					}
				});	
			}
			
			function process3(dteProc)
			{
				$.ajax
				({
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=oracleData&getTxtDate='+dteProc.val(),
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
						process4(dteProc);
					}
				});	
			}
			
			function process4(dteProc)
			{
				$.ajax
				({
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=remvLoadedInv&getTxtDate='+dteProc.val(),
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
						process5(dteProc);
					}
				});	
			}
			
			function process5(dteProc)
			{
				$.ajax
				({
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=updFileName&getTxtDate='+dteProc.val(),
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Updating filename success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process6(dteProc);
					}
				});	
			}
			
			function process6(dteProc)
			{
				$.ajax
				({
					url: 'rtv_daily.php',
					type: 'POST',
					data: 'action=createTxtFile&getTxtDate='+dteProc.val(),
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
			
		</style>
    </head>    
        <body>
            <center>
                <div id='header'>
                </div>
            </center>
        	<? include('menu.php'); ?>
            <center>
            	<div id="activity_pane" style="height: 100vh;">
                    <table border="0">
                    	<th colspan="2">
                        	<h3 align="center"><font style="font-family:Lucida Handwriting"> 901 - RTV (Daily) </font></h3>
                        </th>
                        <tr>
                            <td>
                                MMS Data:
                            </td>
                            <td>
                                <input type="text" name="datepicker" id="datepicker" value="<?=$datepick?>" size="15"
                                placeholder="Select Date" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center>
                                    <br />
                                    <button class="btn btn-success" onClick="process();" value="Analyze"> Process </button>
                                </center>
                            </td>
                        </tr>
                    </table>
                 </div>
            </center>
            <center>
                <div id='footer'></div>
            </center>
        </body>
</html>