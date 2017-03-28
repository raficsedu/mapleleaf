<style>
    .cp-studying th{
        text-align: center !important;
    }
    .h_plus{
        width: 10%;
        float: left;
        padding-top: 5%;
    }
    .h_input{
        width: 90% !important;
        float: left;
    }
    .cl{
        font-size: 15px;
        width: 100%;
        text-align: center !important;
        margin-bottom: 2% !important;
    }
    #waiver{
        display: none;
    }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('addmission_form');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php //echo form_open(base_url() . 'index.php?admin/student/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>index.php?admin/student/create" autocomplete="off" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Admitted For The Session').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-3">
                        <select name="s_session" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" id="month" onchange="" size="1">
                            <option value="01">January</option>
                            <option value="07">July</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="year" class="form-control" data-validate="required" id="year" onchange="" size="1">

                        </select>
                        <div class="s-year" style="display: none;">
                            <?php
                            $year = date('Y') - 5;
                            for($i= $year;$i<=$year+6;$i++){
                                echo '<option class="jan" value="'.$i.'">'.$i.'</option>';
                                echo '<option class="july" value="'.$i.'-'.($i+1).'">'.$i.'-'.($i+1).'</option>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <select name="class_id" class="form-control" data-validate="required" id="class_id"
                                data-message-required="<?php echo get_phrase('value_required');?>"
                                onchange="return get_class_sections(this.value)">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $row):
                                $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description;
                                $fee = $row['monthly_fee'].','.$row['admission_fee'].','.$row['evaluation_fee'].','.$vat;
                                ?>
                                <option class="<?php echo $fee?>" value="<?php echo $row['class_id'];?>">
                                    <?php echo $row['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section').'<span style="color:red">*</span>';?></label>
                    <div class="col-sm-5">
                        <select name="section_id" class="form-control" id="section_selector_holder" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select_class_first');?></option>

                        </select>
                    </div>
                    <div class="col-sm-4" style="color: blue;font-size: 15px;">
                        Capacity : <span id="info_capacity">0</span> <br> Current : <span id="info_current">0</span>
                    </div>
                </div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name').'<span style="color:red">*</span>';?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('gender').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <select name="gender" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <option value="male"><?php echo get_phrase('male');?></option>
                            <option value="female"><?php echo get_phrase('female');?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Present Address');?></label>

                    <div class="col-sm-5">
<!--                        <input type="text" class="form-control" name="present_address" value="" >-->
                        <textarea rows="2" cols="70" name="present_address"></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="phone" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('SMS Number').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="sms_number" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" >
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Permanent Address');?></label>

                    <div class="col-sm-5">
<!--                        <input type="text" class="form-control" name="parmanent_address" value="" >-->
                        <textarea rows="2" cols="70" name="parmanent_address"></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('birthday').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control datepicker" name="birthday" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" data-start-view="2">
                    </div>
                </div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('parent/gurdian ID');?></label>
                        
						<div class="col-sm-5">
<!--							<select name="parent_id" class="form-control">-->
<!--                              <option value="">--><?php //echo get_phrase('select');?><!--</option>-->
<!--                              --><?php //
//								$parents = $this->db->get('parent')->result_array();
//								foreach($parents as $row):
//									?>
<!--                            		<option value="--><?php //echo $row['parent_id'];?><!--">-->
<!--										--><?php //echo $row['father_name'].'(ID = '.$row['parent_id'].')';?>
<!--                                    </option>-->
<!--                                --><?php
//								endforeach;
//							  ?>
<!--                          </select>-->
                            <input type="text" class="form-control" name="parent_id" placeholder="Enter Parent ID" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div> 
					</div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Lab');?></label>

                    <div class="col-sm-5">
                        <select name="lab" class="form-control" id="lab" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value="1">None</option>
                            <option value="2">C Lab</option>
                            <option value="3">P Lab</option>
                            <option value="4">Both Lab</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Yearly Fee');?></label>

                    <div class="col-sm-5">
                        <input type="checkbox" name="yearly_fee[]" value="admission" checked> Admission Fee<br>
                        <input type="checkbox" name="yearly_fee[]" value="evaluation" checked> Evaluation Fee
                    </div>
                </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Parent/Student Status');?></label>

                        <div class="col-sm-5">
                            <select name="parent_status" id="parent_status" data-validate="required" class="form-control">
                                <option value="0">Non Teacher</option>
                                <option value="1">Teacher</option>
                                <option value="2">Scholarship</option>
                                <option value="3">Teacher + Scholarship</option>
                                <option value="4">Special</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="mf_waiver">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Monthly Fee Waiver %');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mf_waiver" value="">
                        </div>
                    </div>

                    <div class="form-group" id="ad_waiver">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admission Fee Waiver %');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="ad_waiver" value="">
                        </div>
                    </div>

                    <div class="form-group" id="ev_waiver">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Evaluation Waiver %');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="ev_waiver" value="">
                        </div>
                    </div>

                    <div class="form-group" id="c_lab_waiver">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('C Lab Waiver %');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="c_lab_waiver" value="">
                        </div>
                    </div>

                    <div class="form-group" id="p_lab_waiver">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('P Lab Waiver %');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="p_lab_waiver" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Status');?></label>

                        <div class="col-sm-5">
                            <select name="student_status" id="student_status" data-validate="required" class="form-control">
                                <option value="1">Current</option>
                                <option value="2">Old</option>
                                <option value="0">Alumni</option>
                            </select>
                        </div>
                    </div>
                    
<!--					<div class="form-group">-->
<!--						<label for="field-1" class="col-sm-3 control-label">--><?php //echo get_phrase('email');?><!--</label>-->
<!--						<div class="col-sm-5">-->
<!--							<input type="text" class="form-control" name="email" value="">-->
<!--						</div>-->
<!--					</div>-->
<!--					-->
<!--					<div class="form-group">-->
<!--						<label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('password');?><!--</label>-->
<!--                        -->
<!--						<div class="col-sm-5">-->
<!--							<input type="password" class="form-control" name="password" value="" >-->
<!--						</div> -->
<!--					</div>-->
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
                        
						<div class="col-sm-5">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new">Select image</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="userfile" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div>
							</div>
						</div>
					</div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-8 control-label cl"><?php echo get_phrase('Information of The Student is Currently Studying Or Has Previously Studied(Not Applicable for PLAY Group)');?></label>
                    <div class="col-sm-12">
                        <table class="cp-studying" style="width:100%" border="1">
                            <tr>
                                <th>NAME OF INSTITUTION</th>
                                <th>CLASS</th>
                                <th>YEAR</th>
                                <th>POSITION</th>
                                <th>PASSED/PROMOTION ON TRIAL</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="institution_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="class_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="year_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="position_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="passed_1" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="institution_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="class_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="year_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="position_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="passed_2" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="institution_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="class_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="year_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="position_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="passed_3" value="" autofocus></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Reason For Leaving Institution');?></label>

                    <div class="col-sm-5">
                        <textarea rows="5" cols="100" name="reason_for_leaving"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-5 control-label cl"><?php echo get_phrase('Names of Brothers/Sisters Currently Studying in This Institution');?></label>
                    <div class="col-sm-12">
                        <table class="cp-studying" style="width:100%" border="1">
                            <tr>
                                <th>NAME OF BROTHERS/SISTERS</th>
                                <th>CLASS</th>
                                <th>SECTION</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="c_brother_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_class_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_section_1" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="c_brother_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_class_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_section_2" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="c_brother_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_class_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="cb_section_3" value="" autofocus></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-5 control-label cl"><?php echo get_phrase('Names of Brothers/Sisters Previously Studying in This Institution');?></label>
                    <div class="col-sm-12">
                        <table class="cp-studying" style="width:100%" border="1">
                            <tr>
                                <th>NAME OF BROTHERS/SISTERS</th>
                                <th>CLASS</th>
                                <th>SECTION</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="p_brothers_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_class_1" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_section_1" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="p_brothers_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_class_2" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_section_2" value="" autofocus></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" name="p_brothers_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_class_3" value="" autofocus></td>
                                <td><input type="text" class="form-control" name="pb_section_3" value="" autofocus></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-4 control-label cl"><?php echo get_phrase('This Section is to be Filled up by School Authority');?></label>
                    <div class="col-sm-12">
                        <table class="cp-studying" style="width:100%">
                            <tr>
<!--                                <th>CLASS</th>-->
                                <th>MONTHLY TUTION FEES</th>
                                <th>ADMISSION FEES</th>
                                <th>EVALUATION FEES</th>
                                <th>VAT</th>
                                <th>TOTAL</th>

                            </tr>
                            <tr>
<!--                                <td>-->
<!--                                    <div class="form-group">-->
<!--                                        <div class="col-sm-10">-->
<!--                                            <select name="fee_class_id" class="form-control" data-validate="required" id="fee_class_id"-->
<!--                                                    data-message-required="--><?php //echo get_phrase('value_required');?><!--">-->
<!--                                                <option value="">--><?php //echo get_phrase('select class');?><!--</option>-->
<!--                                                --><?php
//                                                $classes = $this->db->get('class')->result_array();
//                                                foreach($classes as $class):
//                                                    $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description;
//                                                    $fee = $class['monthly_fee'].','.$class['admission_fee'].','.$class['evaluation_fee'].','.$vat;
//                                                    ?>
<!--                                                    <option class="--><?php //echo $fee;?><!--" value="--><?php //echo $class['class_id'];?><!--">-->
<!--                                                        --><?php //echo $class['name'];?>
<!--                                                    </option>-->
<!--                                                --><?php
//                                                endforeach;
//                                                ?>
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
                                <td><input type="text" class="form-control" name="monthly_fees" id="monthly_fees" value="" autofocus readonly></td>
                                <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="admission_fees" id="admission_fees" value="" autofocus readonly></td>
                                <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="evaluation_fees" id="evaluation_fees" value="" autofocus readonly></td>
                                <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="vat" id="vat" value="" autofocus readonly></td>
                                <td><span class="h_plus">=</span><input type="text" class="form-control h_input" name="total" id="total" value="" autofocus readonly></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Building Information').'<span style="color:red">*</span>';?></label>
                    <div class="col-sm-5">
                        <select name="building_info" class="form-control" data-validate="required" id="building_info"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select building');?></option>
                            <?php
                            $buildings = $this->db->get('building')->result_array();
                            foreach($buildings as $building):
                                ?>
                                <option value="<?php echo $building['id'];?>">
                                    <?php echo $building['building_name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Branch Information').'<span style="color:red">*</span>';?></label>
                    <div class="col-sm-5">
                        <select name="branch_info" class="form-control" data-validate="required" id="branch_info"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select branch');?></option>
                            <?php
                            $branchs = $this->db->get('branch')->result_array();
                            foreach($branchs as $branch):
                                ?>
                                <option value="<?php echo $branch['branch_id'];?>">
                                    <?php echo $branch['branch_name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Payment Month Start From').'<span style="color:red">*</span>';?></label>
                    <div class="col-sm-5">
                        <select name="payment_month_start_from" class="form-control" data-validate="required" id="payment_month_start_from"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Admission Date').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control datepicker" name="admission_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo date('d/m/Y');?>" data-start-view="2">
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info"><?php echo get_phrase('add_student');?></button>
                    </div>
				</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	function get_class_sections(class_id) {
        var s_session = $('#month').val();
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id + '/' + s_session,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

        var c = $('#class_id option:selected').attr("class");
        var fees = c.split(",");
        var monthly_fee = parseFloat(fees[0]);
        var admission_fee = parseFloat(fees[1]);
        var evaluation_fee = parseFloat(fees[2]);
        var vat = parseFloat(fees[3]);
        var total = monthly_fee + admission_fee +  evaluation_fee;
        var final_total = total + (total * (vat/100));

        //Putting to necessary field
        $('#monthly_fees').val(monthly_fee);
        $('#admission_fees').val(admission_fee);
        $('#evaluation_fees').val(evaluation_fee);
        $('#vat').val(vat);
        $('#total').val(final_total);

    }
    $(function(){
        var m = $('#month').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }

        var s = $('#parent_status').val();
        if(s==1 || s==2 || s==3 || s==4){
            $('#mf_waiver').show();
            $('#ad_waiver').show();
            $('#ev_waiver').show();
        }else if(s==0){
            $('#mf_waiver').hide();
            $('#ad_waiver').hide();
            $('#ev_waiver').hide();
        }
    });

    $('#month').change(function(){
        var m = $('#month').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }
    });

    $('#parent_status').change(function(){
        var s = $(this).val();
        if(s==1 || s==2 || s==3 || s==4){
            $('#mf_waiver').show();
            $('#ad_waiver').show();
            $('#ev_waiver').show();
            $('#c_lab_waiver').show();
            $('#p_lab_waiver').show();
        }else if(s==0){
            $('#mf_waiver').find('input').val(0);
            $('#ad_waiver').find('input').val(0);
            $('#ev_waiver').find('input').val(0);
            $('#c_lab_waiver').find('input').val(0);
            $('#p_lab_waiver').find('input').val(0);

            $('#mf_waiver').hide();
            $('#ad_waiver').hide();
            $('#ev_waiver').hide();
            $('#c_lab_waiver').hide();
            $('#p_lab_waiver').hide();
        }
    });

//    $(document).delegate('#class_id','.change',function(){
//        var c = $('#class_id option:selected').attr("class");
//        var fees = c.split(",");
//        var monthly_fee = parseFloat(fees[0]);
//        var admission_fee = parseFloat(fees[1]);
//        var evaluation_fee = parseFloat(fees[2]);
//        var vat = parseFloat(fees[3]);
//        var total = monthly_fee + admission_fee +  evaluation_fee;
//        var final_total = total + (total * (vat/100));
//
//        //Putting to necessary field
//        $('#monthly_fees').val(monthly_fee);
//        $('#admission_fees').val(admission_fee);
//        $('#evaluation_fees').val(evaluation_fee);
//        $('#vat').val(vat);
//        $('#total').val(final_total);
//    });

    $(document.body).on('change','#section_selector_holder',function(){
        var year = $('#year').val();
        var section = $(this).val();
        if(parseInt(section)){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url();?>index.php?admin/get_capacity_current/' + section + '/' + year,
                dataType: "json",
                success: function(res)
                {
                    jQuery('#info_capacity').html(res[0]);
                    jQuery('#info_current').html(res[1]);
                }
            });
        }
    });

</script>