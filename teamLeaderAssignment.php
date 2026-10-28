<!doctype html>
<?php
session_start();
error_reporting(0);
    include "php/db.php";
    include "dbconnect.php";
	
	$emm=$_SESSION['userid'];
	$user_type = $_SESSION['user_type']; // get the user type from the database or session
	if($emm==''||$emm==NULL){
		echo "<script>window.open('index.php','_self')</script>";
	}else{
	$sql1= "select empnum from tbl_employment where empid='$emm'";
	$result1 = $con->query($sql1);
	$row1 = $result1->fetch_assoc();
	$id1=$row1['empnum'];
	$sql = "SELECT  distinct e.empfname,e.empmname,e.emplname,e.empext
			FROM tbl_employment a 
			inner join employee_info e using (empnum)
			where a.empnum= '$id1'";
	$result = $con->query($sql);
	$row = $result->fetch_assoc();
?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="images/icons/dswd_icon.jpg">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>DSWD FO XI - ECT Payroll System</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <link href='css/googleapis.css' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />	
	<style>
		body {
		  font-size: 16px;
		}
	</style>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<body>

<div class="wrapper">
   <div class="sidebar" data-color="blue" data-image="assets/img/sidebar-6.jpg">
    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->
	<div class="sidebar-wrapper">
             <div class="logo">
			
                <a href="#" class="simple-text">
                      <img src="images/icons/dswd_logo_white_2.png" style="width:82%; height:82%; margin-left:-3%" alt="..."/>
                    </a>
            </div>
			
			<ul class="nav">
				
				<?php if($user_type=='Team Leader'){ ?>
                <li>
					<a href="dashboard.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Dashboard</span></b> </a>
				</li>
				
				<?php } if($emm=='11-6148'){ ?>
                <li>
					<a href="export_excel.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Import CSV File</span></b> </a>
				</li>
				<?php } if($emm=='11-6148'){ ?>
				 <li>
					<a href="teamLeaderAssignment.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>File Assignment</span></b> </a>
				</li>
				<?php }?>
					<input hidden id="user_id" value="<?php echo $emm;?>"/>
				<?php if($user_type=='Team Leader'||$user_type=='Validator'){ ?>
				<li>
					<a href="validation.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Masterlist</span></b> </a>
				</li>
				<?php }
				if($user_type=='Team Leader'||$user_type=='Tagger'){ ?>
				<li>
					<a href="tagger.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>Tagging</span></b> </a>
				</li>
				<?php }
				 if($emm=='11-6148'){ ?>
				<li>
					<a href="payroll_list.php?userid=<?php echo $emm;?>"><i class="pe-7s-folder"></i><span><b>List of Payroll</span></b> </a>
				</li>
				<?php }
				 if($user_type=='Team Leader'){ ?>
				 <li>
					<a href="#subPages6" data-toggle="collapse" class="collapsed"><i class="pe-7s-user"></i><span><b>Team Leader</span></b> </a>
					<div id="subPages6" class="collapse ">
						<ul>		
							<li >
								<a href="user.php?userid=<?php echo $emm;?>">
									<i class="pe-7s-angle-right"></i>
									<p>User</p>
								</a>
								<a href="fund_source.php?userid=<?php echo $emm;?>">
									<i class="pe-7s-angle-right"></i>
									<p>Fund Source</p>
								</a>
								<!--<a href="category.php?userid=<?php echo $emm;?>">
									<i class="pe-7s-angle-right"></i>
									<p>Category</p>
								</a>
								<a href="subCategory.php?userid=<?php echo $emm;?>">
									<i class="pe-7s-angle-right"></i>
									<p>Sub-Category</p>
								</a>-->
							</li>
						</ul>						
					</div>
				</li>
				<?php } ?>
			</ul>
    </div>
</div>

    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo strtoupper($row["emplname"]).", ".strtoupper($row["empfname"])." ".strtoupper($row["empmname"]); ?> </a>
                </div>
                <div class="collapse navbar-collapse">
                     <ul class="nav navbar-nav navbar-right">
                        
                        <li>
                            <a href="logout.php">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>


         <div class="main-panel" style="width:80%">
		<div class="content" style="width:100%">
            <div class="container-fluid">
			
				<form action="fileAssignment.php" method="post" target="_self" enctype="multipart/form-data">
       
      <div class="table-responsive">  
	  <br>
	  <br>
	  
           <table class="table table-bordered" style="width:80%">		   
				<tr>  
                     <td width="20%"><label  style="font-size: 20px;font-family:Arial Narrow;">PAYOUT DATE FROM</label></td>  
                     <td width="80%" ><input type="date" name="payroll_date_from" class="form-control" /> </td>  
                </tr> 
				<tr>  
                     <td width="20%"><label  style="font-size: 20px;font-family:Arial Narrow;">PAYOUT DATE TO</label></td>  
                     <td width="80%" ><input type="date" name="payroll_date_to" class="form-control" /> </td>  
                </tr> 
				<tr >  
                     <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">SDO</label></td>  
                     <td width="70%" >
						<select class="form-control selectpicker" name="sdo" id="sdo">
							<option disabled selected>----------</option>
							<?php
								$sql1 = "SELECT * FROM tbl_employee order by lname asc";
								$result1 = mysqli_query($con, $sql1);
								while ($row1 = mysqli_fetch_assoc($result1)) {
									echo '<option value="' . $row1['empid'] . '">' . strtoupper($row1['lname'].', '.$row1['fname'].' '.$row1['mname']). '</option>';
								}
							?>
						</select>
					</td>  
                </tr>				
				<tr>  
                     <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">VENUE</label></td>  
                     <td width="70%" ><input type="text" name="venue"  class="form-control"> </input> </td> 
				 </tr> 	
				<tr>  
                     <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">TEAM LEADER</label></td>  
                     <td width="70%" >
						<select class="form-control selectpicker" name="team_leader" id="team_leader" hidden>
							<option disabled selected>----------</option>
							<?php
								$sql3 = "SELECT * FROM tbl_employment as a 
									INNER JOIN employee_info as b on a.empnum = b.empnum
									where empid<>'' and empid<>'xxx' order by emplname asc";
							$result3 = mysqli_query($con, $sql3);
							while ($row3 = mysqli_fetch_assoc($result3)) { ?>
								<option value="<?php echo $row3['empid']; ?>" ><?php echo $row3['emplname'].', '.$row3['empfname'].' '.$row3['empmname']; ?></option>
							<?php }?>
						</select>
					</td>  
                </tr> 
				<tr>  
                     <td width="30%"><label  style="font-size: 20px;font-family:Arial Narrow;">PARTNER</label></td>  
                     <td width="70%" >
						<select class="form-control selectpicker" name="partnerId" id="partnerId" hidden>
							<option disabled selected>----------</option>
							<?php
								$sql3 = "SELECT * FROM lib_fund_source";
								$result3 = mysqli_query($conn, $sql3);
								while ($row3 = mysqli_fetch_assoc($result3)) { ?>
									<option value="<?php echo $row3['id']; ?>" ><?php echo strtoupper($row3['fs_name']); ?></option>
							<?php }?>
						</select>
					</td>  
                </tr>
				  <tr>  
                     <td width="20%"><label  style="font-size: 20px;font-family:Arial Narrow;">SELECT FILE</label></td>  
                     <td width="80%" >
						<select class="form-control selectpicker" name="selectedFile" id="selectedFile" hidden>
							<option disabled selected>----------</option>
							<?php
								$sql = "SELECT * FROM tbl_payroll_list WHERE payroll_status = 'Active'";
								$result = mysqli_query($conn, $sql);

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['payroll_no'] . '">' . $row['file_name'] . '</option>';
								}
							?>
						</select>
					</td> 
				 </tr>
           </table>  
      </div>  
		<div class="modal-footer" style="width:80%">  		 
			<center><input type="submit" name="submit" class="btn btn-primary" value="ASSIGN TEAM LEADER"><center>
		</div>
	  </form>
</div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	

</html>
	<?php }?>

