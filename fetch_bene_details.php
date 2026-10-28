<?php
	header('Content-Type: text/html; charset=UTF-8');
include "dbconnect.php";  // Database connection

if (isset($_POST['bene_id'])) {
    $bene_id = $_POST['bene_id'];

// Fetch beneficiary details
$sql_bene = "SELECT * FROM aics_clean_list WHERE exportStatus IS NULL and control_number = '$bene_id'";
$result_bene = $conn->query($sql_bene);

if ($result_bene->num_rows > 0) {
    echo "<form id='updateStatusForm'>";

    while ($bene = $result_bene->fetch_assoc()) {
		
		
?>

<?php if( $bene['status']==NULL){ ?>
       <div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Control No:</label>
					<input type="text" class="form-control" name="no" value="<?php echo $bene['control_number']; ?>" disabled />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>First Name:</label>
					<input type="text" class="form-control" name="fname" id="fname" value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $bene['first_name']); ?>" disabled />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Middle Name:</label>
					<input type="text" class="form-control" name="mname" id="mname" value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $bene['middle_name']); ?>" disabled />
					<input style="display:none" type="text" class="form-control" name="control_no" id="control_no" value="<?php echo $bene['control_number']; ?>" disabled />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Last Name:</label>
					<input type="text" class="form-control" name="lname" id="lname" value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ', $bene['last_name']); ?>" disabled />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Ext. Name:</label>
					<input type="text" class="form-control" name="ename" id="ename" value="<?php echo $bene['extension_name']; ?>" disabled />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Date of Birth:</label>
					<input type="text" class="form-control" name="dob" id="dob" value="<?php echo $bene['birth_day'].'/'.$bene['birth_month'].'/'.$bene['birth_year']; ?>" disabled />
				</div>
			</div>
		</div>
<?php }
		if( $bene['status']=='Correction'){
			// Fetch beneficiary details
		$sql_bene_cor = "SELECT * FROM aics_clean_list WHERE control_number = '$bene_id'";
		$result_bene_cor = $conn->query($sql_bene_cor);
		 if ($bene_cor = $result_bene_cor->fetch_assoc()) 
			?>
			<div class="row"id="new_details">
		&emsp;<label style="color:green">New Details</label><br>
			<div class="col-md-3">
				<div class="form-group">
					<label>Last Name:</label>
		<input type="text" class="form-control" name="newfname" id="newfname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['last_name'])); ?>" readonly  />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>First Name:</label>
					<input type="text" class="form-control" name="newmname" id="newmname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['first_name'])); ?>" readonly />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Middle Name:</label>
					<input type="text" class="form-control" name="newlname" id="newlname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['middle_name'])); ?>" readonly />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Ext. Name</label>
					<input type="text" class="form-control" name="newdob" id="newdob" value="<?php echo $bene_cor['extension_name']; ?>" readonly />
				</div>
			</div><br>
		</div>
		
		<hr>
		<center>
						<button type='button' class='btn btn-danger disqualify-status-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene_cor['id']; ?>'>Disqualify</button>
						<button type='button' class='btn btn-primary printNewGis equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>GIS</button>
						<button type='button' class='btn btn-primary printInfo equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Information Sheet</button>
						<button type='button' class='btn btn-primary printNewCoe equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>COE</button>
						<button type='button' class='btn btn-primary printJustification equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Justification</button>
					</center>
					<?php }
		if( $bene['status']=='Claimed'){
			// Fetch beneficiary details
		$sql_bene_cor = "SELECT * FROM aics_clean_list WHERE control_number = '$bene_id'";
		$result_bene_cor = $conn->query($sql_bene_cor);
		 if ($bene_cor = $result_bene_cor->fetch_assoc()) 
			?>
			<div class="row"id="new_details">
		&emsp;<label style="color:green">New Details</label><br>
			<div class="col-md-3">
				<div class="form-group">
					<label>Last Name:</label>
		<input type="text" class="form-control" name="newfname" id="newfname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['last_name'])); ?>" readonly  />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>First Name:</label>
					<input type="text" class="form-control" name="newmname" id="newmname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['first_name'])); ?>" readonly />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Middle Name:</label>
					<input type="text" class="form-control" name="newlname" id="newlname" value="<?php echo strtoupper(str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene_cor['middle_name'])); ?>" readonly />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Ext. Name</label>
					<input type="text" class="form-control" name="newdob" id="newdob" value="<?php echo $bene_cor['extension_name']; ?>" readonly />
				</div>
			</div><br>
		</div>
		
		<hr>
		<center>
					<button type='button' class='btn btn-danger disqualify-status-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene_cor['id']; ?>'>Disqualify</button>
						<button type='button' class='btn btn-primary printNewGis equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>GIS</button>
						<button type='button' class='btn btn-primary printInfo equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Information Sheet</button>
						<button type='button' class='btn btn-primary printNewCoe equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>COE</button>
					<button type='button' class='btn btn-primary printJustification equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Justification</button>
	
					</center>
		<?php }
		?>
		<div class="row" id="new_details" hidden>
		&emsp;<label style="color:red">Add New Details</label><br>
			<div class="col-md-3">
				<div class="form-group">
					<input type="hidden" class="form-control" name="payroll_no" id="payroll_no"  required value="<?php echo $bene['payroll_no']; ?>" />
					<label>First Name:</label>
					<input type="text" class="form-control" name="new_fname" id="new_fname"  required value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene['first_name']); ?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Middle Name:</label>
					<input type="text" class="form-control" name="new_mname" id="new_mname" value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene['middle_name']); ?>"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Last Name:</label>
					<input type="text" class="form-control" name="new_lname" id="new_lname" required value="<?php echo str_replace(['Ã','Ã±','ÃÂ','Ã'], 'Ñ',$bene['last_name']); ?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Ext. Name</label>
					<select name="new_ename" id="new_ename"  class="form-control" >
						<option value="<?php echo $bene['extension_name']; ?>" ><?php echo $bene['extension_name']; ?></option>
						<option value="">None</option>
						<option value="Sr">Sr</option>
						<option value="Jr">Jr</option>
						<option value="I">I</option>
						<option value="II">II</option>
						<option value="III">III</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="row" id="new_details2" hidden>
			<div class="col-md-3">
				<div class="form-group">
					<label>Sex</label>
					<select id="sex" name="sex" class="form-control" >
						<option value="<?php echo $bene['sex']; ?>"><?php echo $bene['sex']; ?></option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Birth Day</label>
					<select name="birthday" id="birthday" class="form-control" required>
						<option value="<?php echo $bene['birth_day']; ?>"><?php echo $bene['birth_day']; ?></option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Birth Month</label>
					<select name="birthmonth" id="birthmonth" class="form-control" required>
						<option value="<?php echo $bene['birth_month']; ?>"><?php echo $bene['birth_month']; ?></option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Birth Year</label>
					<select name="birthyear" id="birthyear" class="form-control" required>
						<option  value="<?php echo $bene['birth_year']; ?>"><?php echo $bene['birth_year']; ?></option>
						<!-- JavaScript to create year options from 1900 to 2024 -->
						<script>
							for (let year = 1900; year <= 2024; year++) {
								const option = document.createElement("option");
								option.value = year;
								option.textContent = year;
								document.getElementById("birthyear").appendChild(option);
							}
						</script>
					</select>
				</div>
			</div>
		</div>
		<div class="row" id="new_details3" hidden>
			<div class="col-md-3">
				<div class="form-group">
					<label>Civil Status</label>
					<select id="civil_status" name="civil_status" class="form-control" >
						<option value="<?php echo $bene['civil_status']; ?>"><?php echo $bene['civil_status']; ?></option>
						<option value="Single">Single</option>
						<option value="Married">Married</option>
						<option value="Widowed">Widowed</option>
						<option value="Divorce">Divorce</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Occupation</label>
					<input type="text" class="form-control" name="occupation" id="occupation" value="<?php echo $bene['occupation']; ?>"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Salary</label>
					<input type="number" class="form-control" name="salary" id="salary" value="<?php echo $bene['monthly_salary'] ?? 0; ?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Category</label>
						<select name="category" id="category" class="form-control" >
							<option value="<?php echo $bene['category']; ?>"><?php echo $bene['category']; ?></option>
							<option value="FHONA">FHONA</option> 
							<option value="WEDC">WEDC</option> 
							<option value="YNSP">YNSP</option> 
							<option value="SC">SC</option>  
							<option value="PWD">PWD</option>  
							<option value="PLHHIV">PLHHIV</option>
							<option value="CNSP">CNSP</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row" id="new_details4" hidden>
			<div class="col-md-3">
				<div class="form-group">
					<label>Sub-category</label>
						<select name="sub_category" id="sub_category" class="form-control" >
							<option value="<?php echo $bene['subcategory']; ?>"><?php echo $bene['subcategory']; ?></option>
							<option value="MINIMUM WAGE EARNER">MINIMUM WAGE EARNER</option> 
							<option value="SOLO PARENT">SOLO PARENT</option> 
							<option value="INDIGENOUS PEOPLE">INDIGENOUS PEOPLE</option> 
							<option value="STREET DWELLERS">STREET DWELLERS</option>  
							<option value="4PS DSWD BENEFICIARY">4PS DSWD BENEFICIARY</option>  
							<option value="KIA/WIA">KIA/WIA</option>
							<option value="REFUGEES">REFUGEES</option>
							<option value="STATELESS PERSON">STATELESS PERSON</option>
							<option value="ASYLUM SEEKERS">ASYLUM SEEKERS</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Contact Number</label>
					<input type="number" class="form-control" name="cnum" id="cnum" value="<?php echo $bene['contact_number']; ?>"/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Purok</label>
					<input type="text" class="form-control" name="purok" id="purok" value="<?php echo $bene['purok']; ?>"/>
				</div>
			</div>
		</div>
		
		
		<div class="row" id="new_details5" hidden>
			<div class="col-md-4">
				<div class="form-group">
					<label>Province</label>
						<select name='province' style='text-transform:uppercase' id='province' class='form-control' onchange="getIdA(this.value)" >
							<option value="<?php echo $bene['province']; ?>"> <?php echo $bene['province']; ?></option>
								<?php
									$query = "SELECT * FROM tbl_province where psgc like '11%'";
									$results=mysqli_query($conn, $query);
									//loop
									while ($row = $results->fetch_assoc()) {
								?>
							<option value="<?php echo $row["psgc"];?>"><?php echo $row["col_province"];?></option>
							<?php } ?>
							</select >
			</div>
			<script>
				function getIdA(val){
													
				//We create ajax function
													$.ajax({
													type: "POST",
													url: "city.php",
													data: "psgc="+val,
													success: function(data){
													$("#citymuni").html(data);
														}
													});
												}
											</script>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					  <label>City</label>
						<select name='citymuni' style='text-transform:uppercase' id='citymuni' class='form-control' onchange="getIdB(this.value)" >
							<option value="<?php echo $bene['city_municipality']; ?>"> <?php echo $bene['city_municipality']; ?></option>
								<?php
									$queryc = "SELECT * FROM tbl_citymuni  where psgc like '11%'";
									$resultsc=mysqli_query($conn, $queryc);
									//loop
									while ($rowc = $resultsc->fetch_assoc()) {
								?>
							<option value="<?php echo $rowc["psgc"];?>"><?php echo $rowc["col_citymuni"];?></option>
							<?php } ?>
							</select >
			</div>
			<script>
				function getIdB(val){
													
				//We create ajax function
													$.ajax({
													type: "POST",
													url: "brgy.php",
													data: "psgc="+val,
													success: function(data){
													$("#barangay").html(data);
														}
													});
												}
											</script>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					 <label>Barangay</label>
						<select name='barangay' style='text-transform:uppercase' id='barangay' class='form-control'  >
							<option value="<?php echo $bene['barangay']; ?>"> <?php echo $bene['barangay']; ?></option>
								<?php
									$queryb = "SELECT * FROM tbl_brgy  where psgc like '11%'";
									$resultsb=mysqli_query($conn, $queryb);
									//loop
									while ($rowb = $resultsb->fetch_assoc()) {
								?>
							<option value="<?php echo $rowb["col_brgy"];?>"><?php echo $rowb["col_brgy"];?></option>
							<?php } ?>
						</select >
				</div>
			</div>
		</div>
		<div class="row" id="new_details6" hidden>
			<div class="col-md-3">
				<div class="form-group">
					<label>Type Of Assistance</label>
						<select name="assistanceType" id="assistanceType" class="form-control" >
							<option value="<?php echo $bene['type_of_assistance']; ?>"> <?php echo $bene['type_of_assistance']; ?></option>
							<option value="EDUCATIONAL ASSISTANCE">EDUCATIONAL ASSISTANCE</option> 
							<option value="FOOD ASSISTANCE">FOOD ASSISTANCE</option> 
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Amount</label>
					<input type="number" class="form-control" name="amount" id="amount" value="<?php echo $bene['amount']; ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Charging</label>
					<input readonly type="text" class="form-control" name="charging" id="charging" value="<?php echo $bene['charging']; ?>" />
				</div>
			</div><br><center>
			<button type='button' class='btn btn-primary correction-details-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene['id']; ?>'>Save Details</button>
			<center>
		</div>
		</div>
		<div class="row">
		<?php if( $bene['status']==NULL){ ?>
		
		<hr>
			<div class="col-md-12"><center>
					<!--<button type='button' class='btn btn-primary validate-status-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene['id']; ?>'>Validate</button>-->
					<button type='button' class='btn btn-danger disqualify-status-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene['id']; ?>'>Disqualify</button>
					<button type='button' class='btn btn-warning correction-status-btn equal-width-btn button-spacing' data-bene-id='<?php echo $bene['id']; ?>'>Correction</button>
						<button type='button' class='btn btn-primary printNewGis equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>GIS</button>
						<button type='button' class='btn btn-primary printInfo equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Information Sheet</button>
						<button type='button' class='btn btn-primary printNewCoe equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>COE</button>
					<button type='button' class='btn btn-primary printJustification equal-width-btn button-spacing' data-bene-id='<?php echo $bene['control_number']; ?>'>Justification</button>
		
				</div>
			</div>
		<?php }?>	
		</div>


    </form>
	<!-- Add the modal for inputting the reason for disqualification -->
		<div id="reasonModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Reason for Disqualification</h4>
					</div>
					<div class="modal-body">
						<label for="reason">Reason:</label>
						<textarea id="reason" class="form-control"></textarea>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" id="submitReasonBtn">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	<?php
}
}
}
?>
<script>
$('.correction-status-btn').click(function() {
  var beneId = $(this).data('bene-id');
    if (confirm("Are you sure you want to add details?")) {
		 $('#new_details').toggle();
		 $('#new_details2').toggle();
		 $('#new_details3').toggle();
		 $('#new_details4').toggle();
		 $('#new_details5').toggle();
		 $('#new_details6').toggle();
	}
 
  // You can also add some animation or effects here
});
$('.correction-details-btn').click(function() {
  var beneId 		= $(this).data('bene-id');
  var newFname 		= $('#new_fname').val();
  var newMname 		= $('#new_mname').val();
  var newLname 		= $('#new_lname').val();
  var new_ename 	= document.getElementById('new_ename').value;
  var birthday 		= document.getElementById('birthday').value;
  var birthmonth 	= document.getElementById('birthmonth').value;
  var birthyear 	= document.getElementById('birthyear').value;
  var sex 			= document.getElementById('sex').value;
  var civil_status 	= $('#civil_status').val();
  var occupation 	= $('#occupation').val();
  var salary = $('#salary').val().trim();
	if (salary === "") {
		$('#salary').val(0);
	}

  var category 		= $('#category').val();
  var sub_category 	= $('#sub_category').val();
  var cnum 			= $('#cnum').val();
  var purok 		= $('#purok').val();
  var barangay 		= $('#barangay option:selected').text();
  var citymuni 		= $('#citymuni option:selected').text();
  var province 		= $('#province option:selected').text();
  var assistanceType = $('#assistanceType').val();
  var amount 		= $('#amount').val();
  var charging 		= $('#charging').val();
  var control_no 	= $('#control_no').val();
  var payroll_no 	= $('#payroll_no').val();

  // Display the progress bar
  $('#progressContainer').show();
  const progressBar = $('#progressBar');
  progressBar.css('width', '0%');
    progressBar.text(`Loading Please wait...`);

  // Simulate progress (for demo purposes)
  let progress = 0;
  const interval = setInterval(() => {
    progress += 10;
    progressBar.css('width', `${progress}%`);
    progressBar.text(`Loading Please wait...`);
    if (progress >= 100) {
      clearInterval(interval);
    }
  }, 200); // Adjust time as needed

  $.ajax({
    type: 'POST',
    url: 'save_correction_details.php',
    data: {
      bene_id: beneId,
      new_fname: newFname,
      new_mname: newMname,
      new_lname: newLname,
      new_ename: new_ename,
      birthday: birthday,
      birthmonth: birthmonth,
      birthyear: birthyear,
      sex: sex,
      civil_status: civil_status,
      occupation: occupation,
      salary: salary,
      category: category,
      sub_category: sub_category,
      cnum: cnum,
      purok: purok,
      barangay: barangay,
      citymuni: citymuni,
      province: province,
      assistanceType: assistanceType,
      amount: amount,
      charging: charging,
      control_no: control_no,
	  payroll_no: payroll_no
    },
    success: function(response) {
		alert(response);
      // Stop progress bar and set to 100%
      clearInterval(interval);
      progressBar.css('width', '100%');
    progressBar.text(`Loading Please wait...`);

      // Show the modal
      $('#payrollModal').modal('hide');
      window.close();
      window.open('runrdv.php', '_self');
    },
    error: function() {
      // Stop progress bar and show error
      clearInterval(interval);
      progressBar.css('width', '100%').addClass('bg-danger');
      progressBar.text('Error');

      alert("Error saving correction details. Please try again.");
    },
    complete: function() {
      // Hide the progress bar after 2 seconds
     
    }
  });
});


$(document).ready(function() {
    // Handle update status button click
    $('.printGis').click(function() {
		
    var beneId = $(this).data('bene-id');
		   window.open('print/gisIndividual.php?beneId='+beneId, '_blank');
	});
	 // Handle update status button click
	 $('.printNewGis').click(function() {
		
		var beneId = $(this).data('bene-id');
			   window.open('print/newGis.php?beneId='+beneId, '_blank');
		});
});
$(document).ready(function() {
    // Handle update status button click
    $('.printCoe').click(function() {
		
    var beneId = $(this).data('bene-id');
		   window.open('print/coeIndividual.php?beneId='+beneId, '_blank');
	});
	// Handle update status button click
    $('.printNewCoe').click(function() {
		
		var beneId = $(this).data('bene-id');
			   window.open('print/newcoeIndividual.php?beneId='+beneId, '_blank');
		});

	// Handle update status button click
    $('.printInfo').click(function() {
		
		var beneId = $(this).data('bene-id');
			   window.open('print/informationsheet.php?beneId='+beneId, '_blank');
		});
});
$(document).ready(function() {
    // Handle update status button click
    $('.printJustification').click(function() {
		
    var beneId = $(this).data('bene-id');
		   window.open('print/justification.php?beneId='+beneId, '_blank');
	});
});
$(document).ready(function() {
    // Handle update status button click
    $('.validate-status-btn').click(function() {
    var beneId = $(this).data('bene-id');
    var selectedStatus = "Validated";
	
	if (sex == "") {
			alert("Please select Gender");
			return false; // Prevent the AJAX request from being sent
		}
    // Confirmation dialog
    if (confirm("Are you sure you want to validate this beneficiary?")) {
		
        // AJAX request to update the details in the database
        $.ajax({
            url: 'validate_status.php',  // The PHP file that handles the update
            type: 'POST',
            data: {
                bene_id: beneId,
                status: selectedStatus
            },
            success: function(response) {
				//alert(response);
                $('#payrollModal').modal('hide'); // Show the modal
				//alert(response);
                if (response >= 1) {
                    // Show the success message in a modal
                    $('#successModal .modal-title').text('Success');
                    $('#successModal .modal-body').html('<center><span style="color: green"><h2>Payroll ID: ' + response + '</h2></span></center>');
                    $('#successModal').modal('show');
                } else if (response >= 4) {
					
                    // Show the success message in a modal
                    $('#successModal .modal-title').text('Warning');
                    $('#successModal .modal-body').html('<center><span style="color: red"><h2> No Available SDO!</h2></span></center>');
                    $('#successModal').modal('show');
                }

                // Reload the page after a short delay
                setTimeout(function() {
                    location.reload();
                }, 1500); // Reload the page after 2 seconds
            },
            error: function() {
                alert('Error updating status and details. Please try again.');
            }
        });
    } else {
        // If the user cancels the confirmation dialog, do nothing
        return false;
    }
});
});
</script>
<script>
$(document).ready(function() {	
    $('.disqualify-status-btn').click(function() {
        var beneId = $(this).data('bene-id');

        if (sex == "") {
            alert("Please select Gender");
            return false; // Prevent the AJAX request from being sent
        }
        
        // Confirmation dialog
        if (confirm("Are you sure you want to disqualify this beneficiary?")) {
            // Show a modal to input the reason for disqualification
           /// $('#reasonModal').modal('show');

                // AJAX request to update the details in the database
                $.ajax({
                    url: 'update_status.php',  // The PHP file that handles the update
                    type: 'POST',
                    data: {
                        bene_id: beneId
                    },
                    success: function(response) {
						//alert(response);
                        $('#payrollModal').modal('hide'); // Hide the original modal
                        $('#reasonModal').modal('hide'); // Hide the reason modal
                        if (response >= 1) {
                            // Show the success message in a modal
                            $('#successModal .modal-title').text('Success');
                            $('#successModal .modal-body').html('<center><span style="color: green"><h2>Payroll ID #: ' + response + '</h2></span></center>');
                            $('#successModal').modal('show');
                        } else {
                            // Show the success message in a modal
                            $('#successModal .modal-title').text('Warning');
                            $('#successModal .modal-body').html('<center><span style="color: red"><h2> The beneficiary has been Disqualified!</h2></span></center>');
                            $('#successModal').modal('show');
                        }

                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Reload the page after 2 seconds
                    },
                    error: function() {
                        alert('Error updating status and details. Please try again.');
                    }
                });
        } else {
            // If the user cancels the confirmation dialog, do nothing
            return false;
        }
    });
});
</script>
<script>
$(document).ready(function() {	
    $('.replacement-status-btn').click(function() {
        var beneId = $(this).data('bene-id');
        var fname = $('#fname').val();
        var mname = $('#mname').val();
        var lname = $('#lname').val();
        var dob = $('#dob').val();
        var sex = $('#sex').val();
        var selectedStatus = "Replacement";

        if (sex == "") {
            alert("Please select Gender");
            return false; // Prevent the AJAX request from being sent
        }
        
        // Confirmation dialog
        if (confirm("Are you sure you want to Replace this beneficiary?")) {
            // Show a modal to input the reason for replacement
            $('#reasonModal').modal('show');

            // Add an event listener to the "Submit" button in the modal
            $('#submitReasonBtn').click(function() {
                var reason = $('#reason').val();

                // AJAX request to update the details in the database
                $.ajax({
                    url: 'update_status.php',  // The PHP file that handles the update
                    type: 'POST',
                    data: {
                        bene_id: beneId,
                        fname: fname,
                        mname: mname,
                        lname: lname,
                        dob: dob,
                        sex: sex,
                        status: selectedStatus,
                        reason: reason
                    },
                    success: function(response) {
                        $('#payrollModal').modal('hide'); // Hide the original modal
                        $('#reasonModal').modal('hide'); // Hide the reason modal
                        if (response >= 1) {
                            // Show the success message in a modal
                            $('#successModal .modal-title').text('Success');
                            $('#successModal .modal-body').html('<center><span style="color: green"><h2>Payroll ID #: ' + response + '</h2></span></center>');
                            $('#successModal').modal('show');
                        } else {
                            // Show the success message in a modal
                            $('#successModal .modal-title').text('Warning');
                            $('#successModal .modal-body').html('<center><span style="color: red"><h2> The beneficiary’s status has been updated to Replacement!</h2></span></center>');
                            $('#successModal').modal('show');
                        }

                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Reload the page after 2 seconds
                    },
                    error: function() {
                        alert('Error updating status and details. Please try again.');
                    }
                });
            });
        } else {
            // If the user cancels the confirmation dialog, do nothing
            return false;
        }
    });
});
</script>
