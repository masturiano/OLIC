<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IIC</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo $base_url; ?>css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />


<link rel="icon" type="image/ico" href="<?php echo $base_url; ?>images/oraicon.png">
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/style.css"/>
<link rel='stylesheet' type='text/css' href='<?php echo $base_url; ?>css/skeleton/skeleton.css' />

<script type='text/javascript' src='<?php echo $base_url; ?>js/jquery-1.8.0.min.js'></script>
<script type='text/javascript' src='<?php echo $base_url; ?>js/jquery-ui-1.8.23.custom.min.js'></script>

<script type='text/javascript' src='<?php echo $base_url; ?>js/jquery.dataTables.js'></script>
<script type='text/javascript' src='<?php echo $base_url; ?>js/jquery.dataTables.min.js'></script>

<style type="text/css" title="currentStyle">
	@import "./css/demo_page.css";
	@import "./css/demo_table_jui.css";
	@import "./css/smoothness/jquery-ui-1.8.4.custom.css";
</style>

<!-- jqgrid 
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $base_url; ?>css/ui.jqgrid.css" />
<script src="<?php echo $base_url; ?>js/jqgrid/grid.locale-en.js" type="text/javascript"></script>
<script src="<?php echo $base_url; ?>js/jqgrid/jquery.jqGrid.min.js" type="text/javascript"></script>
 end -->

<script type="text/javascript">
$(document).ready(function () {

	//Initialize Process Data
	$( "#process_file").hide();
	$("#processfiles").click(function(){
		$( "#process_file").dialog({
			width:550,
			height:250,
			modal: true,
			buttons: {
				Yes: function() {
					$.ajax({
						url:"<?php echo $base_url; ?>modules/process/processfile_data.php",
						type:"POST",
						data:{initialize:"yes"},
						beforeSend: showprogress('open'),
						success: function(data){
							eval(data);
							showprogress('close');
							$( this ).dialog( "close" );
						}
					});
					
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	//END
	//Initialize Process Oracle Data
	$( "#process_oracle").hide();
	$("#process_ofiles").click(function(){
		$( "#process_oracle").dialog({
			width:550,
			height:250,
			modal: true,
			buttons: {
				Yes: function() {
					$.ajax({
						url:"<?php echo $base_url; ?>modules/process/processoraclefile_data.php/",
						type:"POST",
						data:{initialize:"yes"},
						beforeSend: showprogress('open'),
						success: function(data){
							eval(data);
							showprogress('close');
							$( this ).dialog( "close" );
						}
					});
					
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	//END
	//Create AP text file for not loaded invoices
	$( "#process_ap_file").hide();
	$("#createtxtap").click(function(){
		$( "#process_ap_file").dialog({
			width:550,
			height:250,
			modal: true,
			buttons: {
				Yes: function() {
					$.ajax({
						url:"modules/create_AP_textfile.php",
						type:"POST",
						data:{initialize:"yes"},
						beforeSend: showprogress('open'),
						success: function(data){
							eval(data);
							showprogress('close');
							$( this ).dialog( "close" );
						}
					});
					
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	//END
	
	//oracle data to local tables
	//Initialize Process Data
	$( "#process_oradata").hide();
	$("#copyoradata").click(function(){
		$("#orafrm").datepicker();
		$("#orato").datepicker();
		$( "#process_oradata").dialog({
			width:350,
			height:250,
			modal: true,
			buttons: {
				Yes: function() {
					var orafrm = $("#orafrm").val();
					var orato = $("#orato").val();
					$.ajax({
						url:"<?php echo $base_url; ?>modules/process/oracelcopydata.php",
						type:"POST",
						data:{initialize:"yes",orafrm:orafrm,orato:orato},
						beforeSend: showprogress('open'),
						success: function(data){
							eval(data);
							showprogress('close');
							$( this ).dialog( "close" );
						}
					});
					
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	
	//oracle data to local tables AR
	//Initialize Process Data
	$( "#process_oradataar").hide();
	$("#copyoradataar").click(function(){
		$("#orafrmar").datepicker();
		$("#oratoar").datepicker();
		$( "#process_oradataar").dialog({
			width:350,
			height:250,
			modal: true,
			buttons: {
				Yes: function() {
					var orafrm = $("#orafrmar").val();
					var orato = $("#oratoar").val();
					$.ajax({
						url:"<?php echo $base_url; ?>modules/process/oracelcopydataAR.php",
						type:"POST",
						data:{initialize:"yes",orafrm:orafrm,orato:orato},
						beforeSend: showprogress('open'),
						success: function(data){
							eval(data);
							showprogress('close');
							$( this ).dialog( "close" );
						}
					});
					
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	
	//loading progress load
	$( "#progress_load").hide();
	function showprogress(action)
	{
		if(action == "open")
		{
			$( "#progress_load").dialog({
				width:250,
				height:110,
				modal: true,
				closeOnEscape: false,
				beforeClose: function(event, ui) {
						return false;
					}
			});
		}
		else
		{
			$( "#progress_load").dialog( 'close' );
			$( "#progress_load").dialog( "destroy" );
		}
	}
	//END
});
</script> 

<script>
// increase the default animation speed to exaggerate the effect
$.fx.speeds._default = 1000;
$(function() {
	$( "#dialog" ).dialog({
		autoOpen: false,
		show: "blind",
		width: "700",
		height: "500",
		hide: "explode"
	});
	
	$( "#dialog2" ).dialog({
		autoOpen: false,
		width: "700",
		height: "500",
		show: "blind",
		hide: "explode"
	});

	$( "#opener" ).click(function() {
		$( "#dialog" ).dialog( "open" );
		return false;
	});
	
	$( "#opener2" ).click(function() {
		$( "#dialog2" ).dialog( "open" );
		return false;
	});
});
</script>

<!-- END ADD BY MYDEL -->

</head>   
<body>

<div class="header_container">
	<center><div id='header'></div></center>
</div>
<div class="menu">
	<center>
	<ul id='nav'>
		<li><a href='#' title="home"></a></li>

		<li><a href='#' title="admin">Admin &#187;</a>
			<ul>
				<li><a href='#?process=1' id="processfiles" title="Process data">Process Data</a></li>
				<li><a href='#' id="process_ofiles" title="Process data">Process Oracle Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_ndt' id="move_files" title="FTP Move Data">FTP Move NDT PG-JR Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_ndt_ppci' id="move_files" title="FTP Move Data">FTP Move NDT PPCI Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_ndt_subic' id="move_files" title="FTP Move Data">FTP Move NDT SUBIC Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data' id="move_files" title="FTP Move Data">FTP Move AP PG-JR Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_ar' id="move_files_" title="FTP Move Data">FTP Move AR PG-JR Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_gl' id="move_files_" title="FTP Move Data">FTP Move GL PG-JR Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_gl_ppci' id="move_files_" title="FTP Move Data">FTP Move GL PPCI Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_ppci' id="move_files" title="FTP Move Data">FTP Move AP PPCI Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_ppci_ar' id="move_files" title="FTP Move Data">FTP Move AR PPCI Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_sup' id="move_files" title="FTP Move Data">FTP Move SUPPLIER PJ Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_sup_ppci' id="move_files" title="FTP Move Data">FTP Move SUPPLIER PPCI Data</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_sup_subic' id="move_files" title="FTP Move Data">FTP Move SUPPLIER SUBIC Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_cus' id="move_files" title="FTP Move Data">FTP Move CUSTOMER PJ Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_cus_ppci' id="move_files" title="FTP Move Data">FTP Move CUSTOMER PPCI Data</a></li>
				 <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_subic' id="move_files" title="FTP Move Data">FTP Move AP Subic Data</a></li>
				 <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_subic_ar' id="move_files" title="FTP Move Data">FTP Move AR Subic Data</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=ftp_data_gl_subic' id="move_files_" title="FTP Move Data">FTP Move GL SUBIC Data</a></li>
				</li>
			</ul>
		</li>
		<li><a href='#' title="Check Integrity">Check Integrity &#187 </a>
			<ul>
				<li><a href='#' title="Not Loaded Invoice">NOT Loaded Invoice</a>
						<ul>
							<li><a href='<?php echo $base_url; ?>index.php?cpage=ap_not_loaded' id='process_ap_xload' title="AP Invoice">AP INVOICE</a></li>
                            <li><a href='#' title="AR Invoice">AR INVOICE</a></li>
						</ul>
				</li>
				<li><a href='#' title="record donate">GENERATE TEXTFILE &#187 </a>
						<ul>
							<!-- <li><a href='<?php echo $base_url; ?>index.php?cpage=create_AP_textfile' title="AP TEXTFILE">AP TEXTFILE</a></li> -->
							<li><a href='#' id="createtxtap" title="AP TEXTFILE">AP TEXTFILE</a></li>
							<li><a href='#' title="ARTEXTFILE">AR TEXTFILE</a>
								<ul>
									<li><a href='<?php echo $base_url; ?>index.php?cpage=create_AR_textfile' title="AR TEXTFILE">A01 TEXTFILE</a></li>
								</ul>
							</li>      
							<li><a href='<?php echo $base_url; ?>index.php?cpage=BalanceGL' title="Balance GL Textfile">BALANCE GL TEXTFILE</a></li>
							<li><a href='<?php echo $base_url; ?>index.php?cpage=create_customer_update' title="Create Customer Update">CREATE CUSTOMER UPDATE</a></li>
                            <li><a href='<?php echo $base_url; ?>index.php?cpage=create_supplier_update' title="Create Supplier Update">CREATE SUPPLIER UPDATE</a></li>
                            <li><a href='<?php echo $base_url; ?>index.php?cpage=mms_posted_GL' title="Check RELEASED GL">CHECK RELEASED GL</a></li>
                            <li><a href='<?php echo $base_url; ?>index.php?cpage=mms_posted_AR' title="Check RELEASED AR">CHECK RELEASED AR</a></li>
						</ul>
				</li> 
				<li><a href='<?php echo $base_url; ?>index.php?cpage=invoice_check' id="Invoice Check" title="Invoice Check">INVOICE CHECK</a></li>
			</ul>
		</li>
		<li><a href='#' title="admin">DC PROCESS DATA &#187;</a>
			<ul>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=mms_ar_data' id="mms_ar_data" title="MMS AR to Local Table">MMS AR to Local Table</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=mms_ap_data' id="mms_ar_data" title="MMS AP to Local Table">MMS AP to Local Table</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=mms_ap_inv_data' id="mms_ar_data" title="MMS AP INVOICE to Local Table">MMS AP INVOICE to Local Table</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=ora_ap_data' id="copyoradataselection" title="Copy Oracle Data AP">Copy Oracle Data to Local table AP (Selection)</a></li>
           		<li><a href='<?php echo $base_url; ?>index.php?cpage=text_ap_data' id="copytextdataselection" title="Copy Oracle Data AP">Copy Textfile Data to Local table AP (Selection)</a></li>
				<li><a href='#' id="copyoradata" title="Copy Oracle Data AP">Copy Oracle Data to Local table AP</a></li>
				<li><a href='#' id="copyoradataar" title="Copy Oracle Data AR">Copy Oracle Data to Local table AR</a></li>
			</ul>
		</li>
		<li><a href='#' title="admin">CREATE TEXTFILE &#187;</a>
			<ul>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=dc_create_ar' id="create_ao1" title="Create A01">Create A01</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=dc_create_ap' id="create_mtina" title="Create AP MTina">Create 301_901_001</a></li>
				<li><a href='<?php echo $base_url; ?>index.php?cpage=dc_create_gl' id="create_mtinaGl" title="Create GL MTina">Create GL</a></li>
                <li><a href='<?php echo $base_url; ?>index.php?cpage=dc_create_ap2' title="Create AP MTina">Create 301/901/001 (Open Query)</a></li>
                
			</ul>
		</li>
		<!--<li><a href='<?php echo $base_url; ?>index.php?cpage=all_loaded_invoices' title="List of Invoices">Invoices(MMS)</a></li> -->
		<li><a href='<?php echo $base_url; ?>index.php/home/logout/' title="logout">Logout</a></li>
	</ul>
	</center>
</div>
<!-- Initialize Process data-->
<div id="process_file" title="Importing Files to tables">
	<p>Initialize Processing of Data Files to tables.</p>
    <p>Proceed ?</p>
</div>
<!-- END -->

<!-- Initialize Process oracle Data -->
<div id="process_oracle" title="Importing Files to tables">
	<p>Initialize Processing of Oracle Data Files to tables.</p>
    <p>Proceed ?</p>
</div>
<!-- END -->

<!-- Create AP textfile -->
<div id="process_ap_file" title="Importing Files to tables">
	<p>Create AP Textfile for Not loaded invoices.</p>
    <p>Proceed ?</p>
</div>
<!-- END -->

<!-- Initialize Process oracle Data -->
<div id="migrate_data_dialog" title="Migrating Data">
	<p>Were Going to Migrate Text file.</p>
    <p>continue?</p>
</div>
<!-- END -->
<!-- Initialize Copy oracle Data to local table -->
<div id="process_oradata" title="Copy Oracle Data to Local Table">
	<p>Were Going to Copy Oracle data to Local table.</p>
		<table>
			<tr>
				<td>FROM : (dd-mm-YYYY)</td><td><input type="text" name="orafrm" id="orafrm" value="" /></td>
			</tr>
			<tr>
				<td>TO : (dd-mm-YYYY)</td><td><input type="text" name="orato" id="orato" value="" /></td>
			</tr>
		</table>
</div>
<!-- END -->
<!-- Initialize Copy oracle Data to local table -->
<div id="process_oradataar" title="Copy Oracle Data to Local Table">
	<p>Were Going to Copy Oracle data to Local table.</p>
	<table>
		<tr>
			<td>FROM : (dd-mm-YYYY)</td><td><input type="text" name="orafrm" id="orafrmar" value="" /></td>
		</tr>
		<tr>
			<td>TO : (dd-mm-YYYY)</td><td><input type="text" name="orato" id="oratoar" value="" /></td>
		</tr>
	</table>
</div>
<!-- END -->



<!-- progress loading -->
<div id="progress_load">
	<img src="<?php echo $base_url; ?>images/loading.gif" />

</div>
<!-- END -->




<div id="main_content">
