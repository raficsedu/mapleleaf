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
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Add New Branch');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/branch/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Branch Name');?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="branch_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Associate Buildings');?></label>

                    <div class="col-sm-10 checkbox">
                        <?php
                        $buildings = $this->db->get('building')->result_array();
                        foreach($buildings as $building):
                            echo '<li class="singRow"><input type="checkbox" name="buildings[]" value="'.$building['id'].'" class="chkBox">'.$building['building_name'].'</li>';
                        endforeach;
                        ?>
                    </div>
                </div>

                <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('Add Branch');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

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

</script>