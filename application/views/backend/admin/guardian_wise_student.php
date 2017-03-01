<style>
    .deleted{
        background-color: #F5A9A9;
    }
    .old{
        background-color: #D8D8D8;
    }
</style>
<hr />
<?php
//print_r($_POST);
?>
<div class="row">
    <form action="<?php echo base_url();?>index.php?admin/report/guardian_wise_student" method="post">
        <div class="form-group">
            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('parent/gurdian');?></label>

            <div class="col-sm-5">
                <select name="parent_id" class="form-control">
                    <option value=""><?php echo get_phrase('select');?></option>
                    <?php
                    $parents = $this->db->get('parent')->result_array();
                    foreach($parents as $row):
                        if($_POST['parent_id']==$row['parent_id']){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                        ?>
                        <option value="<?php echo $row['parent_id'];?>" <?php echo $selected;?>>
                            <?php echo $row['father_name'].'(ID = '.$row['parent_id'].')';?>
                        </option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-sm-4">
                <button type="submit" class="btn btn-info"><?php echo get_phrase('Submit');?></button>
            </div>

        </div>
    </form>
</div>
<hr />
<div class="row">
    <div class="col-md-12">

        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">

                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                    <tr>
                        <th width="70"><div><?php echo get_phrase('SL');?></div></th>
                        <th><div><?php echo get_phrase('Student ID');?></div></th>
                        <th><div><?php echo get_phrase('photo');?></div></th>
                        <th><div><?php echo get_phrase('name');?></div></th>
                        <th><div><?php echo get_phrase('class');?></div></th>
                        <th><div><?php echo get_phrase('section');?></div></th>
                        <th><div><?php echo get_phrase('parent');?></div></th>
                        <th><div><?php echo get_phrase('DOB');?></div></th>
                        <th><div><?php echo get_phrase('Gender');?></div></th>
                        <th><div><?php echo get_phrase('SMS No');?></div></th>
                        <th><div><?php echo get_phrase('phone');?></div></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

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
                        <td><?php echo $this->crud_model->get_type_name_by_id('class',$row['class_id']);?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('section',$row['section_id']);?></td>
                        <td><?php $parent = $this->crud_model->get_parent_info($row['parent_id']);echo $parent[0]['father_name'];?></td>
                        <td><?php echo $row['birthday'];?></td>
                        <td><?php echo $row['gender'];?></td>
                        <td><?php echo $row['sms_number'];?></td>
                        <td><?php echo $row['phone'];?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<div id="table_header" style="display: none;">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Guardian wise Students</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 50%;float: left;">
            <h4>parent/gurdian : <?php if($_POST['parent_id'])echo $this->crud_model->get_type_name_by_id('parent',$_POST['parent_id'],'father_name');?></h4>
        </div>
    </div>
</div>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->
<script type="text/javascript">

    jQuery(document).ready(function($)
    {


        var datatable = $("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "aaSorting": [],
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
            "oTableTools": {
                "aButtons": [

                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1, 3, 4, 5, 6, 7, 8, 9,10]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1, 3, 4, 5, 6, 7, 8, 9,10]
                    },
                    {
                        "sExtends": "print",
                        "fnSetText"	   : "Press 'esc' to return",
                        "sMessage": $('#table_header').html(),
                        "fnClick": function (nButton, oConfig) {
                            //datatable.fnSetColumnVis(2, false);
                            //datatable.fnSetColumnVis(5, false);

                            this.fnPrint( true, oConfig );

                            window.print();

                            $(window).keyup(function(e) {
                                if (e.which == 27) {
                                    //datatable.fnSetColumnVis(2, true);
                                    //datatable.fnSetColumnVis(5, true);
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