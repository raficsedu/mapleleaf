<style>
    .print_div{
        width: 10%;
        float: right;
    }
    .print_div a{
        font-size: 20px;
    }
    .report_title{
        text-align: center;
    }
    .calculation{
        width: 30%;
        float: right;
    }
    .calc_title{
        font-weight: bold;
        font-size: 15px;
        width: 50%;
        float: left;
        min-width: 50%;
    }
    .calc_value{
        font-size: 15px;
        width: 50%;
        float: left;
        min-width: 50%;
    }
</style>
<hr />
<div class="row">
    <form action="<?php echo base_url();?>index.php?admin/report/all_student_collection" method="post">
        <div class="row">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Branch');?></label>
            <div class="col-sm-2">
                <select name="branch_info" class="form-control" data-validate="required" id="branch_info">
                    <option value=""><?php echo get_phrase('select branch');?></option>
                    <?php
                    $branch_id = $_SESSION['branch'];
                    $level = $_SESSION['level'];

                    if($level==1){
                        $buildings = $this->db->get('branch')->result_array();
                        foreach($buildings as $building):
                            if($_POST['branch_info']==$building['branch_id']){
                                $selected = 'selected';
                            }else{
                                $selected = '';
                            }
                            ?>
                            <option value="<?php echo $building['branch_id'];?>" <?php echo $selected;?>>
                                <?php echo $building['branch_name'];?>
                            </option>
                        <?php
                        endforeach;
                    }else{
                        $buildings = $this->db->get_where('branch',array('branch_id'=>$branch_id) )->result_array();
                        foreach($buildings as $building):
                            ?>
                            <option value="<?php echo $building['branch_id'];?>">
                                <?php echo $building['branch_name'];?>
                            </option>
                        <?php
                        endforeach;
                    }
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Building');?></label>
            <div class="col-sm-2">
                <select name="building_info" class="form-control" id="building_info">
                    <option value=""><?php echo get_phrase('select building');?></option>
                    <?php
                    $buildings = $this->db->get('building')->result_array();
                    foreach($buildings as $building):
                        if($_POST['building_info']==$building['id']){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                        ?>
                        <option value="<?php echo $building['id'];?>" <?php echo $selected;?>>
                            <?php echo $building['building_name'];?>
                        </option>
                    <?php
                    endforeach;
                    ?>
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
        </div>

        <div class="row" style="margin-top: 2%">

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('gender');?></label>
            <div class="col-sm-2">
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male" <?php if($_POST['gender']=='male')echo 'selected';?>><?php echo get_phrase('male');?></option>
                    <option value="female" <?php if($_POST['gender']=='female')echo 'selected';?>><?php echo get_phrase('female');?></option>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Session');?></label>
            <div class="col-sm-2">
                <select id="session" name="session" class="form-control">
                    <option value="">Select Session</option>
                    <option value="01" <?php if($_POST['session']=='01')echo 'selected';?>><?php echo get_phrase('JAN');?></option>
                    <option value="07" <?php if($_POST['session']=='07')echo 'selected';?>><?php echo get_phrase('JULY');?></option>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Date From');?></label>
            <div class="col-sm-2">
                <input type="text" id="date_from" class="form-control datepicker" name="date_from" value="<?php if(isset($_POST['date_from'])){echo $_POST['date_from'];}else{echo date('d/m/Y');}?>" placeholder="Date From" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-start-view="2">
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Date To');?></label>
            <div class="col-sm-2">
                <input type="text" id="date_to" class="form-control datepicker" name="date_to" value="<?php if(isset($_POST['date_to'])){echo $_POST['date_to'];}else{echo date('d/m/Y');}?>" placeholder="Date To" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-start-view="2">
            </div>
        </div>

        <div class="row" style="margin-top: 2%">

            <div class="col-sm-2">
                <button type="submit" class="btn btn-info"><?php echo get_phrase('Submit');?></button>
            </div>

        </div>
    </form>
</div>
<hr />
<div class="row">
    <div class="col-md-12" id="printableArea">
        <table class="table table-bordered datatable" id="table_export">
            <thead>
            <tr>
                <th><?php echo get_phrase('Student ID');?></th>
                <th><?php echo get_phrase('Student Name');?></th>
                <th><?php echo get_phrase('Class');?></th>
                <th><?php echo get_phrase('Section');?></th>
                <th><?php echo get_phrase('Pay Date');?></th>
                <th><?php echo get_phrase('Receipt No');?></th>
                <th><?php echo get_phrase('Month From');?></th>
                <th><?php echo get_phrase('Month To');?></th>
                <th><?php echo get_phrase('Fee Name');?></th>
                <th><?php echo get_phrase('Amount');?></th>
                <th><?php echo get_phrase('Remark');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_collection = 0;
            $total_amount = 0;
            $total_refund = 0;
            $total_vat = 0;
            $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            foreach($payments as $payment):
                if($payment['payment_type']==1){
                    //getting payment items
                    $payment_id = $payment['payment_id'];
                    $sql = "SELECT * FROM payment_items WHERE payment_id='$payment_id'";
                    $query = $this->db->query($sql);
                    $payment_items = $query->result_array();

                    //Looping the output
                    foreach($payment_items as $item){
                        if($item['item_name']=='Monthly Fee'){
                            $m_from = $months[intval($payment['month_from'])-1];
                            $m_to = $months[intval($payment['month_to'])-1];
                        }else{
                            $m_from = '';
                            $m_to = '';
                        }
                        echo '
                        <tr>
                            <td>'.$payment['roll'].'</td>
                            <td>'.$payment['name'].'</td>
                            <td>'.$this->crud_model->get_type_name_by_id('class',$payment['class_id']).'</td>
                            <td>'.$this->crud_model->get_type_name_by_id('section',$payment['section_id']).'</td>
                            <td>'.date('d/m/Y',strtotime($payment['timestamp'])).'</td>
                            <td>'.$payment['book_no'].'</td>
                            <td>'.$m_from.'</td>
                            <td>'.$m_to.'</td>
                            <td>'.$item['item_name'].'</td>
                            <td>'.$item['item_amount'].'</td>
                            <td>Collected</td>
                        </tr>
                        ';
                    }
                    $total_amount += $payment['total_amount'];
                    $total_collection += $payment['total_receivable'];
                }else if($payment['payment_type']==2){
                    $total_refund = $total_refund + $payment['total_amount'];
                }
            endforeach;
            //Printing bottom Final row
            echo '
                <tr>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue">Total VAT</td>
                    <td style="font-weight:bold;color:blue">'.($total_collection-$total_amount).'</td>
                </tr>
                ';

            echo '
                <tr>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue">Total Refund</td>
                    <td style="font-weight:bold;color:blue">'.$total_refund.'</td>
                </tr>
                ';

            echo '
                <tr>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue">Total Collection</td>
                    <td style="font-weight:bold;color:blue">'.$total_collection.'</td>
                </tr>
                ';
            ?>
            </tbody>
        </table>
    </div>
</div>
<div id="table_header" style="display: none;">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : All Student Collection</h3>
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
            <h4>Date From : <?php if($_POST['date_from'])echo $_POST['date_from'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Date To : <?php if($_POST['date_to'])echo $_POST['date_to'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Gender : <?php if($_POST['gender'])echo $_POST['gender'];?></h4>
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

    jQuery(document).ready(function($)
    {


        var datatable = $("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "bPaginate": false,
            "aaSorting": [],
            "sDom": "<'row'<'col-xs-12 col-right'<'export-data'T>f>r>t<'row'<'col-xs-12 col-left'i>>",
            "oTableTools": {
                "aButtons": [

                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1,2, 3, 4,5,6,7,8,9,10]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4,5,6,7,8,9,10]
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
                    }
                ]
            }

        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });

</script>