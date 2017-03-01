<style>
    .modal-dialog {
        width: 1080px !important;
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .form-horizontal .control-label {
        text-align: left;
    }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_academic_fees');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/academic_fees/do_update/'.$param2 , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class').'<span style="color:red">*</span>';?></label>

                    <div class="col-sm-5">
                        <select name="class_id" class="form-control" data-validate="required" id="class_id"
                                data-message-required="<?php echo get_phrase('value_required');?>" disabled>
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $row):
                                if($row['class_id'] == $param2){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }
                                ?>
                                <option value="<?php echo $row['class_id'];?>" <?php echo $selected;?>>
                                    <?php echo $row['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                $sy = 2014;
                $ey = date('Y') + 1;
                for($i = $sy ; $i <= $ey ; $i++){
                    $ny = $i.'-'.($i+1);
                    $jan = $this->db->get_where('academic_fees' , array('class_id' => $param2,'year' => $i))->row();
                    $jul = $this->db->get_where('academic_fees' , array('class_id' => $param2,'year' => $ny))->row();
                ?>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"><?php echo $i;?></label>

                        <div class="col-sm-11">
                            <div class="col-sm-2">
                                <label class="col-sm-5">MF</label>
                                <input class="col-sm-7" type="text" class="form-control" name="mf_<?php echo $i;?>" value="<?php if(isset($jan->mf))echo $jan->mf;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">AD</label>
                                <input class="col-sm-7" type="text" class="form-control" name="ad_<?php echo $i;?>" value="<?php if(isset($jan->ad))echo $jan->ad;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">EV</label>
                                <input class="col-sm-7" type="text" class="form-control" name="ev_<?php echo $i;?>" value="<?php if(isset($jan->ev))echo $jan->ev;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">C Lab</label>
                                <input class="col-sm-7" type="text" class="form-control" name="c_lab_<?php echo $i;?>" value="<?php if(isset($jan->c_lab))echo $jan->c_lab;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">P Lab</label>
                                <input class="col-sm-7" type="text" class="form-control" name="p_lab_<?php echo $i;?>" value="<?php if(isset($jan->p_lab))echo $jan->p_lab;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">TC</label>
                                <input class="col-sm-7" type="text" class="form-control" name="tc_<?php echo $i;?>" value="<?php if(isset($jan->tc))echo $jan->tc;?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"><?php echo $ny;?></label>

                        <div class="col-sm-11">
                            <div class="col-sm-2">
                                <label class="col-sm-5">MF</label>
                                <input class="col-sm-7" type="text" class="form-control" name="mf_<?php echo $ny;?>" value="<?php if(isset($jul->mf))echo $jul->mf;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">AD</label>
                                <input class="col-sm-7" type="text" class="form-control" name="ad_<?php echo $ny;?>" value="<?php if(isset($jul->ad))echo $jul->ad;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">EV</label>
                                <input class="col-sm-7" type="text" class="form-control" name="ev_<?php echo $ny;?>" value="<?php if(isset($jul->ev))echo $jul->ev;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">C Lab</label>
                                <input class="col-sm-7" type="text" class="form-control" name="c_lab_<?php echo $ny;?>" value="<?php if(isset($jul->c_lab))echo $jul->c_lab;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">P Lab</label>
                                <input class="col-sm-7" type="text" class="form-control" name="p_lab_<?php echo $ny;?>" value="<?php if(isset($jul->p_lab))echo $jul->p_lab;?>">
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-5">TC</label>
                                <input class="col-sm-7" type="text" class="form-control" name="tc_<?php echo $ny;?>" value="<?php if(isset($jul->tc))echo $jul->tc;?>">
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_fee');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>