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
				
                <?php //echo form_open(base_url() . 'index.php?admin/parent/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>index.php?admin/parent/create" autocomplete="off" method="post" enctype="multipart/form-data">
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Father\'s / Guardian Name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="father_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                            	value="">
						</div>
					</div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>

                        <div class="col-sm-5">
                            <input id="phone" type="text" class="form-control" name="phone" value="" onkeyup="parentSearch(this.value)">
                        </div>
                        <div class="col-sm-3">
                            <p id="duplicate_parent" style="color: red;font-size: 20px;"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nationality');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="father_nationality" data-validate="required">
                                <option value="bangladeshi">Bangladeshi</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="father_occupation" data-validate="required">
                                <?php
                                $father_occupations = $this->db->get_where('parent_occupation', array(
                                    'father' => 1
                                ))->result_array();
                                foreach($father_occupations as $s){
                                    echo '<option value="'.$s['parent_occupation_id'].'">'.$s['title'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Designation');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_designation" autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('TIN No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_tin" autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Name of the organization');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_organization"  autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('National ID No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="father_nid"  autofocus value="">
                        </div>
                    </div>
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
                                            <input type="file" name="father_file" accept="image/*">
                                        </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Mother\'s / Guardian Name');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Nationality');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="mother_nationality" data-validate="required">
                                <option value="bangladeshi">Bangladeshi</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Occupation');?></label>

                        <div class="col-sm-5">
                            <select class="form-control" name="mother_occupation" data-validate="required">
                                <?php
                                $mother_occupations = $this->db->get_where('parent_occupation', array(
                                    'mother' => 1
                                ))->result_array();
                                foreach($mother_occupations as $s){
                                    echo '<option value="'.$s['parent_occupation_id'].'">'.$s['title'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Designation');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_designation" autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('TIN No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_tin" autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Name of the organization');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_organization"  autofocus
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('National ID No');?></label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="mother_nid"  autofocus value="">
                        </div>
                    </div>
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
                                                <input type="file" name="mother_file" accept="image/*">
                                            </span>
                                    <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <hr>

<!--					<div class="form-group">-->
<!--						<label for="field-1" class="col-sm-3 control-label">--><?php //echo get_phrase('email');?><!--</label>-->
<!--						<div class="col-sm-5">-->
<!--							<input type="text" class="form-control" name="email" id="email" value="">-->
<!--						</div>-->
<!--					</div>-->
<!--					-->
<!--					<div class="form-group">-->
<!--						<label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('password');?><!--</label>-->
<!--                        -->
<!--						<div class="col-sm-5">-->
<!--							<input type="password" class="form-control" name="password" value="">-->
<!--						</div>-->
<!--					</div>-->
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('address');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="address" value="">
						</div>
					</div>
					
<!--					<div class="form-group">-->
<!--						<label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('profession');?><!--</label>-->
<!--                        -->
<!--						<div class="col-sm-5">-->
<!--							<input type="text" class="form-control" name="profession" value="">-->
<!--						</div>-->
<!--					</div>-->
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-default"><?php echo get_phrase('add_parent');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function parentSearch(str){
        if(str!=""){
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/parent_search/' + str,
                success: function(response)
                {
                    if(response){
                        var html = 'Duplicate Found , ID : ' + response;
                        $('#duplicate_parent').html(html);
                    }else{
                        $('#duplicate_parent').html('');
                    }
                }
            });
        }else{
            $('#duplicate_parent').html('');
        }
    }
    $("#phone").bind("paste", function(e){
        // access the clipboard using the api
        var pastedData = e.originalEvent.clipboardData.getData('text');
        parentSearch(pastedData);
    } );
</script>