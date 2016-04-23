<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("rtv_file_obj.php");

$olicDailyObj = new olicDailyObj();

switch($_POST['action']){
	
	case "olicData":
    
         if($_POST['orgId'] == '87'){
            $directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_pj_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_pj_archive/";    
        }else if($_POST['orgId'] == '85'){
            $directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_pg_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_pg_archive/";  
        }else if($_POST['orgId'] == '133'){
            $directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_sbc_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_sbc_archive/";  
        }else if($_POST['orgId'] == '153'){
            $directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_di_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_di_archive/";  
        }else if($_POST['orgId'] == '113'){
            $directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_fl_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/rtv/rtv_fl_archive/";  
        }else{
            $directory="";  
            $arch_directory="";
        }
			// create a handler to the directory
			//$dirhandler = opendir($directory);
			// read all the files from directory
			$nofiles=0;
			$checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

			if ($checkEmpty == "Empty"){
				echo "0";
				exit();
			}else{
				$olicDailyObj->clearTblOlicInvoice();
				if ($dirhandler = opendir($directory)) {  
					while ($file = readdir($dirhandler)) {
                        substr($file,0,2);
						$file_ext = explode('.',$file);
						$max_val = count($file_ext);
						$file_ext = $file_ext[($max_val-1)];
						$ermsg = "";

						//echo "$().toastmessage('showSuccessToast','File data successfully imported to database!');";
					}
					
					//$perksObj->inserttblTxtfileTemp($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9);
					
					//echo "alert('File data successfully imported to database!!')";
					
					//mssql_close($connect);
				}
				closedir($handle);
				
				foreach(glob($directory.'*.901*') as $v){
					unlink($v);
				}
				foreach(glob($directory.'*.901*') as $v){
					unlink($v);
				}
				/*if ($checkEmpty == "Not empty"){
				
				}*/
			}
		
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$olicDailyObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$olicDailyObj->oracleData($_POST['orgId']);
	exit();
	
	case "remvLoadedInv":
		$olicDailyObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileName":
		$olicDailyObj->updFileName($_POST['orgId']);
	exit();
	break;	
	
	case "createTxtFile":
	
		$arrFileName = $olicDailyObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['Col040'];
			
			$arrFileCont = $olicDailyObj->viewOlic($fileName);
			
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
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=olicData&orgId='+orgId,
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
					},
					success: function(data1){
						if(data1 == 0){
							$().toastmessage('showToast', {
								text: 'Folder is empty!',
								sticky: false,
								position: 'middle-center',
								type: 'warning',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								jQuery('#activity_pane').hideLoading();
								}
							});
						}else{
							$().toastmessage('showToast', {
								text: 'Copying of source data success!',
								sticky: false,
								position: 'middle-center',
								type: 'success',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								}
							});
							<!-- process2(orgId); -->
						}
						eval(data1);
					}
				});	
			}
			
			function process2(orgId)
			{
				$.ajax
				({
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Remove spaces of invoices success!',
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
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=oracleData&orgId='+orgId,
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
						process4(orgId);
					}
				});	
			}
			
			function process4(orgId)
			{
				$.ajax
				({
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=remvLoadedInv&orgId='+orgId,
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
						process5(orgId);
					}
				});	
			}
			
			function process5(orgId)
			{
				$.ajax
				({
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=updFileName&orgId='+orgId,
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
						process6(orgId);
					}
				});	
			}
			
			function process6(orgId)
			{
				$.ajax
				({
					url: 'rtv_file_testing.php',
					type: 'POST',
					data: 'action=createTxtFile&orgId='+orgId,
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
                                <b>901 - RTV (Textfiles)</b>
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
                        <td><? $olicDailyObj->DropDownMenu($arrOrgId,'cmbOrgId','','class="selectBox"'); ?></td>
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