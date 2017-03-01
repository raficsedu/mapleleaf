<?php
$payment = $this->db->get_where('payment', array('payment_id' => $param2))->row();
$payment_items = $this->db->get_where('payment_items', array('payment_id' => $param2))->result_array();
$student = $this->db->get_where('student', array('student_id' => $payment->student_id))->row();
$parent = $this->db->get_where('parent', array('parent_id' => $student->parent_id))->row();
$class = $this->db->get_where('class', array('class_id' => $student->class_id))->row();
$section = $this->db->get_where('section', array('section_id' => $student->section_id))->row();
?>

<style>
    .cp-studying th {
        text-align: center !important;
        font-size: 20px;
    }

    .right {
        float: right !important;
        width: 50% !important;
        margin-top: 10%;
    }
    .print_div{
        width: 100%;
        float: left;
    }
    .print_div a{
        font-size: 20px;
        float: right;
    }
    #printme , #DivIdToPrint{
        display: none;
    }
    @media print {
        .page-container{
            display: none;
        }
        #printme {
            display: block;
        }
        h4{
            padding: 0px;
            margin: 3px 0px;
        }
    }
</style>
<div class="row">
<div class="col-md-12">

<!------CONTROL TABS START------>
<ul class="nav nav-tabs bordered">
    <li class="active">
        <a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_invoice/payment'); ?>
        </a>
    </li>
</ul>
<!------CONTROL TABS END------>
<div class="tab-content">
<div class="print_div btn btn-inverse btn-small">
    <a onclick="printDiv('DivIdToPrint')" href="javascript:void(0)"><i class="entypo-print"></i> Print</a>
</div>
<!----CREATION FORM STARTS---->
<div class="tab-pane box active" id="add" style="padding: 5px">
<?php echo form_open(base_url() . 'index.php?admin/invoice/do_update/' . $param2, array('class' => 'form-horizontal form-groups-bordered validate', 'target' => '_top')); ?>
<input type="hidden" name="vat" id="vat"
       value="<?php echo $this->db->get_where('settings', array('type' => 'vat'))->row()->description; ?>">
<input type="hidden" name="h_adjustment_due" id="h_adjustment_due" value="<?php echo $payment->adjustment_due; ?>">
<input type="hidden" name="h_adjustment_to" id="h_adjustment_to" value="<?php echo $payment->adjustment_to; ?>">
<input type="hidden" name="student_real_id" id="student_real_id" value="<?php echo $student->student_id; ?>">
<input type="hidden" name="number_of_additional_fee" id="number_of_additional_fee">
<input type="hidden" name="real_monthly_fee" id="real_monthly_fee" value="<?php echo $payment->monthly_fee; ?>">

<div class="row">
<div class="col-md-6">
    <div class="panel panel-default panel-shadow" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?php echo get_phrase('Student Information'); ?></div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Student ID'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_id" class="form-control" name="student_id" data-validate="required"
                           value="<?php echo $student->roll; ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('class'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_class" class="form-control" name="student_class"
                           data-validate="required" value="<?php echo $class->name; ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_section" class="form-control" name="student_section"
                           data-validate="required" value="<?php echo $section->name; ?>" disabled/>
                </div>
            </div>
            <!--                                <div class="form-group">-->
            <!--                                    <label for="field-2" class="col-sm-3 control-label">-->
            <?php //echo get_phrase('class');?><!--</label>-->
            <!---->
            <!--                                    <div class="col-sm-5">-->
            <!--                                        <select name="class_id" class="form-control" data-validate="required" id="class_id"-->
            <!--                                                data-message-required="-->
            <?php //echo get_phrase('value_required');?><!--"-->
            <!--                                                onchange="return get_class_sections(this.value)">-->
            <!--                                            <option value="">-->
            <?php //echo get_phrase('select');?><!--</option>-->
            <!--                                            --><?php
            //                                            $classes = $this->db->get('class')->result_array();
            //                                            foreach($classes as $row):
            //                                                $info = $row['monthly_fee'].','.$row['admission_fee'].','.$row['evaluation_fee'];
            //
            ?>
            <!--                                                <option class="--><?php //echo $info;?><!--" value="-->
            <?php //echo $row['class_id'];?><!--">-->
            <!--                                                    --><?php //echo $row['name'];?>
            <!--                                                </option>-->
            <!--                                            --><?php
            //                                            endforeach;
            //
            ?>
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!---->
            <!--                                <div class="form-group">-->
            <!--                                    <label for="field-2" class="col-sm-3 control-label">-->
            <?php //echo get_phrase('section');?><!--</label>-->
            <!--                                    <div class="col-sm-5">-->
            <!--                                        <select name="section_id" class="form-control" id="section_selector_holder">-->
            <!--                                            <option value="">-->
            <?php //echo get_phrase('select_class_first');?><!--</option>-->
            <!---->
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->

            <!--                                <div class="form-group">-->
            <!--                                    <label class="col-sm-3 control-label">-->
            <?php //echo get_phrase('student');?><!--</label>-->
            <!--                                    <div class="col-sm-9">-->
            <!--                                        <select name="student_id" class="form-control" style="" id="student_selection">-->
            <!--                                            --><?php //
            //                                            $this->db->order_by('class_id','asc');
            //                                            $students = $this->db->get('student')->result_array();
            //                                            foreach($students as $row):
            //
            ?>
            <!--                                                <option value="-->
            <?php //echo $row['student_id'];?><!--">-->
            <!--                                                    class -->
            <?php //echo $this->crud_model->get_class_name($row['class_id']);?><!-- --->
            <!--                                                    roll --><?php //echo $row['roll'];?><!-- --->
            <!--                                                    --><?php //echo $row['name'];?>
            <!--                                                </option>-->
            <!--                                            --><?php
            //                                            endforeach;
            //
            ?>
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Student Name'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="student_name" class="form-control" name="student_name"
                           value="<?php echo $student->name; ?>" disabled/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Payment Year'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_payment_year" class="form-control" name="student_payment_year"
                           value="<?php echo $student->year; ?>" disabled/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default panel-shadow" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?php echo get_phrase('invoice_information'); ?></div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('date'); ?></label>

                <div class="col-sm-9">
                    <input type="text" class="datepicker form-control" name="date"
                           value="<?php echo getBDdatetime($payment->timestamp);?>" disabled/ >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Book No'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="book_no" class="form-control" name="book_no"
                           value="<?php echo $payment->book_no; ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Parent Name'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="parent_name" class="form-control" name="parent_name"
                           value="<?php echo $parent->father_name; ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Building Name'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="building_name" class="form-control" name="building_name"
                           value="<?php echo $payment->building_name; ?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Branch Name'); ?></label>

                <div class="col-sm-9">
                    <select id="branch_name" name="branch_name" class="form-control" disabled>
                        <?php
                        $branch_id = $_SESSION['branch'];
                        $level = $_SESSION['level'];
                        if ($level == 1) {
                            $buildings = $this->db->get('branch')->result_array();
                            foreach ($buildings as $building):
                                ?>
                                <option
                                    value="<?php echo $building['branch_id']; ?>" <?php if ($building['branch_id'] == $payment->branch_id) echo 'selected' ?>>
                                    <?php echo $building['branch_name']; ?>
                                </option>
                            <?php
                            endforeach;
                        } else {
                            $buildings = $this->db->get('branch')->result_array();
                            foreach ($buildings as $building):
                                if ($branch_id == $building['branch_id']) {
                                    ?>
                                    <option value="<?php echo $building['branch_id']; ?>">
                                        <?php echo $building['branch_name']; ?>
                                    </option>
                                <?php
                                }
                            endforeach;
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-11">
<div class="panel panel-default panel-shadow" data-collapsed="0">
<div class="panel-heading">
    <div class="panel-title"><?php echo get_phrase('payment_informations'); ?></div>
</div>
<div class="panel-body">
<div id="s_payment_info">

</div>
<table class="cp-studying" style="width:100%" border="1">
    <thead>
    <tr>
        <th>Fee Name</th>
        <th>Month From</th>
        <th>Month To</th>
        <th>Amount</th>
        <th>Waiver (%)</th>
        <th>Comment</th>
    </tr>
    </thead>
    <tbody id="payment_table">
    <?php
    $sl = 100;
    //Checking for the refund
    $payment_type = $payment->payment_type;
    if ($payment_type == 2) {
        echo '<tr id="refund">
                                <td><input class="form-control h_input" name="refund" id="refund" value="Refund" disabled type="text"></td>
                                <td></td>
                                <td></td>
                                <td><input class="form-control h_input amount" name="refund_amount" id="refund_amount" value="' . $payment->total_amount . '" disabled="" type="text"></td>
                                <td></td>
                                <td><input class="form-control h_input" name="refund_comment" id="refund_comment" type="text" value="' . $payment->comment . '" disabled></td>
                          </tr>';
    }
    foreach ($payment_items as $item) {
        if ($sl == 100) {
            ?>
            <tr>
                <?php
                $this_month = date('m');
                ?>
                <td><input type="text" class="form-control h_input" name="<?php echo $item['form_item_name'] ?>"
                           id="<?php echo $item['form_item_name'] ?>" value="<?php echo $item['item_name'] ?>" autofocus
                           disabled></td>
                <?php
                $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
                if($student->s_session=='01'){
                    ?>
                    <td>
                        <select id="month_from" name="month_from" class="form-control" disabled>
                            <?php
                            for($i=intval($payment->month_from);$i<=12;$i++){
                                $j = ($i<10) ? '0'.$i : $i;
                                $s = ($payment->month_from==$j) ? 'selected' : '';
                                echo '<option value="'.$j.'" '.$s.'>'.$months[$i-1].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="month_to" name="month_to" class="form-control" disabled>
                            <?php
                            for($i=intval($payment->month_from);$i<=12;$i++){
                                $j = ($i<10) ? '0'.$i : $i;
                                $s = ($payment->month_to==$j) ? 'selected' : '';
                                echo '<option value="'.$j.'" '.$s.'>'.$months[$i-1].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                <?php
                }else if($student->s_session=='07'){
                    ?>
                    <td>
                        <select id="month_from" name="month_from" class="form-control" disabled>
                            <?php
                            for($i=intval($payment->month_from);$i<=18;$i++){
                                $j = ($i<10) ? '0'.$i : $i;
                                $m = $i%12;
                                $m = ($m==0) ? 12 : $m;
                                $s = ($payment->month_from==$j) ? 'selected' : '';
                                echo '<option value="'.$j.'" '.$s.'>'.$months[$m-1].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="month_to" name="month_to" class="form-control" disabled>
                            <?php
                            for($i=intval($payment->month_from);$i<=18;$i++){
                                $j = ($i<10) ? '0'.$i : $i;
                                $m = $i%12;
                                $m = ($m==0) ? 12 : $m;
                                $s = ($payment->month_to==$j) ? 'selected' : '';
                                echo '<option value="'.$j.'" '.$s.'>'.$months[$m-1].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                <?php
                }
                ?>
                <td><input type="text" class="form-control h_input amount"
                           name="<?php echo $item['form_amount_name'] ?>" id="<?php echo $item['form_amount_name'] ?>"
                           value="<?php echo $item['item_amount'] ?>" autofocus disabled></td>
                <td><input type="text" class="form-control h_input waiver fee_total"
                           name="<?php echo $item['form_waiver_name'] ?>" id="<?php echo $item['form_waiver_name'] ?>"
                           value="<?php echo $item['waiver_amount'] ?>" base="<?php if ($item['waiver_amount'] != '') {
                        $base = $item['item_amount'] * 100 / $item['waiver_amount'];
                        echo $base;
                    } ?>" disabled></td>
                <td><input type="text" class="form-control h_input" name="<?php echo $item['form_comment_name'] ?>"
                           id="<?php echo $item['form_comment_name'] ?>" value="<?php echo $item['comment'] ?>"
                           disabled></td>
            </tr>
        <?php
        } else {
            ?>
            <tr id="<?php echo $sl; ?>">
                <td><a href="javascript:void(0)" class="close <?php echo $sl; ?>" style="width: 15%;float: left"><img
                            src="<?php echo base_url(); ?>assets/images/close.png"></a><input
                        style="width: 85%;float: left" type="text" class="form-control h_input"
                        name="<?php echo $item['form_item_name'] ?>" id="<?php echo $item['form_item_name'] ?>"
                        value="<?php echo $item['item_name'] ?>" autofocus disabled></td>
                <?php
                if ($item['month_from'] != '') {
                    ?>
                    <td>
                        <select id="remission_month_from" name="remission_month_from" class="form-control" disabled>
                            <option value="01" <?php if ($item['month_from'] == '01') echo 'selected' ?>>January
                            </option>
                            <option value="02" <?php if ($item['month_from'] == '02') echo 'selected' ?>>February
                            </option>
                            <option value="03" <?php if ($item['month_from'] == '03') echo 'selected' ?>>March</option>
                            <option value="04" <?php if ($item['month_from'] == '04') echo 'selected' ?>>April</option>
                            <option value="05" <?php if ($item['month_from'] == '05') echo 'selected' ?>>May</option>
                            <option value="06" <?php if ($item['month_from'] == '06') echo 'selected' ?>>June</option>
                            <option value="07" <?php if ($item['month_from'] == '07') echo 'selected' ?>>July</option>
                            <option value="08" <?php if ($item['month_from'] == '08') echo 'selected' ?>>August</option>
                            <option value="09" <?php if ($item['month_from'] == '09') echo 'selected' ?>>September
                            </option>
                            <option value="10" <?php if ($item['month_from'] == '10') echo 'selected' ?>>October
                            </option>
                            <option value="11" <?php if ($item['month_from'] == '11') echo 'selected' ?>>November
                            </option>
                            <option value="12" <?php if ($item['month_from'] == '12') echo 'selected' ?>>December
                            </option>
                        </select>
                    </td>
                    <td>
                        <select id="remission_month_to" name="remission_month_to" class="form-control" disabled>
                            <option value="01" <?php if ($item['month_to'] == '01') echo 'selected' ?>>January</option>
                            <option value="02" <?php if ($item['month_to'] == '02') echo 'selected' ?>>February</option>
                            <option value="03" <?php if ($item['month_to'] == '03') echo 'selected' ?>>March</option>
                            <option value="04" <?php if ($item['month_to'] == '04') echo 'selected' ?>>April</option>
                            <option value="05" <?php if ($item['month_to'] == '05') echo 'selected' ?>>May</option>
                            <option value="06" <?php if ($item['month_to'] == '06') echo 'selected' ?>>June</option>
                            <option value="07" <?php if ($item['month_to'] == '07') echo 'selected' ?>>July</option>
                            <option value="08" <?php if ($item['month_to'] == '08') echo 'selected' ?>>August</option>
                            <option value="09" <?php if ($item['month_to'] == '09') echo 'selected' ?>>September
                            </option>
                            <option value="10" <?php if ($item['month_to'] == '10') echo 'selected' ?>>October</option>
                            <option value="11" <?php if ($item['month_to'] == '11') echo 'selected' ?>>November</option>
                            <option value="12" <?php if ($item['month_to'] == '12') echo 'selected' ?>>December</option>
                        </select>
                    </td>
                <?php
                } else {
                    echo '<td></td>
                            <td></td>';
                }
                ?>
                <td><input type="text" class="form-control h_input amount"
                           name="<?php echo $item['form_amount_name'] ?>" id="<?php echo $item['form_amount_name'] ?>"
                           value="<?php echo $item['item_amount'] ?>" autofocus disabled></td>
                <td><input type="text" class="form-control h_input waiver <?php echo $item['form_amount_name']; ?>"
                           name="<?php echo $item['form_waiver_name'] ?>" id="<?php echo $item['form_waiver_name'] ?>"
                           value="<?php echo $item['waiver_amount'] ?>"
                           base="<?php $base = $item['item_amount'] * 100 / $item['waiver_amount'];
                           echo $base; ?>" disabled></td>
                <td><input type="text" class="form-control h_input" name="<?php echo $item['form_comment_name'] ?>"
                           id="<?php echo $item['form_comment_name'] ?>" value="<?php echo $item['comment'] ?>"
                           disabled></td>
            </tr>
        <?php
        }
        $sl--;
    }
    ?>
    </tbody>
</table>
<div class="form-group" style="margin-top: 3%;float: left;width: 50%;">
    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Additional Fee'); ?></label>

    <div class="col-sm-7">
        <select name="additional_fee" class="form-control" id="additional_fee" disabled>
            <option value=""><?php echo get_phrase('select'); ?></option>
            <?php
            $fees = $this->db->get('fees')->result_array();
            foreach ($fees as $row):
                $info = $row['fee_name'] . ',' . $row['fee_amount'];
                ?>
                <option class="<?php echo $info; ?>" value="<?php echo $row['id']; ?>">
                    <?php echo $row['fee_name']; ?>
                </option>
            <?php
            endforeach;
            //adding admission fee and evaluation fee
            $class_admission = 'Admission Fee,' . $class->admission_fee;
            $class_evaluation = 'Evaluation Fee,' . $class->evaluation_fee;
            $class_remission = 'Remission On Fee,' . $class->monthly_fee;
            echo '<option class="' . $class_admission . '" value="admission_fee">Admission Fee</option>';
            echo '<option class="' . $class_evaluation . '" value="evaluation_fee">Evaluation Fee</option>';
            echo '<option class="' . $class_remission . '" value="remission">Remission on Fee</option>';
            ?>
        </select>
    </div>
</div>
<div class="right">
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Adjustment Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_adjustment_amount" class="form-control" name="invoice_adjustment_amount"
                   value="<?php echo $payment->adjustment_amount; ?>" disabled/>
        </div>
    </div>
    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Adjustment to'); ?></label>

        <div class="col-sm-5">
            <select name="adjustment_to" class="form-control" id="adjustment_to" disabled>
                <option value="0"><?php echo get_phrase('select'); ?></option>
                <option value="1" <?php if ($payment->adjustment_to == 1) echo 'selected'; ?>>Monthly Fee</option>
                <option value="2" <?php if ($payment->adjustment_to == 2) echo 'selected'; ?>>Admission Fee</option>
                <option value="3" <?php if ($payment->adjustment_to == 3) echo 'selected'; ?>>Evaluation Fee</option>
                <option value="5" <?php if ($payment->adjustment_to == 5) echo 'selected'; ?>>Refund</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Total Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_total_amount" class="form-control" name="invoice_total_amount"
                   value="<?php echo $payment->total_amount; ?>" disabled/>
        </div>
    </div>
    <div class="form-group">
        <?php $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description; ?>
        <label class="col-sm-3 control-label"><?php echo get_phrase('VAT ') . ' (' . $vat . '%)'; ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_vat" class="form-control" name="invoice_vat"
                   value="<?php echo $payment->vat; ?>" disabled/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Total Receivable'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_total_receivable" class="form-control" name="invoice_total_receivable"
                   value="<?php echo $payment->total_receivable; ?>" disabled/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Received Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_received_amount" class="form-control" name="invoice_received_amount"
                   value="<?php echo $payment->received_amount; ?>" data-validate="required" disabled/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Return Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_return_amount" class="form-control" name="invoice_return_amount"
                   value="<?php echo $payment->returned_amount; ?>" data-validate="required" disabled/>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="site_url" style="display: none"><?php echo base_url(); ?></div>
</div>
<?php echo form_close(); ?>
</div>
<!----CREATION FORM ENDS-->

</div>
</div>
</div>

<div id="DivIdToPrint">
    <div style="width: 100%;float: left;">
        <div style="width: 100%;float: left;">
            <div style="width: 20%;float: left;margin-top: 1%;">
                <img src="<?php echo base_url().'assets/images/logo.png'?>" width="80px" height="80px">
                <h4 style="text-align: left;margin-top: 5%;">Voucher No : <?php echo $payment->book_no;?></h4>
            </div>
            <div style="width: 60%;float: left;">
                <h3 style="text-align: center;">MAPLE LEAF INTERNATIONAL SCHOOL</h3>
                <h4 style="text-align: center;">DHANMONDI , DHAKA</h4>
                <h6 style="text-align: center;">Central VAT Registered No : 19151048296</h6>
                <h6 style="text-align: center;">Area Code : 190403</h6>
            </div>
            <div style="width: 20%;float: left;margin-top: 7.5%;">
                <h4 style="text-align: right;">Session : <?php if($student->s_session=='01'){echo 'January';}else{echo 'July';}?></h4>
                <h4 style="text-align: right;">Class : <?php echo $class->name; ?></h4>
                <h4 style="text-align: right;">Section : <?php echo $section->name; ?></h4>
            </div>
        </div>
        <hr>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 100%;float: left;">
                <h4>Student Name : <?php echo $student->name.' ('.$student->roll.')';?></h4>
            </div>
            <?php
            $m_from = intval($payment->month_from);
            $m_from = ($m_from%12==0)? 12 : $m_from%12;
            $m_to = intval($payment->month_to);
            $m_to = ($m_to%12==0)? 12 : $m_to%12;
            ?>
            <h4>Tuition Fees for the month of (<?php echo $months[$m_from-1].' To '.$months[$m_to-1];?>)</h4>
            <table style="width: 50%;float: right;">
                <?php
                foreach ($payment_items as $item) {
                    echo '<tr>
                        <td width="80%"><h4>'.$item['item_name'].'</h4></td>
                        <td width="20%"><h4>'.$item['item_amount'].'</h4></td>
                    </tr>';
                }
                echo '<tr>
                        <td width="80%"><h4>VAT ('.$vat.'%)</h4></td>
                        <td width="20%"><h4>'.$payment->vat.'</h4></td>
                    </tr>';
                echo '<tr>
                        <td width="80%"><h4>TOTAL</h4></td>
                        <td width="20%"><h4>'.$payment->total_receivable.'</h4></td>
                    </tr>';
                ?>
            </table>
        </div>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 30%;float: left;">
                <h4> Date : <?php echo getBDdatetime($payment->timestamp)?></h4>
            </div>
            <div style="width: 70%;float: left;">
                <h4> Collecting Officer : <?php echo $this->crud_model->get_type_name_by_id('admin',$payment->collector_id);?></h4>
            </div>
        </div>
    </div>
    <div style="width: 100%;float: left;margin-top: 6%;">
        <div style="width: 100%;float: left;">
            <div style="width: 20%;float: left;margin-top: 1%;">
                <img src="<?php echo base_url().'assets/images/logo.png'?>" width="80px" height="80px">
                <h4 style="text-align: left;margin-top: 5%;">Voucher No : <?php echo $payment->book_no;?></h4>
            </div>
            <div style="width: 60%;float: left;">
                <h3 style="text-align: center;">MAPLE LEAF INTERNATIONAL SCHOOL</h3>
                <h4 style="text-align: center;">DHANMONDI , DHAKA</h4>
                <h6 style="text-align: center;">Central VAT Registered No : 19151048296</h6>
                <h6 style="text-align: center;">Area Code : 190403</h6>
            </div>
            <div style="width: 20%;float: left;margin-top: 7.5%;">
                <h4 style="text-align: right;">Session : <?php if($student->s_session=='01'){echo 'January';}else{echo 'July';}?></h4>
                <h4 style="text-align: right;">Class : <?php echo $class->name; ?></h4>
                <h4 style="text-align: right;">Section : <?php echo $section->name; ?></h4>
            </div>
        </div>
        <hr>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 100%;float: left;">
                <h4>Student Name : <?php echo $student->name.' ('.$student->roll.')';?></h4>
            </div>
            <?php
            $m_from = intval($payment->month_from);
            $m_from = ($m_from%12==0)? 12 : $m_from%12;
            $m_to = intval($payment->month_to);
            $m_to = ($m_to%12==0)? 12 : $m_to%12;
            ?>
            <h4>Tuition Fees for the month of (<?php echo $months[$m_from-1].' To '.$months[$m_to-1];?>)</h4>
            <table style="width: 50%;float: right;">
                <?php
                foreach ($payment_items as $item) {
                    echo '<tr>
                        <td width="80%"><h4>'.$item['item_name'].'</h4></td>
                        <td width="20%"><h4>'.$item['item_amount'].'</h4></td>
                    </tr>';
                }
                echo '<tr>
                        <td width="80%"><h4>VAT ('.$vat.'%)</h4></td>
                        <td width="20%"><h4>'.$payment->vat.'</h4></td>
                    </tr>';
                echo '<tr>
                        <td width="80%"><h4>TOTAL</h4></td>
                        <td width="20%"><h4>'.$payment->total_receivable.'</h4></td>
                    </tr>';
                ?>
            </table>
        </div>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 30%;float: left;">
                <h4> Date : <?php echo getBDdatetime($payment->timestamp)?></h4>
            </div>
            <div style="width: 70%;float: left;">
                <h4> Collecting Officer : <?php echo $this->crud_model->get_type_name_by_id('admin',$payment->collector_id);?></h4>
            </div>
        </div>
    </div>
    <div style="width: 100%;float: left;margin-top: 6%;">
        <div style="width: 100%;float: left;">
            <div style="width: 20%;float: left;margin-top: 1%;">
                <img src="<?php echo base_url().'assets/images/logo.png'?>" width="80px" height="80px">
                <h4 style="text-align: left;margin-top: 5%;">Voucher No : <?php echo $payment->book_no;?></h4>
            </div>
            <div style="width: 60%;float: left;">
                <h3 style="text-align: center;">MAPLE LEAF INTERNATIONAL SCHOOL</h3>
                <h4 style="text-align: center;">DHANMONDI , DHAKA</h4>
                <h6 style="text-align: center;">Central VAT Registered No : 19151048296</h6>
                <h6 style="text-align: center;">Area Code : 190403</h6>
            </div>
            <div style="width: 20%;float: left;margin-top: 7.5%;">
                <h4 style="text-align: right;">Session : <?php if($student->s_session=='01'){echo 'January';}else{echo 'July';}?></h4>
                <h4 style="text-align: right;">Class : <?php echo $class->name; ?></h4>
                <h4 style="text-align: right;">Section : <?php echo $section->name; ?></h4>
            </div>
        </div>
        <hr>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 100%;float: left;">
                <h4>Student Name : <?php echo $student->name.' ('.$student->roll.')';?></h4>
            </div>
            <?php
            $m_from = intval($payment->month_from);
            $m_from = ($m_from%12==0)? 12 : $m_from%12;
            $m_to = intval($payment->month_to);
            $m_to = ($m_to%12==0)? 12 : $m_to%12;
            ?>
            <h4>Tuition Fees for the month of (<?php echo $months[$m_from-1].' To '.$months[$m_to-1];?>)</h4>
            <table style="width: 50%;float: right;">
                <?php
                foreach ($payment_items as $item) {
                    echo '<tr>
                        <td width="80%"><h4>'.$item['item_name'].'</h4></td>
                        <td width="20%"><h4>'.$item['item_amount'].'</h4></td>
                    </tr>';
                }
                echo '<tr>
                        <td width="80%"><h4>VAT ('.$vat.'%)</h4></td>
                        <td width="20%"><h4>'.$payment->vat.'</h4></td>
                    </tr>';
                echo '<tr>
                        <td width="80%"><h4>TOTAL</h4></td>
                        <td width="20%"><h4>'.$payment->total_receivable.'</h4></td>
                    </tr>';
                ?>
            </table>
        </div>
        <div style="width: 100%;float: left;margin-top: 2%;">
            <div style="width: 30%;float: left;">
                <h4> Date : <?php echo getBDdatetime($payment->timestamp)?></h4>
            </div>
            <div style="width: 70%;float: left;">
                <h4> Collecting Officer : <?php echo $this->crud_model->get_type_name_by_id('admin',$payment->collector_id);?></h4>
            </div>
        </div>
    </div>
</div>
<?php
function getBDdatetime($date=''){
    $temp = explode('-',$date);
    $new_date = $temp[2].'/'.$temp[1].'/'.$temp[0];
    return $new_date;
}
?>

<script type="text/javascript">

    function printDiv(print) {
        var print_content = $('#'+print).html();
        $("#printme").html(print_content);
        window.print();
        $("#printme").html("");
    }

</script>