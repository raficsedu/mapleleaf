<style>
    .singRow{
        width: 50%;
        float: left;
        list-style: none;
    }
    .checkbox{
        margin-left: 27%;
    }
</style>
<?php
$edit_data		=	$this->db->get_where('branch' , array('branch_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Edit Branch');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/branch/do_update/'.$row['branch_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Branch Name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="branch_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row['branch_name'];?>">
						</div>
					</div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Associate Buildings');?></label>

                    <div class="col-sm-10 checkbox">
                        <?php
                        $query = $this->db->query("SELECT buildings FROM branch WHERE branch_id='$row[branch_id]'");
                        $temp = $query->row();
                        $b = explode(',',$temp->buildings);
                        $buildings = $this->db->get('building')->result_array();
                        foreach($buildings as $building):
                            if(in_array($building['id'],$b)){
                                $checked = 'checked';
                            }else{
                                $checked = '';
                            }
                            echo '<li class="singRow"><input type="checkbox" name="buildings[]" value="'.$building['id'].'" class="chkBox" '.$checked.'>'.$building['building_name'].'</li>';
                        endforeach;
                        ?>
                    </div>
                </div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit branch');?></button>
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