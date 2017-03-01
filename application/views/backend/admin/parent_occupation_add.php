<style>
    /*.cp-studying th{*/
        /*text-align: center !important;*/
    /*}*/
    /*.h_plus{*/
        /*width: 10%;*/
        /*float: left;*/
        /*padding-top: 5%;*/
    /*}*/
    /*.h_input{*/
        /*width: 90% !important;*/
        /*float: left;*/
    /*}*/
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Add New Parent Occupation');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/parent_occupation/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation Title').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="title" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation For').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <input type="checkbox" name="father" value="1" checked> Father<br>
                        <input type="checkbox" name="mother" value="1" checked> Mother<br>
                    </div>
                </div>

                <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('Add Occupation');?></button>
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