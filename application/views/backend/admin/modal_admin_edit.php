<?php 
$edit_data		=	$this->db->get_where('admin' , array('admin_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Edit Admin');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/manage_admin/do_update/'.$row['admin_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>


                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admin Name').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="admin_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['name'];?>" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admin Email').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="admin_email" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['email'];?>" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Admin Password').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="admin_password" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['password'];?>" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Special ID').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="special_id" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['special_id'];?>" autofocus>
                    </div>
                </div>


                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Access Level').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <select name="access_level" class="form-control" data-validate="required" id="access_level"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <option value="1" <?php if($row['level']==1)echo 'selected';?>><?php echo get_phrase('Administrator');?></option>
                            <option value="10" <?php if($row['level']==10)echo 'selected';?>><?php echo get_phrase('Student Manager');?></option>
                            <option value="11" <?php if($row['level']==11)echo 'selected';?>><?php echo get_phrase('Payment Manager');?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Branch Information');?></label>
                    <div class="col-sm-5">
                        <select name="branch_info" class="form-control" id="branch_info"
                                data-message-required="<?php echo get_phrase('value_required');?>">
                            <option value=""><?php echo get_phrase('select branch');?></option>
                            <?php
                            $branchs = $this->db->get('branch')->result_array();
                            foreach($branchs as $branch):
                                if($row['branch_id']==$branch['branch_id']){
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
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit admin');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
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

    }

    var class_id = $("#class_id").val();
    
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });


</script>