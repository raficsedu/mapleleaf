<?php 
	$edit_data = $this->db->get_where('section' , array(
		'section_id' => $param2
	))->result_array();
	foreach ($edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_new_section');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/sections/edit/' . $row['section_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" 
								value="<?php echo $row['name'];?>">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('nick_name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="nick_name" 
								value="<?php echo $row['nick_name'];?>" >
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
						<div class="col-sm-5">
							<select name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
									$classes = $this->db->get('class')->result_array();
									foreach($classes as $row2):
										?>
                                		<option value="<?php echo $row2['class_id'];?>"
                                			<?php if ($row['class_id'] == $row2['class_id'])
                                				echo 'selected';?>>
													<?php echo $row2['name'];?>
                                        </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('teacher');?></label>
                        
						<div class="col-sm-5">
							<select name="teacher_id" class="form-control">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
									$teachers = $this->db->get('teacher')->result_array();
									foreach($teachers as $row3):
										?>
                                		<option value="<?php echo $row3['teacher_id'];?>"
                                			<?php if ($row['teacher_id'] == $row3['teacher_id'])
                                				echo 'selected';?>>
													<?php echo $row3['name'];?>
                                        </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Session').'<span style="color:red">*</span>';?></label>
                    <div class="col-sm-5">
                        <select name="session" id="session" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value="01" <?php if($row['session']=='01')echo 'selected';?>>JAN</option>
                            <option value="07" <?php if($row['session']=='07')echo 'selected';?>>JUL</option>
                        </select>
                    </div>
                </div>

                <div id="01" style="margin-top: 2%;">
                    <?php
                    $capacity = unserialize($row['capacity']);
                    $sy = 2014;
                    $ey = date('Y') + 1;
                    for($i = $sy ; $i <= $ey ; $i++){
                        ?>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo 'Capacity for '.$i.'<span style="color:red">*</span>';?></label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="capacity_<?php echo $i;?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php if(isset($capacity['capacity_'.$i]))echo $capacity['capacity_'.$i];?>" autofocus>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div id="07" style="margin-top: 2%;">
                    <?php
                    $capacity = unserialize($row['capacity']);
                    $sy = 2014;
                    $ey = date('Y') + 1;
                    for($i = $sy ; $i <= $ey ; $i++){
                        $ny = $i.'-'.($i+1);
                        ?>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?php echo 'Capacity for '.$ny.'<span style="color:red">*</span>';?></label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="capacity_<?php echo $ny;?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php if(isset($capacity['capacity_'.$ny]))echo $capacity['capacity_'.$ny];?>" autofocus>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('update');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>
<script>
    $(function(){
        var session = $('#session').val();
        if(session=="01"){
            $('#01').show();
            $('#07').hide();
        }else{
            $('#01').hide();
            $('#07').show();
        }
    });

    $('#session').change(function(){
        var session = $('#session').val();
        if(session=="01"){
            $('#01').show();
            $('#07').hide();
        }else{
            $('#01').hide();
            $('#07').show();
        }
    });
</script>