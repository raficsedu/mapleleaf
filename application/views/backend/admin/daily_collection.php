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
    <form id="filter_form" action="<?php echo base_url();?>index.php?admin/report/daily_collection" method="post">
        <div class="row">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Branch');?></label>
            <div class="col-sm-2">
                <select name="branch_info" class="form-control" data-validate="required" id="branch_info"
                        data-message-required="<?php echo get_phrase('value_required');?>">
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
                    }
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Building');?></label>
            <div class="col-sm-2">
                <select name="building_info" class="form-control" id="building_info">
                    <option value=""><?php echo get_phrase('all building');?></option>
                    <?php
                    if($level==1){
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
                    }
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Date');?></label>
            <div class="col-sm-2">
                <input type="text" id="date" class="form-control datepicker" name="date" value="<?php if(isset($_POST['date'])){echo $_POST['date'];}else{echo date('d/m/Y');}?>" placeholder="Date" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-start-view="2">
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
        </div>
        <div class="row" style="margin-top: 2%">
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
                <th><?php echo get_phrase('Branch');?></th>
                <th><?php echo get_phrase('Student ID');?></th>
                <th><?php echo get_phrase('Name');?></th>
                <th><?php echo get_phrase('class');?></th>
                <th><?php echo get_phrase('section');?></th>
                <th><?php echo get_phrase('JAN');?></th>
                <th><?php echo get_phrase('FEB');?></th>
                <th><?php echo get_phrase('MAR');?></th>
                <th><?php echo get_phrase('APR');?></th>
                <th><?php echo get_phrase('MAY');?></th>
                <th><?php echo get_phrase('JUN');?></th>
                <th><?php echo get_phrase('JUL');?></th>
                <th><?php echo get_phrase('AUG');?></th>
                <th><?php echo get_phrase('SEP');?></th>
                <th><?php echo get_phrase('OCT');?></th>
                <th><?php echo get_phrase('NOV');?></th>
                <th><?php echo get_phrase('DEC');?></th>
                <th><?php echo get_phrase('EV');?></th>
                <th><?php echo get_phrase('ADM');?></th>
                <th><?php echo get_phrase('C.LAB');?></th>
                <th><?php echo get_phrase('P.LAB');?></th>
                <th><?php echo get_phrase('TC');?></th>
                <th><?php echo get_phrase('Fine');?></th>
                <th><?php echo get_phrase('VAT');?></th>
                <th><?php echo get_phrase('TOTAL');?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $sl = 0;
                $total_collection = 0;
                $total_payment = ($total_paid>0) ?  $total_paid : 0;
                $total_refund = 0;
                foreach($payments as $payment):
                    if($payment['payment_type']==1){
                        $sl++;
                        echo '<tr>';
                        echo '<td>'.$this->crud_model->get_type_name_by_id('branch',$payment['branch_id'],'branch_name').'</td>';
                        echo '<td>'.$payment['roll'].'</td>';
                        echo '<td>'.$payment['name'].'</td>';
                        echo '<td>'.$this->crud_model->get_type_name_by_id('class',$payment['class_id']).'</td>';
                        echo '<td>'.$this->crud_model->get_type_name_by_id('section',$payment['section_id']).'</td>';
                        //Monthly Fee Print Out
                        $monthly_fee = $payment['monthly_fee'];
                        if($payment['monthly_fee_given'] < $payment['monthly_fee']){
                            $monthly_fee = $payment['monthly_fee_given'];
                        }

                        for($i=1;$i<=12;$i++){
                            $month[$i] = '';
                        }
                        $from = intval($payment['month_from']);
                        $to = intval($payment['month_to']);
                        for($i=$from;$i<=$to;$i++){
                            $j = $i%12;
                            if($j==0)
                                $j = 12;
                            $month[$j] = $monthly_fee;
                        }
                        echo '<td>'.$month[1].'</td>';
                        echo '<td>'.$month[2].'</td>';
                        echo '<td>'.$month[3].'</td>';
                        echo '<td>'.$month[4].'</td>';
                        echo '<td>'.$month[5].'</td>';
                        echo '<td>'.$month[6].'</td>';
                        echo '<td>'.$month[7].'</td>';
                        echo '<td>'.$month[8].'</td>';
                        echo '<td>'.$month[9].'</td>';
                        echo '<td>'.$month[10].'</td>';
                        echo '<td>'.$month[11].'</td>';
                        echo '<td>'.$month[12].'</td>';

                        //Printing payment items
                        $sql = "SELECT * FROM payment_items WHERE payment_id='$payment[payment_id]'";
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
                        echo '<td>'.$evaluation_fee.'</td>';
                        echo '<td>'.$admission_fee.'</td>';
                        echo '<td>'.$c_lab.'</td>';
                        echo '<td>'.$p_lab.'</td>';
                        echo '<td>'.$tc.'</td>';
                        echo '<td>'.$fine.'</td>';
                        //Printing out VAT and TOTAL
                        echo '<td>'.$payment['vat'].'</td>';
                        echo '<td>'.$payment['total_receivable'].'</td>';
                        echo '</tr>';

                        //Calculations
                        $total_collection+= $payment['total_receivable'];
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
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
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
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue">Total Payment</td>
                        <td style="font-weight:bold;color:blue">'.$total_payment.'</td>
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
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
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
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue"></td>
                        <td style="font-weight:bold;color:blue">Total Balance</td>
                        <td style="font-weight:bold;color:blue">'.($total_collection-$total_payment-$total_refund).'</td>
                    </tr>
                    ';
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="table_header" style="display: none;">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Daily Collection</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 20%;float: left;">
            <h4>Branch : <?php if(isset($_POST['branch_info']))echo $this->crud_model->get_type_name_by_id('branch',$_POST['branch_info'],'branch_name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Building : <?php if(isset($_POST['building_info']))echo $this->crud_model->get_building_name('building',$_POST['building_info'],'building_name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Class : <?php if(isset($_POST['class_id']))echo $this->crud_model->get_type_name_by_id('class',$_POST['class_id']);?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Section : <?php if(isset($_POST['section_id']))echo $this->crud_model->get_type_name_by_id('section',$_POST['section_id']);?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Date : <?php if(isset($_POST['date']))echo $_POST['date'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Gender : <?php if(isset($_POST['gender']))echo $_POST['gender'];?></h4>
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
            <h4>Session : <?php if(isset($_POST['session'])){
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
                        "mColumns": [0, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24]
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