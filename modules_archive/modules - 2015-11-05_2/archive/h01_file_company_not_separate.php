<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("h01_file_obj.php");

$h01FileObj = new h01FileObj();

switch($_POST['action']){
	
	case "h01Data":
		
		$directory="C:/wamp/www/OLIC/h01_data/";
			$arch_directory="C:/wamp/www/OLIC/h01_archive/";
			// create a handler to the directory
			//$dirhandler = opendir($directory);
			// read all the files from directory
			$nofiles=0;
			$checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

			if ($checkEmpty == "Empty"){
				echo "0";
				exit();
			}else{
				$h01FileObj->clearTblH01Invoice();
				if ($dirhandler = opendir($directory)) {
					while ($file = readdir($dirhandler)) {
						$file_ext = explode('.',$file);
						$max_val = count($file_ext);
						$file_ext = $file_ext[($max_val-1)];
						$ermsg = "";
	
						if($file_ext == "H01"){
							
							$csv_file = $directory.$file;
							if (($handle = fopen($csv_file, "r")) !== FALSE) {
								
								//fgetcsv($handle);//Adding this line will skip the reading of th first line from the csv file and the reading process will begin from the second line onwards
								while (($data = fgetcsv($handle, 10000000, "|")) !== FALSE) {
									$num = count($data);
									//echo "<p> $num fields in line $row: <br /></p>\n";
									$row++;
									for ($c=0; $c < $num; $c++) {
										//echo $data[$c] . "\n";
										$col1 = trim($data[0]);
										$col2 = trim($data[1]);
										$col3 = trim($data[2]);
										$col4 = trim($data[3]);
										$col5 = trim($data[4]);
										$col6 = trim($data[5]);
										$col7 = trim($data[6]);
										$col8 = trim($data[7]);
										$col9 = trim($data[8]);
										$col10 = trim($data[9]);
										$col11 = trim($data[10]);
										$col12 = trim($data[11]);
										$col13 = trim($data[12]);
										$col14 = trim($data[13]);
										$col15 = trim($data[14]);
										$col16 = trim($data[15]);
										$col17 = trim($data[16]);
										$col18 = trim($data[17]);
										$col19 = trim($data[18]);
										//$col7 = "'".trim(str_replace("'","''",date('m/d/Y',strtotime($data[6]))))."'";	
									}
									$h01FileObj->h01Data($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18);
								}	
								if($loaded = $h01FileObj->displayLoadedRec()){
									echo "
									$().toastmessage('showToast', {
									text     : '<b>Filename: </b> ".$file." data successfully imported to database! <br>"
									.'<font color="#00FF00"><b>Uploaded Rec.:</b></font> '.$loaded['loaded']."',
									sticky   : false,
									position : 'middle-center',
									type     : 'success',
									close    : function () {console.log('toast is closed ...');}
									});
									";
								}
							}
							copy($directory.$file, $arch_directory.$file);
						}
						//echo "$().toastmessage('showSuccessToast','File data successfully imported to database!');";
					}
					
					//$perksObj->inserttblTxtfileTemp($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9);
					
					//echo "alert('File data successfully imported to database!!')";
					
					//mssql_close($connect);
				}
				closedir($handle);
				
				$dir = 'C:/wamp/www/OLIC/h01_data/';
				foreach(glob($dir.'*.H01*') as $v){
					unlink($v);
				}
				foreach(glob($dir.'*.H01*') as $v){
					unlink($v);
				}
				/*if ($checkEmpty == "Not empty"){
				
				}*/
			}
		
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$h01FileObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$h01FileObj->oracleData();
	exit();
	
	case "remvLoadedInv":
		$h01FileObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileName":
		$h01FileObj->updFileName();
	exit();
	break;	
	
	case "createTxtFile":
	
		$arrFileName = $h01FileObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['filename'];
			
			$arrFileCont = $h01FileObj->viewH01($fileName);
			
			$fCont = "";
			
			foreach($arrFileCont as $valFileCont){
				
				$fCont .= trim($valFileCont['invoice'])."|";
				$fCont .= trim($valFileCont['invoice_date'])."|";
				$fCont .= trim($valFileCont['trxn_type'])."|";
				$fCont .= trim($valFileCont['strshrt'])."|";
				$fCont .= trim($valFileCont['col005'])."|";
				$fCont .= trim($valFileCont['strshrt2'])."|";
				$fCont .= trim($valFileCont['seq'])."|";
				$fCont .= trim($valFileCont['col008'])."|";
				$fCont .= trim($valFileCont['type'])."|";
				$fCont .= trim($valFileCont['gl_date'])."|";
				$fCont .= trim($valFileCont['col011'])."|";
				$fCont .= trim($valFileCont['col012'])."|";
				$fCont .= trim($valFileCont['col013'])."|";
				$fCont .= trim($valFileCont['amount'])."|";
				$fCont .= trim($valFileCont['col015'])."|";
				$fCont .= trim($valFileCont['curency'])."|";
				$fCont .= trim($valFileCont['col017'])."|";
				$fCont .= trim($valFileCont['filename'])."|";
				$fCont .= trim($valFileCont['col019']);
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
				
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=h01Data',
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
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
								text: 'Copy H01 data success!',
								sticky: false,
								position: 'middle-center',
								type: 'success',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								}
							});
							process2();
						}
						eval(data1);
					}
				});	
			}
			
			function process2()
			{
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace',
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
						process3();
					}
				});	
			}
			
			function process3()
			{
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=oracleData',
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
						process4();
					}
				});	
			}
			
			function process4()
			{
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=remvLoadedInv',
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
						process5();
					}
				});	
			}
			
			function process5()
			{
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=updFileName',
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
						process6();
					}
				});	
			}
			
			function process6()
			{
				$.ajax
				({
					url: 'h01_file.php',
					type: 'POST',
					data: 'action=createTxtFile',
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
					<br>
                    <table border="0">
                    	<th colspan="2">
							<h3 align="center"><font style="font-family:Lucida Handwriting"> H01 (Textfiles) </font></h3>
                        </th>
                        <tr>
                            <td colspan="2">
                                <center>
                                    <br />
                                    <button class="btn btn-success" onClick="process();" value="Analyze">  Process  </button>
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