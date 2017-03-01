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
$edit_data		=	$this->db->get_where('payment' , array('payment_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Edit Payment');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/payment/do_update/'.$row['payment_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                    <div class="col-sm-9">
                        <input type="text" class="datepicker form-control" name="date" value="<?php $t = explode('-',$row['timestamp']);$date = $t[2].'/'.$t[1].'/'.$t[0];echo $date;?>"/ >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Book No');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="book_no" class="form-control" name="book_no" value="<?php echo $row['book_no'];?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Paid To');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="paid_to" class="form-control" name="paid_to" value="<?php echo $row['paid_to'];?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Address');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="address" class="form-control" name="address" value="<?php echo $row['address'];?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Purpose');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="purpose" class="form-control" name="purpose" value="<?php echo $row['purpose'];?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Total Taka');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="total_amount" class="form-control" name="total_amount" value="<?php echo $row['total_amount'];?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Branch Name');?></label>
                    <div class="col-sm-9">
                        <select id="branch_name" name="branch_name" class="form-control">
                            <?php
                            $branch_id = $_SESSION['branch'];
                            $level = $_SESSION['level'];
                            if($level==1){
                                $buildings = $this->db->get('branch')->result_array();
                                foreach($buildings as $building):
                                    ?>
                                    <option value="<?php echo $building['branch_id'];?>" <?php if($building['branch_id']==$row['branch_id'])echo 'selected'?>>
                                        <?php echo $building['branch_name'];?>
                                    </option>
                                <?php
                                endforeach;
                            }else{
                                $buildings = $this->db->get('branch')->result_array();
                                foreach($buildings as $building):
                                    if($branch_id==$building['branch_id']){
                                        ?>
                                        <option value="<?php echo $building['branch_id'];?>">
                                            <?php echo $building['branch_name'];?>
                                        </option>
                                    <?php
                                    }
                                endforeach;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit payment');?></button>
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