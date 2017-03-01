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
    .modal-dialog {
        width: 960px !important;
        padding-top: 30px;
        padding-bottom: 30px;
    }
</style>
<?php
$edit_data		=	$this->db->get_where('student' , array('student_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/student/'.$row['class_id'].'/do_update/'.$row['student_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                
                	
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
                        
						<div class="col-sm-5">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="<?php echo $this->crud_model->get_image_url('student' , $row['student_id']);?>" alt="...">
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
                    <?php
                    if($row['active']==0){
                        $disabled = '';
                    }else{
                        $disabled = '';
                    }
                    ?>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student ID');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="roll" value="<?php echo $row['roll'];?>" <?php echo $disabled;?>>
                        </div>
                    </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Admitted For The Session');?></label>

                    <div class="col-sm-3">
                        <select class="form-control" name="s_session" id="month" onchange="" size="1">
                            <option value="01" <?php if($row['s_session']=='01')echo 'selected';?>>January</option>
                            <option value="07" <?php if($row['s_session']=='07')echo 'selected';?>>July</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" name="year" id="year" onchange="" size="1">
                            <?php
                            if($row['s_session']=='01'){
                                $year = date('Y') - 5;
                                for($i= $year;$i<=$year+6;$i++){
                                    if($row['year']==$i){
                                        $s = 'selected';
                                    }else{
                                        $s = '';
                                    }
                                    echo '<option class="jan" value="'.$i.'" '.$s.'>'.$i.'</option>';
                                }
                            }else if($row['s_session']=='07'){
                                $year = date('Y') - 5;
                                for($i= $year;$i<=$year+6;$i++){
                                    $ttt = explode('-',$row['year']);
                                    $holding_year = $ttt[0];
                                    if($holding_year==$i){
                                        $s = 'selected';
                                    }else{
                                        $s = '';
                                    }
                                    echo '<option class="july" value="'.$i.'-'.($i+1).'" '.$s.'>'.$i.'-'.($i+1).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
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

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>

                    <div class="col-sm-5">
                        <select name="class_id" class="form-control" data-validate="required" id="class_id"
                                data-message-required="<?php echo get_phrase('value_required');?>"
                                onchange="return get_class_sections(this.value)">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $row2):
                                $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description;
                                $fee = $row2['monthly_fee'].','.$row2['admission_fee'].','.$row2['evaluation_fee'].','.$vat;
                                ?>
                                <option class="<?php echo $fee;?>" value="<?php echo $row2['class_id'];?>"
                                    <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
                    <div class="col-sm-5">
                        <select name="section_id" class="form-control" id="section_selector_holder">
                            <option value=""><?php echo get_phrase('select_class_first');?></option>
                            <?php
                            $sections = $this->db->get_where('section' , array(
                                'class_id' => $row['class_id']
                            ))->result_array();
                            foreach ($sections as $row2) {
                                if($row['section_id']==$row2['section_id']){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }
                                echo '<option value="' . $row2['section_id'] . '" '.$selected.'>' . $row2['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['name'];?>">
						</div>
					</div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('gender');?></label>

                        <div class="col-sm-5">
                            <select name="gender" class="form-control">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <option value="male" <?php if($row['gender'] == 'male')echo 'selected';?>><?php echo get_phrase('male');?></option>
                                <option value="female"<?php if($row['gender'] == 'female')echo 'selected';?>><?php echo get_phrase('female');?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('present address');?></label>

                        <div class="col-sm-5">
<!--                            <input type="text" class="form-control" name="present_address" value="--><?php //echo $row['present_address'];?><!--" >-->
                            <textarea rows="2" cols="70" name="present_address"><?php echo $row['present_address'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>" >
                        </div>
                    </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('SMS Number');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="sms_number" value="<?php echo $row['sms_number'];?>" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Permanent Address');?></label>

                    <div class="col-sm-5">
<!--                        <input type="text" class="form-control" name="parmanent_address" value="--><?php //echo $row['parmanent_address'];?><!--" >-->
                        <textarea rows="2" cols="70" name="parmanent_address"><?php echo $row['parmanent_address'];?></textarea>
                    </div>
                </div>


                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('birthday');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="birthday" value="<?php echo $row['birthday'];?>" data-start-view="2">
                        </div>
                        <div class="col-sm-3">
                            <?php
                            //date in mm/dd/yyyy format; or it can be in other formats as well
                            $birthDate = $row['birthday'];
                            //explode the date to get month, day and year
                            $birthDate = explode("/", $birthDate);
                            //get age from date or birthdate
                            $age = (date("dm", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("dm")
                                ? ((date("Y") - $birthDate[2]) - 1)
                                : (date("Y") - $birthDate[2]));
                            echo '<p style="color: blue;    font-size: 20px;">Age : ' . $age . '</p>';
                            ?>
                        </div>
                    </div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('parent/gurdian ID');?></label>
                        
						<div class="col-sm-5">
                            <input type="text" class="form-control" name="parent_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['parent_id'];?>" autofocus>
						</div> 
					</div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Lab');?></label>

                    <div class="col-sm-5">
                        <select name="lab" class="form-control" id="lab" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value="1" <?php if($row['c_lab']==0 && $row['p_lab']==0)echo 'selected';?>>None</option>
                            <option value="2" <?php if($row['c_lab']>0 && $row['p_lab']==0)echo 'selected';?>>C Lab</option>
                            <option value="3" <?php if($row['c_lab']==0 && $row['p_lab']>0)echo 'selected';?>>P Lab</option>
                            <option value="4" <?php if($row['c_lab']>0 && $row['p_lab']>0)echo 'selected';?>>Both Lab</option>
                        </select>
                    </div>
                </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Parent/Student Status');?></label>

                        <div class="col-sm-5">
                            <select name="parent_status" id="parent_status" data-validate="required" class="form-control">
                                <option value="0" <?php if($row['parent_status']==0)echo 'selected';?>>Non Teacher</option>
                                <option value="1" <?php if($row['parent_status']==1)echo 'selected';?>>Teacher</option>
                                <option value="2" <?php if($row['parent_status']==2)echo 'selected';?>>Scholarship</option>
                                <option value="3" <?php if($row['parent_status']==3)echo 'selected';?>>Teacher + Scholarship</option>
                                <option value="4" <?php if($row['parent_status']==4)echo 'selected';?>>Special</option>
                            </select>
                        </div>
                    </div>

                <div class="form-group" id="mf_waiver">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Monthly Fee Waiver %');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="mf_waiver" value="<?php echo $row['mf_waiver'];?>">
                    </div>
                </div>

                <div class="form-group" id="ad_waiver">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admission Fee Waiver %');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="ad_waiver" value="<?php echo $row['ad_waiver'];?>">
                    </div>
                </div>

                <div class="form-group" id="ev_waiver">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Evaluation Waiver %');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="ev_waiver" value="<?php echo $row['ev_waiver'];?>">
                    </div>
                </div>

                <div class="form-group" id="c_lab_waiver">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('C Lab Waiver %');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="c_lab_waiver" value="<?php echo $row['c_lab_waiver'];?>">
                    </div>
                </div>

                <div class="form-group" id="p_lab_waiver">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('P Lab Waiver %');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="p_lab_waiver" value="<?php echo $row['p_lab_waiver'];?>">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Status');?></label>

                        <div class="col-sm-5">
                            <select name="student_status" id="student_status" data-validate="required" class="form-control">
                                <option value="1" <?php if($row['active']==1)echo 'selected';?>>Current</option>
                                <option value="2" <?php if($row['active']==2)echo 'selected';?>>Old</option>
                                <option value="0" <?php if($row['active']==0)echo 'selected';?>>Alumni</option>
                            </select>
                        </div>
                    </div>
					

<!--					<div class="form-group">-->
<!--						<label for="field-1" class="col-sm-3 control-label">--><?php //echo get_phrase('email');?><!--</label>-->
<!--						<div class="col-sm-5">-->
<!--							<input type="text" class="form-control" name="email" value="--><?php //echo $row['email'];?><!--">-->
<!--						</div>-->
<!--					</div>-->
<!---->
<!--                    <div class="form-group">-->
<!--                        <label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('password');?><!--</label>-->
<!--                        -->
<!--                        <div class="col-sm-5">-->
<!--                            <input type="password" class="form-control" name="password" value="--><?php //echo $row['password'];?><!--" >-->
<!--                        </div> -->
<!--                    </div>-->

                    <div class="form-group">
                        <label for="field-2" class="col-sm-8 control-label cl"><?php echo get_phrase('Information of The Student is Currently Studying Or Has Previously Studied(Not Applicable for PLAY Group)');?></label>
                        <div class="col-sm-12">
                            <table class="cp-studying" style="width:100%" border="1">
                                <thead>
                                    <tr>
                                        <th>NAME OF INSTITUTION</th>
                                        <th>CLASS</th>
                                        <th>YEAR</th>
                                        <th>POSITION</th>
                                        <th>PASSED/PROMOTION ON TRIAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="institution_1" value="<?php echo $row['institution_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="class_1" value="<?php echo $row['class_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="year_1" value="<?php echo $row['year_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="position_1" value="<?php echo $row['position_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="passed_1" value="<?php echo $row['passed_1'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="institution_2" value="<?php echo $row['institution_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="class_2" value="<?php echo $row['class_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="year_2" value="<?php echo $row['year_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="position_2" value="<?php echo $row['position_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="passed_2" value="<?php echo $row['passed_2'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="institution_3" value="<?php echo $row['institution_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="class_3" value="<?php echo $row['class_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="year_3" value="<?php echo $row['year_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="position_3" value="<?php echo $row['position_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="passed_3" value="<?php echo $row['passed_3'];?>" autofocus></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Reason For Leaving Institution');?></label>

                        <div class="col-sm-5">
                            <textarea rows="5" cols="50" name="reason_for_leaving"><?php echo $row['reason_for_leaving'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-5 control-label cl"><?php echo get_phrase('Names of Brothers/Sisters Currently Studying in This Institution');?></label>
                        <div class="col-sm-12">
                            <table class="cp-studying" style="width:100%" border="1">
                                <thead>
                                    <tr>
                                        <th>NAME OF BROTHERS/SISTERS</th>
                                        <th>CLASS</th>
                                        <th>SECTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="c_brother_1" value="<?php echo $row['c_brother_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_class_1" value="<?php echo $row['cb_class_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_section_1" value="<?php echo $row['cb_section_1'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="c_brother_2" value="<?php echo $row['c_brother_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_class_2" value="<?php echo $row['cb_class_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_section_2" value="<?php echo $row['cb_section_2'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="c_brother_3" value="<?php echo $row['c_brother_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_class_3" value="<?php echo $row['cb_class_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="cb_section_3" value="<?php echo $row['cb_section_3'];?>" autofocus></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="field-2" class="col-sm-5 control-label cl"><?php echo get_phrase('Names of Brothers/Sisters Previously Studying in This Institution');?></label>
                        <div class="col-sm-12">
                            <table class="cp-studying" style="width:100%" border="1">
                                <thead>
                                    <tr>
                                        <th>NAME OF BROTHERS/SISTERS</th>
                                        <th>CLASS</th>
                                        <th>SECTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="p_brothers_1" value="<?php echo $row['p_brothers_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_class_1" value="<?php echo $row['pb_class_1'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_section_1" value="<?php echo $row['pb_section_1'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="p_brothers_2" value="<?php echo $row['p_brothers_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_class_2" value="<?php echo $row['pb_class_2'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_section_2" value="<?php echo $row['pb_section_2'];?>" autofocus></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="p_brothers_3" value="<?php echo $row['p_brothers_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_class_3" value="<?php echo $row['pb_class_3'];?>" autofocus></td>
                                        <td><input type="text" class="form-control" name="pb_section_3" value="<?php echo $row['pb_section_3'];?>" autofocus></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="field-2" class="col-sm-4 control-label cl"><?php echo get_phrase('This Section is to be Filled up by School Authority');?></label>
                        <div class="col-sm-12">
                            <table class="cp-studying" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>MONTHLY TUTION FEES</th>
                                        <th>ADMISSION FEES(FOR NEW STUDENT)</th>
                                        <th>EVALUATION FEES</th>
                                        <th>VAT</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="monthly_fees" id="monthly_fees" value="" autofocus readonly></td>
                                        <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="admission_fees" id="admission_fees" value="" autofocus readonly></td>
                                        <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="evaluation_fees" id="evaluation_fees" value="" autofocus readonly></td>
                                        <td><span class="h_plus">+</span><input type="text" class="form-control h_input" name="vat" id="vat" value="" autofocus readonly></td>
                                        <td><span class="h_plus">=</span><input type="text" class="form-control h_input" name="total" id="total" value="" autofocus readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Building Information');?></label>
                        <div class="col-sm-5">
                            <select name="building_info" class="form-control" id="building_info"
                                    data-message-required="<?php echo get_phrase('value_required');?>">
                                <?php
                                $buildings = $this->db->get('building')->result_array();
                                foreach($buildings as $building):
                                    if($building['id']==$row['building_info']){
                                        $selected = 'selected';
                                    }else{
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $building['id'];?>" <?php echo $selected;?>>
                                        <?php echo $building['building_name'];?>
                                    </option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Branch Information');?></label>
                    <div class="col-sm-5">
                        <select name="branch_info" class="form-control" id="branch_info"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <?php
                            $branchs = $this->db->get('branch')->result_array();
                            foreach($branchs as $branch):
                                if($branch['branch_id']==$row['branch_info']){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }
                                ?>
                                <option value="<?php echo $branch['branch_id'];?>" <?php echo $selected;?>>
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
                            <option value="1" <?php if($row['payment_month_start_from']==1){echo 'selected';}?>>January</option>
                            <option value="2" <?php if($row['payment_month_start_from']==2){echo 'selected';}?>>February</option>
                            <option value="3" <?php if($row['payment_month_start_from']==3){echo 'selected';}?>>March</option>
                            <option value="4" <?php if($row['payment_month_start_from']==4){echo 'selected';}?>>April</option>
                            <option value="5" <?php if($row['payment_month_start_from']==5){echo 'selected';}?>>May</option>
                            <option value="6" <?php if($row['payment_month_start_from']==6){echo 'selected';}?>>June</option>
                            <option value="7" <?php if($row['payment_month_start_from']==7){echo 'selected';}?>>July</option>
                            <option value="8" <?php if($row['payment_month_start_from']==8){echo 'selected';}?>>August</option>
                            <option value="9" <?php if($row['payment_month_start_from']==9){echo 'selected';}?>>September</option>
                            <option value="10" <?php if($row['payment_month_start_from']==10){echo 'selected';}?>>October</option>
                            <option value="11" <?php if($row['payment_month_start_from']==11){echo 'selected';}?>>November</option>
                            <option value="12" <?php if($row['payment_month_start_from']==12){echo 'selected';}?>>December</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special Monthly Fee');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_monthly_fee" value="<?php echo $row['special_monthly_fee']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special Admission Fee');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_admission_fee" value="<?php echo $row['special_admission_fee']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special Evaluation Fee');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_evaluation_fee" value="<?php echo $row['special_evaluation_fee']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special C Lab Fee');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_c_lab_fee" value="<?php echo $row['special_c_lab_fee']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special P Lab Fee');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_p_lab_fee" value="<?php echo $row['special_p_lab_fee']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Admission Date').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control datepicker" name="admission_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo getbddate($row['admission_date']);?>" data-start-view="2">
                    </div>
                </div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_student');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
function getbddate($date){
    $t = explode('-',$date);
    $date = $t[2].'/'.$t[1].'/'.$t[0];
    return $date;
}
?>

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

<!--    var class_id = $("#class_id").val();-->
<!--    -->
<!--    	$.ajax({-->
<!--            url: '--><?php //echo base_url();?><!--index.php?admin/get_class_section/' + class_id ,-->
<!--            success: function(response)-->
<!--            {-->
<!--                jQuery('#section_selector_holder').html(response);-->
<!--            }-->
<!--        });-->

    $(function(){
//        var m = $('#month').val();
//        var html = '';
//        if(m=="01"){
//            $('.s-year').find('option').each(function(){
//                var t = $(this).html();
//                if($(this).hasClass( 'jan' )){
//                    html += '<option value="'+t+'">'+t+'</option>';
//                }
//            });
//            $('#year').html(html);
//        }else{
//            $('.s-year').find('option').each(function(){
//                var t = $(this).html();
//                if($(this).hasClass( 'july' )){
//                    html += '<option value="'+t+'">'+t+'</option>';
//                }
//            });
//            $('#year').html(html);
//        }

        //Selected class action for Tutuition fee
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

        var s = $('#parent_status').val();
        if(s==1 || s==2 || s==3 || s==4){
            $('#mf_waiver').show();
            $('#ad_waiver').show();
            $('#ev_waiver').show();
            $('#c_lab_waiver').show();
            $('#p_lab_waiver').show();
        }else if(s==0){
            $('#mf_waiver').hide();
            $('#ad_waiver').hide();
            $('#ev_waiver').hide();
            $('#c_lab_waiver').hide();
            $('#p_lab_waiver').hide();
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

    $(document).delegate('#fee_class_id','change',function(){
        var c = $('#fee_class_id option:selected').attr("class");
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


</script>