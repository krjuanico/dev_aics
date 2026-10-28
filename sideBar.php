<?php


?>

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
				
				<?php } if($user_type=='Team Leader'){ ?>
                <li>
					<a href="export_excel.php?userid=<?php echo $emm;?>"><i class="pe-7s-file"></i><span><b>Import CSV File</span></b> </a>
				</li>
				<?php } if($user_type=='Team Leader'){ ?>
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
				 if($user_type=='Team Leader'||$user_type=='Payroll'){ ?>
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