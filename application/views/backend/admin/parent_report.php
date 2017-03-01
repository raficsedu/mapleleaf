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
    <form action="<?php echo base_url();?>index.php?admin/report/parent_report" method="post">
        <div class="row">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Session');?></label>
            <div class="col-sm-2">
                <select id="session" name="session" class="form-control">
                    <option value="">Select Session</option>
                    <option value="01" <?php if($_POST['session']=='01')echo 'selected';?>><?php echo get_phrase('JAN');?></option>
                    <option value="07" <?php if($_POST['session']=='07')echo 'selected';?>><?php echo get_phrase('JULY');?></option>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('class');?></label>
            <div class="col-sm-2">
                <select name="class_id" class="form-control" data-validate="required" id="class_id"
                        data-message-required="<?php echo get_phrase('value_required');?>"
                        onchange="return get_class_sections(this.value)">
                    <option value=""><?php echo get_phrase('select');?></option>
                    <?php
                    $classes = $this->db->get('class')->result_array();
                    foreach($classes as $row2):
                        ?>
                        <option value="<?php echo $row2['class_id'];?>"
                            <?php if($_POST['class_id'] == $row2['class_id'])echo 'selected';?>>
                            <?php echo $row2['name'];?>
                        </option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('section');?></label>
            <div class="col-sm-2">
                <select name="section_id" class="form-control" id="section_selector_holder">
                    <option value=""><?php echo get_phrase('select_class_first');?></option>
                    <?php
                    $sections = $this->db->get_where('section' , array(
                        'class_id' => $_POST['class_id']
                    ))->result_array();
                    foreach ($sections as $row2) {
                        if($_POST['section_id']==$row2['section_id']){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                        echo '<option value="' . $row2['section_id'] . '" '.$selected.'>' . $row2['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Occupation');?></label>
            <div class="col-sm-2">
                <select name="occupation" class="form-control" id="occupation">
                    <option value=""><?php echo get_phrase('select occupation');?></option>
                    <?php
                    $po = $this->db->get('parent_occupation')->result_array();
                    foreach($po as $p):
                        if($_POST['occupation']==$p['parent_occupation_id']){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                        ?>
                        <option value="<?php echo $p['parent_occupation_id'];?>" <?php echo $selected;?>>
                            <?php echo $p['title'];?>
                        </option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 2%">
            <div class="col-sm-3">
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
                        <th><div><?php echo get_phrase('Father');?></div></th>
                        <th><div><?php echo get_phrase('Mother');?></div></th>
                        <th><div><?php echo get_phrase('Occupation');?></div></th>
                        <th><div><?php echo get_phrase('Phone');?></div></th>
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
                        <td><?php echo $row['father_name'];?></td>
                        <td><?php echo $row['mother_name'];?></td>
                        <td><?php echo $this->crud_model->get_type_name_by_id('parent_occupation',$_POST['occupation'],'title');?></td>
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
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Class - Section wise Students</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 20%;float: left;">
            <h4>Branch : <?php if($_POST['branch_info'])echo $this->crud_model->get_type_name_by_id('branch',$_POST['branch_info'],'name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Building : <?php if($_POST['building_info'])echo $this->crud_model->get_building_name('building',$_POST['building_info'],'name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Class : <?php if($_POST['class_id'])echo $this->crud_model->get_type_name_by_id('class',$_POST['class_id']);?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Section : <?php if($_POST['section_id'])echo $this->crud_model->get_type_name_by_id('section',$_POST['section_id']);?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Year : <?php if($_POST['year'])echo $_POST['year'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Gender : <?php if($_POST['gender'])echo $_POST['gender'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Student Status : <?php if($_POST['student_status']>-1){
                    if($_POST['student_status']=='0')echo 'Non Teacher';
                    else if($_POST['student_status']=='1')echo 'Teacher';
                    else if($_POST['student_status']=='2')echo 'Scholarship';
                    else if($_POST['student_status']=='3')echo 'Teacher + Scholarship';
                    else if($_POST['student_status']=='4')echo 'Special';
                }?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Session : <?php if($_POST['session']){
                    if($_POST['session']=='01')echo 'JAN';
                    else if($_POST['session']=='07')echo 'JUL';
                }?></h4>
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
                        "mColumns": [0, 1,2, 3, 4, 5]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4, 5]
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

        var session = $('#session').val();
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id + '/' + session,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

    }

    jQuery('#session').change(function(){
        var class_id = jQuery('#class_id').val();
        get_class_sections(class_id);
    });

</script>