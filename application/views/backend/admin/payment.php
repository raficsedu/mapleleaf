<style>
    .cp-studying th{
        text-align: center !important;
        font-size: 20px;
    }
    .right{
        float: right !important;
        width: 50% !important;
        margin-top: 10%;
    }
    .deleted{
        background-color: #F5A9A9;
    }
    .old{
        background-color: #D8D8D8;
    }
    #remission{
        /*display: none;*/
    }
</style>
<hr />
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/payment_add/');"
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('New Payment');?>
    </a> 
<br>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('All Payments');?></span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th class="span3"><div><?php echo get_phrase('Payment ID');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Pay To');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Address');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Purpose');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Amount');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Branch');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Payment By');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Date');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $level = $_SESSION['level'];
                    if($level==1){
                        $invoices   =   $this->db->get('payment' )->result_array();
                    }else{
                        $invoices   =   $this->db->get_where('payment',array('deleted'=>0,'collector_id'=>$_SESSION['admin_id']) )->result_array();
                    }

                    foreach($invoices as $row):
                        if($row['deleted']==1){
                            echo '<tr class="deleted">';
                        }else{
                            echo '<tr>';
                        }
                        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
                        ?>
                        <td><?php echo $row['payment_id'];?></td>
                        <td><?php echo $row['paid_to'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $row['purpose'];?></td>
                        <td><?php echo $row['total_amount'];?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('branch',$row['branch_id'],'branch_name');?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('admin',$row['collector_id']);?></td>
                        <td><?php $t = explode('-',$row['timestamp']);$date = $t[2].'-'.$t[1].'-'.$t[0];echo $date;?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                    <!-- VIEWING LINK -->
                                    <li>
                                        <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_view_payment/<?php echo $row['payment_id'];?>');">
                                            <i class="entypo-credit-card"></i>
                                            <?php echo get_phrase('view_payment');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>

                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_payment_edit/<?php echo $row['payment_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>

                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="javascript:void(0)" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/payment/delete/<?php echo $row['payment_id'];?>');">
                                            <i class="entypo-trash"></i>
                                            <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        <?php 
            $query = $this->db->get_where('section' , array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
        ?>
            <div class="tab-pane" id="<?php echo $row['section_id'];?>">
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('roll');?></div></th>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $students   =   $this->db->get_where('student' , array(
                                    'class_id'=>$class_id , 'section_id' => $row['section_id']
                                ))->result_array();
                                foreach($students as $row):?>
                        <tr>
                            <td><?php echo $row['roll'];?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $row['address'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- STUDENT PROFILE LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('profile');?>
                                                </a>
                                        </li>
                                        
                                        <!-- STUDENT EDITING LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                        </li>
                                        <li class="divider"></li>
                                        
                                        <!-- STUDENT DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/student/<?php echo $class_id;?>/delete/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete');?>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        <?php endforeach;?>
        <?php endif;?>

        </div>
        
        
    </div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>