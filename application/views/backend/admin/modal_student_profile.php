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
    .cp-studying th {
        text-align: center !important;
        font-size: 20px;
    }

    .right {
        float: right !important;
        width: 50% !important;
        margin-top: 10%;
    }
    .print_div{
        width: 100%;
        float: left;
    }
    .print_div a{
        font-size: 20px;
        float: right;
    }
    #printme , #DivIdToPrint{
        display: none;
    }
    @media print {
        .page-container{
            display: none;
        }
        #printme {
            display: block;
        }
        h4{
            padding: 0px;
            margin: 3px 0px;
        }
    }
</style>
<?php
$edit_data		=	$this->db->get_where('student' , array('student_id' => $param2) )->result_array();
$font_size = 'style="font-size : 19px;"';
foreach ( $edit_data as $row):
    ?>
    <div class="row">
    <div class="col-md-12">
    <div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title" >
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('student_profile');?>
        </div>
        <div class="print_div">
            <a onclick="printDiv('DivIdToPrint')" href="javascript:void(0)"><i class="entypo-print"></i> Print</a>
        </div>
    </div>
    <div class="panel-body">

    <?php echo form_open(base_url() . 'index.php?admin/student/'.$row['class_id'].'/do_update/'.$row['student_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>



    <div class="form-group">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>

        <div class="col-sm-5 control-info">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                    <img src="<?php echo $this->crud_model->get_image_url('student' , $row['student_id']);?>" alt="...">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student ID');?></label>

        <div class="col-sm-5 control-info">
            <input type="text" class="form-control" name="roll" value="<?php echo $row['roll'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Admitted For The Session');?></label>

        <div class="col-sm-3">
            <select class="form-control" name="s_session" id="month" onchange="" size="1" disabled>
                <option value="01" <?php if($row['s_session']=='01')echo 'selected';?>>January</option>
                <option value="07" <?php if($row['s_session']=='07')echo 'selected';?>>July</option>
            </select>
        </div>
        <div class="col-sm-3">
            <select class="form-control" name="year" id="year" onchange="" size="1" disabled>
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
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>

        <div class="col-sm-5 control-info">
            <select name="class_id" class="form-control" data-validate="required" id="class_id"
                    data-message-required="<?php echo get_phrase('value_required');?>"
                    onchange="return get_class_sections(this.value)" disabled>
                <option value=""><?php echo get_phrase('select');?></option>
                <?php
                $classes = $this->db->get('class')->result_array();
                foreach($classes as $row2):
                    ?>
                    <option value="<?php echo $row2['class_id'];?>"
                        <?php if($row['class_id'] == $row2['class_id']){echo 'selected';$class_name = $row2['name'];}?>>
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
            <select name="section_id" class="form-control" id="section_selector_holder" disabled>
                <option value=""><?php echo get_phrase('select_class_first');?></option>
                <?php
                $sections = $this->db->get_where('section' , array(
                    'class_id' => $row['class_id']
                ))->result_array();
                foreach ($sections as $row2) {
                    if($row['section_id']==$row2['section_id']){
                        $selected = 'selected';
                        $section_name = $row2['name'];
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

        <div class="col-sm-5 control-info">
            <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['name'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('gender');?></label>

        <div class="col-sm-5">
            <select name="gender" class="form-control" disabled>
                <option value=""><?php echo get_phrase('select');?></option>
                <option value="male" <?php if($row['gender'] == 'male')echo 'selected';?>><?php echo get_phrase('male');?></option>
                <option value="female"<?php if($row['gender'] == 'female')echo 'selected';?>><?php echo get_phrase('female');?></option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('present address');?></label>

        <div class="col-sm-5 control-info">
<!--            <input type="text" class="form-control" name="present_address" value="--><?php //echo $row['present_address'];?><!--" disabled>-->
            <textarea rows="2" cols="70" name="present_address" disabled><?php echo $row['present_address'];?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>

        <div class="col-sm-5 control-info">
            <input type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('SMS Number');?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control" name="sms_number" value="<?php echo $row['sms_number'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Permanent Address');?></label>

        <div class="col-sm-5 control-info">
<!--            <input type="text" class="form-control" name="parmanent_address" value="--><?php //echo $row['parmanent_address'];?><!--" disabled>-->
            <textarea rows="2" cols="70" name="parmanent_address" disabled><?php echo $row['parmanent_address'];?></textarea>
        </div>
    </div>


    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('birthday');?></label>

        <div class="col-sm-5 control-info">
            <input type="text" class="form-control datepicker" name="birthday" value="<?php echo $row['birthday'];?>" data-start-view="2" disabled>
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

        <div class="col-sm-5 control-info">
            <input type="text" class="form-control" name="parent_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['parent_id'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Lab');?></label>

        <div class="col-sm-5">
            <select name="lab" class="form-control" id="lab" disabled>
                <option value="1" <?php if($row['c_lab']==0 && $row['p_lab']==0){echo 'selected';$lab = 'None';}?>>None</option>
                <option value="2" <?php if($row['c_lab']>0 && $row['p_lab']==0){echo 'selected';$lab = 'C Lab';}?>>C Lab</option>
                <option value="3" <?php if($row['c_lab']==0 && $row['p_lab']>0){echo 'selected';$lab = 'P Lab';}?>>P Lab</option>
                <option value="4" <?php if($row['c_lab']>0 && $row['p_lab']>0){echo 'selected';$lab = 'Both C and P Lab';}?>>Both Lab</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Yearly Fee');?></label>

        <div class="col-sm-5">
            <input type="checkbox" name="yearly_fee[]" value="admission" <?php if($row['admission']) echo 'checked';?> disabled> Admission Fee<br>
            <input type="checkbox" name="yearly_fee[]" value="evaluation" <?php if($row['evaluation']) echo 'checked';?> disabled> Evaluation Fee
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Parent/Student Status');?></label>

        <div class="col-sm-5 control-info">
            <select name="parent_status" id="parent_status" data-validate="required" class="form-control" disabled>
                <option value="0" <?php if($row['parent_status']==0){echo 'selected';$student_type = 'Non Teacher';}?>>Non Teacher</option>
                <option value="1" <?php if($row['parent_status']==1){echo 'selected';$student_type = 'Teacher';}?>>Teacher</option>
                <option value="2" <?php if($row['parent_status']==2){echo 'selected';$student_type = 'Scholarship';}?>>Scholarship</option>
                <option value="3" <?php if($row['parent_status']==3){echo 'selected';$student_type = 'Teacher + Scholarship';}?>>Teacher + Scholarship</option>
                <option value="4" <?php if($row['parent_status']==4){echo 'selected';$student_type = 'Special';}?>>Special</option>
            </select>
        </div>
    </div>

    <div class="form-group" id="mf_waiver">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Monthly Fee Waiver %');?></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="mf_waiver" value="<?php echo $row['mf_waiver'];?>" disabled>
        </div>
    </div>

    <div class="form-group" id="ad_waiver">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admission Fee Waiver %');?></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="ad_waiver" value="<?php echo $row['ad_waiver'];?>" disabled>
        </div>
    </div>

    <div class="form-group" id="ev_waiver">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Evaluation Waiver %');?></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="ev_waiver" value="<?php echo $row['ev_waiver'];?>" disabled>
        </div>
    </div>

    <div class="form-group" id="c_lab_waiver">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('C Lab Waiver %');?></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="c_lab_waiver" value="<?php echo $row['c_lab_waiver'];?>" disabled>
        </div>
    </div>

    <div class="form-group" id="p_lab_waiver">
        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('P Lab Waiver %');?></label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="p_lab_waiver" value="<?php echo $row['p_lab_waiver'];?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Student Status');?></label>

        <div class="col-sm-5 control-info">
            <select name="student_status" id="student_status" data-validate="required" class="form-control" disabled>
                <option value="1" <?php if($row['active']==1){echo 'selected';$student_status = 'Current';}?>>Current</option>
                <option value="2" <?php if($row['active']==2){echo 'selected';$student_status = 'Old';}?>>Old</option>
                <option value="0" <?php if($row['active']==0){echo 'selected';$student_status = 'Alumni';}?>>Alumni</option>
            </select>
        </div>
    </div>


<!--    <div class="form-group">-->
<!--        <label for="field-1" class="col-sm-3 control-label">--><?php //echo get_phrase('email');?><!--</label>-->
<!--        <div class="col-sm-5 control-info">-->
<!--            <input type="text" class="form-control" name="email" value="--><?php //echo $row['email'];?><!--" disabled>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('password');?><!--</label>-->
<!--        -->
<!--        <div class="col-sm-5">-->
<!--            <input type="password" class="form-control" name="password" value="--><?php //echo $row['password'];?><!--" >-->
<!--        </div> -->
<!--    </div>-->

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
                    <td><input type="text" class="form-control" name="institution_1" value="<?php echo $row['institution_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="class_1" value="<?php echo $row['class_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="year_1" value="<?php echo $row['year_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="position_1" value="<?php echo $row['position_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="passed_1" value="<?php echo $row['passed_1'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="institution_2" value="<?php echo $row['institution_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="class_2" value="<?php echo $row['class_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="year_2" value="<?php echo $row['year_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="position_2" value="<?php echo $row['position_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="passed_2" value="<?php echo $row['passed_2'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="institution_3" value="<?php echo $row['institution_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="class_3" value="<?php echo $row['class_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="year_3" value="<?php echo $row['year_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="position_3" value="<?php echo $row['position_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="passed_3" value="<?php echo $row['passed_3'];?>" autofocus disabled></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Reason For Leaving Institution');?></label>

        <div class="col-sm-5 control-info">
            <textarea rows="5" cols="50" name="reason_for_leaving" disabled><?php echo $row['reason_for_leaving'];?></textarea>
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
                    <td><input type="text" class="form-control" name="c_brother_1" value="<?php echo $row['c_brother_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_class_1" value="<?php echo $row['cb_class_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_section_1" value="<?php echo $row['cb_section_1'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="c_brother_2" value="<?php echo $row['c_brother_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_class_2" value="<?php echo $row['cb_class_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_section_2" value="<?php echo $row['cb_section_2'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="c_brother_3" value="<?php echo $row['c_brother_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_class_3" value="<?php echo $row['cb_class_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="cb_section_3" value="<?php echo $row['cb_section_3'];?>" autofocus disabled></td>
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
                    <td><input type="text" class="form-control" name="p_brothers_1" value="<?php echo $row['p_brothers_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_class_1" value="<?php echo $row['pb_class_1'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_section_1" value="<?php echo $row['pb_section_1'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="p_brothers_2" value="<?php echo $row['p_brothers_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_class_2" value="<?php echo $row['pb_class_2'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_section_2" value="<?php echo $row['pb_section_2'];?>" autofocus disabled></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="p_brothers_3" value="<?php echo $row['p_brothers_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_class_3" value="<?php echo $row['pb_class_3'];?>" autofocus disabled></td>
                    <td><input type="text" class="form-control" name="pb_section_3" value="<?php echo $row['pb_section_3'];?>" autofocus disabled></td>
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
        <div class="col-sm-5 control-info">
            <select name="building_info" class="form-control" id="building_info"
                    data-message-required="<?php echo get_phrase('value_required');?>" disabled>
                <?php
                $buildings = $this->db->get('building')->result_array();
                foreach($buildings as $building):
                    if($building['id']==$row['building_info']){
                        $selected = 'selected';
                        $building_name = $building['building_name'];
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
        <div class="col-sm-5 control-info">
            <select name="branch_info" class="form-control" id="branch_info"
                    data-message-required="<?php echo get_phrase('value_required');?>" disabled>
                <?php
                $branchs = $this->db->get('branch')->result_array();
                foreach($branchs as $branch):
                    if($branch['branch_id']==$row['branch_info']){
                        $selected = 'selected';
                        $branch_name = $branch['branch_name'];
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
                    data-message-required="<?php echo get_phrase('value_required');?>" disabled>
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
            <input type="text" class="form-control" name="special_monthly_fee" value="<?php echo $row['special_monthly_fee']?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special Admission Fee');?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control" name="special_admission_fee" value="<?php echo $row['special_admission_fee']?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special Evaluation Fee');?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control" name="special_evaluation_fee" value="<?php echo $row['special_evaluation_fee']?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special C Lab Fee');?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control" name="special_c_lab_fee" value="<?php echo $row['special_c_lab_fee']?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Special P Lab Fee');?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control" name="special_p_lab_fee" value="<?php echo $row['special_p_lab_fee']?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Admission Date').'<span style="color:red">*</span>';?></label>

        <div class="col-sm-5">
            <input type="text" class="form-control datepicker" name="admission_date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo getbddate($row['admission_date']);?>" data-start-view="2" disabled>
        </div>
    </div>

    <?php echo form_close();?>
    </div>
    </div>
    </div>
    </div>
    <div id="DivIdToPrint">
        <div style="width: 100%;float: left;">
            <div style="width: 100%;float: left;">
                <div style="width: 20%;float: left;margin-top: 1%;">
                    <img src="<?php echo base_url().'assets/images/logo.png'?>" width="80px" height="80px">
                </div>
                <div style="width: 60%;float: left;">
                    <h3 style="text-align: center;">MAPLE LEAF INTERNATIONAL SCHOOL</h3>
                    <h4 style="text-align: center;">DHANMONDI , DHAKA</h4>
                    <h4 style="text-align: center;">Central VAT Registered No : 19151048296</h4>
                    <h4 style="text-align: center;">Area Code : 190403</h4>
                </div>
                <div style="width: 20%;float: left;margin-top: 7.5%;">
                    <h4 style="text-align: right;">Session : <?php if($row['s_session']=='01'){echo 'January';}else{echo 'July';}?></h4>
                    <h4 style="text-align: right;">Class : <?php echo $class_name; ?></h4>
                    <h4 style="text-align: right;">Section : <?php echo $section_name; ?></h4>
                </div>
            </div>
            <hr style="width: 100%;float: left;">
            <div style="width: 100%;float: left;margin-top: 2%;">
                <h2>Basic Information :</h2>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Student Name : <?php echo $row['name'];?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Student ID : <?php echo $row['roll'];?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Session : <?php if($row['s_session']=='01'){echo 'January';}else{echo 'July';}?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Year : <?php echo $row['year'];?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Gender : <?php echo $row['gender'];?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Phone : <?php echo $row['phone'];?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Present Address : <?php echo $row['present_address'];?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Permanent Address : <?php echo $row['parmanent_address'];?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Birthday : <?php echo $row['birthday'];?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Admission Date : <?php echo date('d/m/Y',strtotime($row['admission_date']));?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>SMS Number : <?php echo $row['sms_number'];?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Associated Lab : <?php echo $lab;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Student Type : <?php echo $student_type;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Student Status : <?php echo $student_status;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Branch : <?php echo $branch_name;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Building : <?php echo $building_name;?></h4>
                    </div>
                </div>
            </div>
            <div style="width: 100%;float: left;margin-top: 2%;">
                <h2>Academic Fees :</h2>
                <?php
                $academic_fees = $this->db->get_where('academic_fees ' , array(
                    'class_id' => $row['class_id'],
                    'year' => $row['year']
                ))->row();
                ?>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Monthly Fee : <?php echo $fee = ($row['special_monthly_fee'] > 0) ? $row['special_monthly_fee'] : $academic_fees->mf;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Admission Fee : <?php echo $fee = ($row['special_admission_fee'] > 0) ? $row['special_admission_fee'] : $academic_fees->ad;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Evaluation Fee : <?php echo $fee = ($row['special_evaluation_fee'] > 0) ? $row['special_evaluation_fee'] : $academic_fees->ev;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>C Lab Fee : <?php echo $fee = ($row['special_c_lab_fee'] > 0) ? $row['special_c_lab_fee'] : $academic_fees->c_lab;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>P Lab Fee : <?php echo $fee = ($row['special_p_lab_fee'] > 0) ? $row['special_p_lab_fee'] : $academic_fees->p_lab;?></h4>
                    </div>
                </div>
            </div>
            <div style="width: 100%;float: left;margin-top: 2%;">
                <h2>Parent Information :</h2>
                <?php
                $parent_info = $this->db->get_where('parent ' , array(
                    'parent_id' => $row['parent_id']
                ))->row();
                ?>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h3>Father Information :</h3>
                        <h4 <?php echo $font_size;?>>Name : <?php echo $parent_info->father_name;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h3>Mother Information :</h3>
                        <h4 <?php echo $font_size;?>>Name : <?php echo $parent_info->mother_name;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Nationality : <?php echo $parent_info->father_nationality;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Nationality : <?php echo $parent_info->mother_nationality;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Occupation : <?php echo $this->crud_model->get_type_name_by_id('parent_occupation',$parent_info->father_occupation,'title');?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Occupation : <?php echo $this->crud_model->get_type_name_by_id('parent_occupation',$parent_info->mother_occupation,'title');?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Designation : <?php echo $parent_info->father_designation;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Designation : <?php echo $parent_info->mother_designation;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>TIN No : <?php echo $parent_info->father_tin;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>TIN No : <?php echo $parent_info->mother_tin;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>National ID No : <?php echo $parent_info->father_nid;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>National ID No : <?php echo $parent_info->mother_nid;?></h4>
                    </div>
                </div>
                <div style="width: 100%;float: left;">
                    <div style="width: 45%;float: left;">
                        <h4 <?php echo $font_size;?>>Phone : <?php echo $parent_info->phone;?></h4>
                    </div>
                    <div style="width: 45%;float: left;margin-left: 10%;">
                        <h4 <?php echo $font_size;?>>Address : <?php echo $parent_info->address;?></h4>
                    </div>
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

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
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

    function printDiv(print) {
        var print_content = $('#'+print).html();
        $("#printme").html(print_content);
        window.print();
        $("#printme").html("");
    }
</script>