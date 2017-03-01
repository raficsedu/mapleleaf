<style>
    .deleted{
        background-color: #F5A9A9;
    }
    .old{
        background-color: #D8D8D8;
    }
</style>
<hr />
<!--<a href="javascript:;" onclick="showAjaxModal('--><?php //echo base_url();?><!--index.php?modal/popup/student_add/');" -->
<!--    class="btn btn-primary pull-right">-->
<!--        <i class="entypo-plus-circled"></i>-->
<!--        --><?php //echo get_phrase('add_new_student');?>
<!--    </a> -->
<!--<br>-->

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>
<!--        --><?php //
//            $query = $this->db->get_where('section' , array('class_id' => $class_id));
//            if ($query->num_rows() > 0):
//                $sections = $query->result_array();
//                foreach ($sections as $row):
//        ?>
<!--            <li>-->
<!--                <a href="#--><?php //echo $row['section_id'];?><!--" data-toggle="tab">-->
<!--                    <span class="visible-xs"><i class="entypo-user"></i></span>-->
<!--                    <span class="hidden-xs">--><?php //echo get_phrase('section');?><!-- --><?php //echo $row['name'];?><!-- ( --><?php //echo $row['nick_name'];?><!-- )</span>-->
<!--                </a>-->
<!--            </li>-->
<!--        --><?php //endforeach;?>
<!--        --><?php //endif;?>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="70"><div><?php echo get_phrase('SL');?></div></th>
                            <th width="100"><div><?php echo get_phrase('Student ID');?></div></th>
                            <th width="100"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('section');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('parent');?></div></th>
                            <th><div><?php echo get_phrase('phone');?></div></th>
                            <th><div><?php echo get_phrase('Admission Class');?></div></th>
                            <?php
                                if($_SESSION['level']==1){
                                    echo '<th><div>'.get_phrase('Added By').'</div></th>';
                                    echo '<th><div>'.get_phrase('Edited By').'</div></th>';
                                }
                            ?>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $query = $this->db->query("SELECT * FROM student WHERE class_id='$class_id' AND deleted='0' ORDER BY student_id DESC , active DESC");
                                $students = $query->result_array();

                                //$students   =   $this->db->get_where('student' , array('class_id'=>$class_id))->result_array();
                                $sl = 0;
                                foreach($students as $row):
                                    $sl++;
                                    if($row['deleted']==1){
                                        echo '<tr class="deleted">';
                                    }else if($row['active']==0){
                                        echo '<tr class="old">';
                                    }else{
                                        echo '<tr>';
                                    }
                        ?>
                            <td><?php echo $sl;?></td>
                            <td><?php echo $row['roll'];?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $this->crud_model->get_type_name_by_id('section',$row['section_id']);?></td>
                            <td><?php $parent = $this->crud_model->get_parent_info($row['parent_id']);if(isset($parent[0]['father_name']))echo $parent[0]['father_name'];?></td>
                            <td><?php echo $row['phone'];?></td>
                            <td><?php echo $this->crud_model->get_type_name_by_id('class',$row['admission_class']);?></td>
                            <?php
                            if($_SESSION['level']==1){
                                echo '<th>'.$this->crud_model->get_type_name_by_id('admin',$row['operator_id'],'special_id').'</th>';
                                echo '<th>'.$this->crud_model->get_type_name_by_id('admin',$row['edited_operator_id'],'special_id').'</th>';
                            }
                            ?>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        <!-- STUDENT PROFILE LINK -->
                                        <li>
                                            <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('profile');?>
                                                </a>
                                        </li>
                                        
                                        <!-- STUDENT EDITING LINK -->
                                        <li>
                                            <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                        </li>
                                        <li class="divider"></li>
                                        
                                        <!-- STUDENT DELETION LINK -->
                                        <?php
                                        if($row['deleted']==1){
                                        ?>
                                            <li>
                                                <a href="javascript:void(0)" onclick="reactivate_modal('<?php echo base_url();?>index.php?admin/student/<?php echo $class_id;?>/reactivate/<?php echo $row['student_id'];?>');">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('Reactivate');?>
                                                </a>
                                            </li>
                                        <?php
                                        }else{
                                        ?>
                                            <li>
                                                <a href="javascript:void(0)" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/student/<?php echo $class_id;?>/delete/<?php echo $row['student_id'];?>');">
                                                    <i class="entypo-trash"></i>
                                                    <?php echo get_phrase('delete');?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
<!--        --><?php //
//            $query = $this->db->get_where('section' , array('class_id' => $class_id));
//            if ($query->num_rows() > 0):
//                $sections = $query->result_array();
//                foreach ($sections as $section):
//        ?>
<!--            <div class="tab-pane" id="--><?php //echo $section['section_id'];?><!--">-->
<!--                -->
<!--                <table class="table table-bordered datatable">-->
<!--                    <thead>-->
<!--                        <tr>-->
<!--                            <th width="80"><div>--><?php //echo get_phrase('roll');?><!--</div></th>-->
<!--                            <th width="80"><div>--><?php //echo get_phrase('photo');?><!--</div></th>-->
<!--                            <th><div>--><?php //echo get_phrase('name');?><!--</div></th>-->
<!--                            <th class="span3"><div>--><?php //echo get_phrase('Parent');?><!--</div></th>-->
<!--                            <th><div>--><?php //echo get_phrase('phone');?><!--</div></th>-->
<!--                            <th><div>--><?php //echo get_phrase('options');?><!--</div></th>-->
<!--                        </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                        --><?php //
//
//                                foreach($students as $row):
//                                    if($row['section_id']==$section['section_id']){
//                                        if($row['deleted']==1){
//                                            echo '<tr class="deleted">';
//                                        }else if($row['active']==0){
//                                            echo '<tr class="old">';
//                                        }else{
//                                            echo '<tr>';
//                                        }
//                                        ?>
<!--                                        <td>--><?php //echo $row['roll'];?><!--</td>-->
<!--                                        <td><img src="--><?php //echo $this->crud_model->get_image_url('student',$row['student_id']);?><!--" class="img-circle" width="30" /></td>-->
<!--                                        <td>--><?php //echo $row['name'];?><!--</td>-->
<!--                                        <td>--><?php //$parent = $this->crud_model->get_parent_info($row['parent_id']);echo $parent[0]['father_name'];?><!--</td>-->
<!--                                        <td>--><?php //echo $row['phone'];?><!--</td>-->
<!--                                        <td>-->
<!---->
<!--                                            <div class="btn-group">-->
<!--                                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">-->
<!--                                                    Action <span class="caret"></span>-->
<!--                                                </button>-->
<!--                                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">-->
<!---->
<!--                                                    <!-- STUDENT PROFILE LINK -->
<!--                                                    <li>-->
<!--                                                        <a href="#" onclick="showAjaxModal('--><?php //echo base_url();?><!--index.php?modal/popup/modal_student_profile/--><?php //echo $row['student_id'];?><!--');">-->
<!--                                                            <i class="entypo-user"></i>-->
<!--                                                            --><?php //echo get_phrase('profile');?>
<!--                                                        </a>-->
<!--                                                    </li>-->
<!---->
<!--                                                    <!-- STUDENT EDITING LINK -->
<!--                                                    <li>-->
<!--                                                        <a href="#" onclick="showAjaxModal('--><?php //echo base_url();?><!--index.php?modal/popup/modal_student_edit/--><?php //echo $row['student_id'];?><!--');">-->
<!--                                                            <i class="entypo-pencil"></i>-->
<!--                                                            --><?php //echo get_phrase('edit');?>
<!--                                                        </a>-->
<!--                                                    </li>-->
<!--                                                    <li class="divider"></li>-->
<!---->
<!--                                                    <!-- STUDENT DELETION LINK -->
<!--                                                    --><?php
//                                                    if($row['deleted']==1){
//                                                        ?>
<!--                                                        <li>-->
<!--                                                            <a href="#" onclick="reactivate_modal('--><?php //echo base_url();?><!--index.php?admin/student/--><?php //echo $class_id;?><!--/reactivate/--><?php //echo $row['student_id'];?><!--');">-->
<!--                                                                <i class="entypo-trash"></i>-->
<!--                                                                --><?php //echo get_phrase('Reactivate');?>
<!--                                                            </a>-->
<!--                                                        </li>-->
<!--                                                    --><?php
//                                                    }else{
//                                                        ?>
<!--                                                        <li>-->
<!--                                                            <a href="#" onclick="confirm_modal('--><?php //echo base_url();?><!--index.php?admin/student/--><?php //echo $class_id;?><!--/delete/--><?php //echo $row['student_id'];?><!--');">-->
<!--                                                                <i class="entypo-trash"></i>-->
<!--                                                                --><?php //echo get_phrase('delete');?>
<!--                                                            </a>-->
<!--                                                        </li>-->
<!--                                                    --><?php
//                                                    }
//                                                    ?>
<!--                                                </ul>-->
<!--                                            </div>-->
<!---->
<!--                                        </td>-->
<!--                                        </tr>-->
<!--                                    --><?php //}?>
<!---->
<!--                        --><?php //endforeach;?>
<!--                    </tbody>-->
<!--                </table>-->
<!--                    -->
<!--            </div>-->
<!--        --><?php //endforeach;?>
<!--        --><?php //endif;?>

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
						"mColumns": [0, 1, 3, 4, 5, 6]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 1, 3, 4, 5, 6]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(2, false);
							datatable.fnSetColumnVis(7, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(2, true);
									  datatable.fnSetColumnVis(7, true);
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