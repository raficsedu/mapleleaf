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
    <?php
        if(!isset($_POST['session'])){
            $_POST['session'] = date('Y');
        }
    ?>
    <form action="<?php echo base_url();?>index.php?admin/report/collection_summary" method="post">
        <div class="row">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Branch');?></label>
            <div class="col-sm-2">
                <select name="branch_info" class="form-control" data-validate="required" id="branch_info">
                    <option value=""><?php echo get_phrase('all branch');?></option>
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
                    <option value=""><?php echo get_phrase('all building');?></option>
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
                <th><?php echo get_phrase('Date');?></th>
                <th><?php echo get_phrase('TUI Fee');?></th>
                <th><?php echo get_phrase('EVA Fee');?></th>
                <th><?php echo get_phrase('ADM Fee');?></th>
                <th><?php echo get_phrase('C.Lab');?></th>
                <th><?php echo get_phrase('P.Lab');?></th>
                <th><?php echo get_phrase('RE Adm');?></th>
                <th><?php echo get_phrase('ADM Form');?></th>
                <th><?php echo get_phrase('TC');?></th>
                <th><?php echo get_phrase('Fine');?></th>
                <th><?php echo get_phrase('Total without VAT');?></th>
                <th><?php echo get_phrase('VAT');?></th>
                <th><?php echo get_phrase('Total with VAT');?></th>
                <th><?php echo get_phrase('Total Payment');?></th>
                <th><?php echo get_phrase('Total Refund');?></th>
                <th><?php echo get_phrase('Net Collection');?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $total_tuition_fee = 0;
                $total_evaluation = 0;
                $total_admission = 0;
                $total_clab = 0;
                $total_plab = 0;
                $total_fine = 0;
                $total_re_admission = 0;
                $total_admission_form = 0;
                $total_tc = 0;
                $total_without_vat = 0;
                $total_vat = 0;
                $total_collection = 0;
                $total_payment = 0;
                $total_refund = 0;
                $net_collection = 0;
                $total_net_collection = 0;

                foreach($payments as $payment){
                    $date =  $payment['timestamp'];
                    $branch = $_POST['branch_info'];
                    $building = $_POST['building_info'];
                    $class_id = $_POST['class_id'];
                    $section_id = $_POST['section_id'];
                    $gender = $_POST['gender'];
                    $session = $_POST['session'];
                    $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE payment.timestamp='$date' AND payment.deleted='0'";

                    if($branch!=''){
                        $sql.= " AND payment.branch_id='$branch'";
                    }
                    if($building!=''){
                        $sql.= " AND payment.building_id='$building'";
                    }
                    if($class_id!=''){
                        $sql.= " AND student.class_id='$class_id'";
                    }
                    if($section_id!=''){
                        $sql.= " AND student.section_id='$section_id'";
                    }
                    if($gender!=''){
                        $sql.= " AND student.gender='$gender'";
                    }
                    if($session!=''){
                        $sql.= " AND student.s_session='$session'";
                    }

                    $query = $this->db->query($sql);
                    $sub_payments = $query->result_array();

                    $row_tuition_fee = 0;
                    $row_evaluation = 0;
                    $row_admission = 0;
                    $row_clab = 0;
                    $row_plab = 0;
                    $row_fine = 0;
                    $row_re_admission = 0;
                    $row_admission_form = 0;
                    $row_tc = 0;
                    $row_without_vat = 0;
                    $row_vat = 0;
                    $row_collection = 0;
                    $row_payment = 0;
                    $row_refund = 0;
                    $row_net_collection = 0;

                    foreach($sub_payments as $s_payment){
                        if($s_payment['payment_type']==1){
                            //Payment items
                            $sql = "SELECT * FROM payment_items WHERE payment_id='$s_payment[payment_id]'";
                            $query = $this->db->query($sql);
                            $payment_item = $query->result_array();
                            $admission_fee = 0;
                            $evaluation_fee = 0;
                            $c_lab = 0;
                            $p_lab = 0;
                            $tc = 0;
                            $fine = 0;
                            foreach($payment_item as $item){
                                if($item['form_item_name']=='add_fee_nameadmission_fee'){
                                    $admission_fee = $item['item_amount'];
                                }
                                else if($item['form_item_name']=='add_fee_nameevaluation_fee'){
                                    $evaluation_fee = $item['item_amount'];
                                }
                                else if($item['form_item_name']=='add_fee_namec_lab_fee'){
                                    $c_lab = $item['item_amount'];
                                }
                                else if($item['form_item_name']=='add_fee_namep_lab_fee'){
                                    $p_lab = $item['item_amount'];
                                }
                                else if($item['form_item_name']=='add_fee_nametc'){
                                    $tc = $item['item_amount'];
                                }
                                else if($item['form_item_name']=='fine'){
                                    $fine = $item['item_amount'];
                                }
                            }

                            //Row calculation and merging
                            $row_tuition_fee += $s_payment['monthly_fee'] * (intval($s_payment['month_to']) - intval($s_payment['month_from']) + 1);
                            $row_evaluation += $evaluation_fee;
                            $row_admission += $admission_fee;
                            $row_clab += $c_lab;
                            $row_plab += $p_lab;
                            $row_re_admission += 0;
                            $row_admission_form += 0;
                            $row_tc += $tc;
                            $row_fine += $fine;
                            $row_without_vat += $s_payment['total_receivable'] - $s_payment['vat'];
                            $row_vat += $s_payment['vat'];
                            $row_collection += $s_payment['total_receivable'];
                        }else if($s_payment['payment_type']==2){
                            $row_refund += $s_payment['total_amount'];
                        }else if($s_payment['payment_type']==3){
                            $row_payment += $s_payment['total_amount'];
                        }
                    }
                    $row_net_collection = $row_collection - $row_refund - $row_payment;

                    //Printing table row
                    echo '
                    <tr>
                        <td>'.date('d/m/Y',strtotime($date)).'</td>
                        <td>'.$row_tuition_fee.'</td>
                        <td>'.$row_evaluation.'</td>
                        <td>'.$row_admission.'</td>
                        <td>'.$row_clab.'</td>
                        <td>'.$row_plab.'</td>
                        <td>'.$row_re_admission.'</td>
                        <td>'.$row_admission_form.'</td>
                        <td>'.$row_tc.'</td>
                        <td>'.$row_fine.'</td>
                        <td>'.$row_without_vat.'</td>
                        <td>'.$row_vat.'</td>
                        <td>'.$row_collection.'</td>
                        <td>'.$row_payment.'</td>
                        <td>'.$row_refund.'</td>
                        <td>'.$row_net_collection.'</td>
                    </tr>
                    ';
                    
                    //Calculating every column total
                    $total_tuition_fee += $row_tuition_fee;
                    $total_evaluation += $row_evaluation;
                    $total_admission += $row_admission;
                    $total_clab += $row_clab;
                    $total_plab += $row_plab;
                    $total_re_admission += $row_re_admission;
                    $total_admission_form += $row_admission_form;
                    $total_tc += $row_tc;
                    $total_fine += $row_fine;
                    $total_without_vat += $row_without_vat;
                    $total_vat += $row_vat;
                    $total_collection += $row_collection;
                    $total_payment += $row_payment;
                    $total_refund += $row_refund;
                    $total_net_collection += $row_net_collection;
                }

                //Printing bottom Final row
                echo '
                    <tr>
                        <td style="font-weight:bold;color:blue">Total</td>
                        <td style="font-weight:bold;color:blue">'.$total_tuition_fee.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_evaluation.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_admission.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_clab.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_plab.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_re_admission.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_admission_form.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_tc.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_fine.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_without_vat.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_vat.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_collection.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_payment.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_refund.'</td>
                        <td style="font-weight:bold;color:blue">'.$total_net_collection.'</td>
                    </tr>
                    ';
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="table_header" style="display: none;">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Collection Summary</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 20%;float: left;">
            <h4>Branch : <?php if($_POST['branch_info'])echo $this->crud_model->get_type_name_by_id('branch',$_POST['branch_info'],'branch_name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Building : <?php if($_POST['building_info'])echo $this->crud_model->get_building_name('building',$_POST['building_info'],'building_name');?></h4>
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
                        "mColumns": [0, 1,2, 3, 4, 5, 6, 7,8,9,10,11,12,13,14,15]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4, 5, 6, 7,8,9,10,11,12,13,14,15]
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
            },

        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });

</script>