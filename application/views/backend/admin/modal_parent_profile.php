<?php
$edit_data = $this->db->get_where('parent' , array('parent_id' => $param2))->result_array();
foreach ($edit_data as $row):
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('add_parent');?>
                    </div>
                </div>
                <div class="panel-body">

                    <?php echo form_open(base_url() . 'index.php?admin/parent/edit/' . $row['parent_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Father\'s / Guardian Name');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                                   value="<?php echo $row['father_name'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nationality');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="father_nationality" data-validate="required" disabled>
                                <option value="bangladeshi" <?php if($row['father_nationality']=='bangladeshi')echo 'selected';?>>Bangladeshi</option>
                                <option value="other" <?php if($row['father_nationality']=='other')echo 'selected';?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="father_occupation" data-validate="required" disabled>
                                <?php
                                $father_occupations = $this->db->get_where('parent_occupation', array(
                                    'father' => 1
                                ))->result_array();
                                foreach($father_occupations as $s){
                                    if($s['parent_occupation_id']==$row['father_occupation']){
                                        $selected = 'selected';
                                    }else{
                                        $selected = '';
                                    }
                                    echo '<option value="'.$s['parent_occupation_id'].'" '.$selected.'>'.$s['title'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Designation');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_designation" autofocus
                                   value="<?php echo $row['father_designation'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('TIN No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_tin" autofocus
                                   value="<?php echo $row['father_tin'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Name of the organization');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_organization"  autofocus
                                   value="<?php echo $row['father_organization'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('National ID No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_nid"  autofocus value="<?php echo $row['father_nid'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>

                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                    <img src="<?php echo $this->crud_model->get_image_url('parent' , 'father'.$row['parent_id']);?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Mother\'s / Guardian Name');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                                   value="<?php echo $row['mother_name'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nationality');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="mother_nationality" data-validate="required" disabled>
                                <option value="bangladeshi" <?php if($row['mother_nationality']=='bangladeshi')echo 'selected';?>>Bangladeshi</option>
                                <option value="other" <?php if($row['mother_nationality']=='other')echo 'selected';?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="mother_occupation" data-validate="required" disabled>
                                <?php
                                $mother_occupations = $this->db->get_where('parent_occupation', array(
                                    'mother' => 1
                                ))->result_array();
                                foreach($mother_occupations as $s){
                                    if($s['parent_occupation_id']==$row['mother_occupation']){
                                        $selected = 'selected';
                                    }else{
                                        $selected = '';
                                    }
                                    echo '<option value="'.$s['parent_occupation_id'].'" '.$selected.'>'.$s['title'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Designation');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_designation" autofocus
                                   value="<?php echo $row['mother_designation'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('TIN No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_tin" autofocus
                                   value="<?php echo $row['mother_tin'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Name of the organization');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_organization"  autofocus
                                   value="<?php echo $row['mother_organization'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('National ID No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_nid"  autofocus value="<?php echo $row['mother_nid'];?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>

                        <div class="col-sm-5">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                    <img src="<?php echo $this->crud_model->get_image_url('parent' , 'mother'.$row['parent_id']);?>" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <hr>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="phone" value="<?php echo $row['phone'];?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('address');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="address" value="<?php echo $row['address'];?>" disabled>
                        </div>
                    </div>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach;?>