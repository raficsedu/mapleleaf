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
</style>
<div class="row">
<div class="col-md-12">

<!------CONTROL TABS START------>
<ul class="nav nav-tabs bordered">
    <li class="active">
        <a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_invoice/payment'); ?>
        </a></li>
</ul>
<!------CONTROL TABS END------>
<div class="tab-content">
<!----CREATION FORM STARTS---->
<div class="tab-pane box active" id="add" style="padding: 5px">
<?php //echo form_open(base_url() . 'index.php?admin/invoice/do_update/' . $param2, array('class' => 'form-horizontal form-groups-bordered validate', 'target' => '_top')); ?>
<form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>index.php?admin/invoice/do_update/<?php echo $param2;?>" autocomplete="off" method="post">
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
                           value="<?php echo $student->roll; ?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('class'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_class" class="form-control" name="student_class"
                           data-validate="required" value="<?php echo $class->name; ?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_section" class="form-control" name="student_section"
                           data-validate="required" value="<?php echo $section->name; ?>" readonly/>
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
                           value="<?php echo $student->name; ?>" readonly/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Payment Year'); ?></label>

                <div class="col-sm-6">
                    <input type="text" id="student_payment_year" class="form-control" name="student_payment_year"
                           value="<?php echo $student->year; ?>" readonly/>
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
                           value="<?php $t = explode('-', $payment->timestamp);
                           $date = $t[2] . '/' . $t[1] . '/' . $t[0];
                           echo $date; ?>"/ >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Book No'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="book_no" class="form-control" name="book_no"
                           value="<?php echo $payment->book_no; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Parent Name'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="parent_name" class="form-control" name="parent_name"
                           value="<?php echo $parent->father_name; ?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Building Name'); ?></label>

                <div class="col-sm-9">
                    <input type="text" id="building_name" class="form-control" name="building_name"
                           value="<?php echo $payment->building_name; ?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Branch Name'); ?></label>

                <div class="col-sm-9">
                    <select id="branch_name" name="branch_name" class="form-control">
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
                                <td><input class="form-control h_input" name="refund" id="refund" value="Refund" readonly="" type="text"></td>
                                <td></td>
                                <td></td>
                                <td><input class="form-control h_input amount" name="refund_amount" id="refund_amount" value="' . $payment->total_amount . '" type="text"></td>
                                <td></td>
                                <td><input class="form-control h_input" name="refund_comment" id="refund_comment" type="text" value="' . $payment->comment . '"></td>
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
                           id="<?php echo $item['form_item_name'] ?>" value="<?php echo $item['item_name'] ?>"
                           readonly></td>
                <?php
                $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
                if($student->s_session=='01'){
                ?>
                    <td>
                        <select id="month_from" name="month_from" class="form-control">
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
                        <select id="month_to" name="month_to" class="form-control">
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
                        <select id="month_from" name="month_from" class="form-control">
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
                        <select id="month_to" name="month_to" class="form-control">
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
                <td>
                    <input type="text" class="form-control h_input amount"
                           name="<?php echo $item['form_amount_name'] ?>" id="<?php echo $item['form_amount_name'] ?>"
                           value="<?php echo $item['item_amount'] ?>" base="<?php echo $item['base'] ?>"
                           readonly>
                    <input type="hidden" name="<?php echo $item['form_amount_name'] . '_base'; ?>"
                           id="<?php echo $item['form_amount_name'] . '_base'; ?>" value="<?php echo $item['base'] ?>">
                </td>
                <td><input type="text" class="form-control h_input waiver fee_total"
                           name="<?php echo $item['form_waiver_name'] ?>" id="<?php echo $item['form_waiver_name'] ?>"
                           value="<?php echo $item['waiver_amount'] ?>" base="<?php if ($item['waiver_amount'] != '') {
                        $base = $item['item_amount'] * 100 / $item['waiver_amount'];
                        echo $base;
                    } ?>"></td>
                <td><input type="text" class="form-control h_input" name="<?php echo $item['form_comment_name'] ?>"
                           id="<?php echo $item['form_comment_name'] ?>" value="<?php echo $item['comment'] ?>"></td>
            </tr>
        <?php
        } else {
            ?>
            <tr id="<?php echo $sl; ?>">
                <td><a href="javascript:void(0)" class="close <?php echo $sl; ?>" style="width: 15%;float: left"><img
                            src="<?php echo base_url(); ?>assets/images/close.png"></a><input
                        style="width: 85%;float: left" type="text" class="form-control h_input"
                        name="<?php echo $item['form_item_name'] ?>" id="<?php echo $item['form_item_name'] ?>"
                        value="<?php echo $item['item_name'] ?>" readonly></td>
                <?php
                if ($item['month_from'] != '') {
                    ?>
                    <td>
                        <select id="remission_month_from" name="remission_month_from" class="form-control">
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
                        <select id="remission_month_to" name="remission_month_to" class="form-control">
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
                <td>
                    <input type="text" class="form-control h_input amount"
                           name="<?php echo $item['form_amount_name'] ?>" id="<?php echo $item['form_amount_name'] ?>"
                           value="<?php echo $item['item_amount'] ?>" base="<?php echo $item['base'] ?>"
                           readonly>
                    <input type="hidden" name="<?php echo $item['form_amount_name'] . '_base'; ?>"
                           id="<?php echo $item['form_amount_name'] . '_base'; ?>" value="<?php echo $item['base'] ?>">
                </td>
                <td><input type="text" class="form-control h_input waiver <?php echo $item['form_amount_name']; ?>"
                           name="<?php echo $item['form_waiver_name'] ?>" id="<?php echo $item['form_waiver_name'] ?>"
                           value="<?php echo $item['waiver_amount'] ?>"
                           base="<?php $base = $item['item_amount'] * 100 / $item['waiver_amount'];
                           echo $base; ?>"></td>
                <td><input type="text" class="form-control h_input" name="<?php echo $item['form_comment_name'] ?>"
                           id="<?php echo $item['form_comment_name'] ?>" value="<?php echo $item['comment'] ?>"></td>
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
        <select name="additional_fee" class="form-control" id="additional_fee">
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
                   value=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Adjustment to'); ?></label>

        <div class="col-sm-5">
            <select name="adjustment_to" class="form-control" id="adjustment_to">
                <option value="0"><?php echo get_phrase('select'); ?></option>
                <?php
                $fees = $this->db->get('fees')->result_array();
                foreach ($fees as $row):
                    $info = 'add_fee_name' . $row['id'] . ',' . 'add_fee_total' . $row['id'];
                    ?>
                    <option class="<?php echo $info; ?>" value="<?php echo $row['id']; ?>">
                        <?php echo $row['fee_name']; ?>
                    </option>
                <?php
                endforeach;
                ?>
                <option id="amf" class="monthly_fee,fee_total" value="50">Monthly Fee</option>
                <option id="aaf" class="add_fee_nameadmission_fee,add_fee_totaladmission_fee" value="51">Admission Fee
                </option>
                <option id="aef" class="add_fee_nameevaluation_fee,add_fee_totalevaluation_fee" value="52">Evaluation
                    Fee
                </option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Total Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_total_amount" class="form-control" name="invoice_total_amount"
                   value="<?php echo $payment->total_amount; ?>" readonly/>
        </div>
    </div>
    <div class="form-group">
        <?php $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description; ?>
        <label class="col-sm-3 control-label"><?php echo get_phrase('VAT ') . ' (' . $vat . '%)'; ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_vat" class="form-control" name="invoice_vat"
                   value="<?php echo $payment->vat; ?>" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Total Receivable'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_total_receivable" class="form-control" name="invoice_total_receivable"
                   value="<?php echo $payment->total_receivable; ?>" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Received Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_received_amount" class="form-control" name="invoice_received_amount"
                   value="<?php echo $payment->received_amount; ?>" data-validate="required"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?php echo get_phrase('Return Amount'); ?></label>

        <div class="col-sm-9">
            <input type="text" id="invoice_return_amount" class="form-control" name="invoice_return_amount"
                   value="<?php echo $payment->returned_amount; ?>" data-validate="required" readonly/>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="form-group">
    <div class="col-sm-5">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_invoice'); ?></button>
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

<script type="text/javascript">

var base_monthly_fee = 0;
var first_row = '';

//Find student click or enter press function call
$('#find_student').click(find_student);
$(document).keypress(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        var chk = $('#student_name').val();
        if (!chk) {
            find_student();
        }
        //find_student();
    }
});

//Prevent form submit
$("form").submit(function (e) {
    //e.preventDefault();
    var invoice_total_receivable = $('#invoice_total_receivable').val();
    var invoice_received_amount = $('#invoice_received_amount').val();
    //alert(invoice_received_amount);
    if (invoice_received_amount < invoice_total_receivable) {
        e.preventDefault();
        alert('Received Amount Must be Greater Than Total Receivable');
    }
});

//Additional fee management here
$(document).delegate('#additional_fee', 'change', function () {
    var v = $(this).val();
    if (v == "remission") {
        var c = $('#additional_fee option:selected').attr('class');
        c = c.split(",");
        var add_fee_name = c[0];
        var add_fee_amount = c[1];
        var url = $('.site_url').html();
        url = url + 'assets/images/close.png';

        var html = '<tr id="' + v + '"><td><button class="close ' + v + '" style="width: 15%;float: left"><img src="' + url + '"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="add_fee_name' + v + '" id="add_fee_name' + v + '" value="' + add_fee_name + '" readonly></td><td><select id="remission_month_from" name="remission_month_from" class="form-control"><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select></td><td><select id="remission_month_to" name="remission_month_to" class="form-control"><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select></td><td><input type="text" class="form-control h_input minus_amount" name="add_fee_total' + v + '" id="add_fee_total' + v + '" value="' + add_fee_amount + '" readonly></td></tr>';
        $('#payment_table').append(html);

        //Final amount calculation
        final_calculation();

        //Adding additional fee number
        var afn = $('#number_of_additional_fee').val();
        afn = parseInt(afn) + 1;
        $('#number_of_additional_fee').val(afn);
    } else {
        var c = $('#additional_fee option:selected').attr('class');
        c = c.split(",");
        var add_fee_name = c[0];
        var add_fee_amount = c[1];
        var url = $('.site_url').html();
        url = url + 'assets/images/close.png';

        var html = '<tr id="' + v + '"><td><button class="close ' + v + '" style="width: 15%;float: left"><img src="' + url + '"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="add_fee_name' + v + '" id="add_fee_name' + v + '" value="' + add_fee_name + '" readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="add_fee_total' + v + '" id="add_fee_total' + v + '" base="' + add_fee_amount + '" value="' + add_fee_amount + '" readonly><input type="hidden" name="add_fee_total' + v + '_base" id="add_fee_total' + v + '_base" value="' + add_fee_amount + '"></td><td><input type="text" class="form-control h_input waiver add_fee_total' + v + '" name="add_fee_name' + v + '_waiver" id="add_fee_name' + v + '_waiver" value="" base=""></td><td><input type="text" class="form-control h_input" name="add_fee_name' + v + '_comment" id="add_fee_name' + v + '_comment"></td></tr>';
        $('#payment_table').append(html);

        //Final amount calculation
        final_calculation();

        //Adding additional fee number
        var afn = $('#number_of_additional_fee').val();
        afn = parseInt(afn) + 1;
        $('#number_of_additional_fee').val(afn);
    }
});


//Close button click event handle here
$(document).delegate('.close', 'click', function () {
    var clss = $(this).attr('class');
    clss = clss.split(" ");
    clss = clss[1];
    $('#' + clss).remove();

    //Final amount calculation
    final_calculation();
});

$(function () {
    //Final amount calculation
    //final_calculation();

    //$('.datepicker').datepicker('option', 'dateFormat', 'd/m/Y');
    var dateFormat = $(".selector").datepicker("option", "dateFormat");

    first_row = $('#tuition_fee').html();
});

//Monthly Fee month change action
var old_to = $('#month_to').val();
var old_total = $('#real_monthly_fee').val();
var old_fine = $('#fine_amount').val();

$(document).delegate('#month_to', 'change', function () {
    var from = $('#month_from').val();
    var to = $('#month_to').val();
    if (from > to) {
        alert('To month can not be smaller than From month');
        $('#month_to').val(old_to);
    } else {
        //Calling function for the fine
        check_for_fine(from, to);

        old_to = to;
        var diff = Math.abs(parseInt(from) - parseInt(to)) + parseInt(1);
        var total = parseFloat(old_total);
        total = total * diff;

        $('#fee_waiver').val('');
        $('#fee_waiver').attr('base', '');
        $('#fee_total').val(total);
        $('#fee_total_base').val(total);
        $('#fee_total').attr('base', total);
        //$('#fine_amount').val(fine);

        //Final amount calculation
        final_calculation();

        $('#invoice_received_amount').val('');
        $('#invoice_return_amount').val('');
    }
});


var old_from = $('#month_from').val();
$(document).delegate('#month_from', 'change', function () {
    var from = $('#month_from').val();
    var to = $('#month_to').val();
    if (from > to) {
        alert('From month can not be greater than To month');
        $('#month_from').val(old_from);
    } else {
        //Calling ajax for the fine
        check_for_fine(from, to);

        old_from = from;
        var diff = Math.abs(parseInt(from) - parseInt(to)) + parseInt(1);
        var total = parseFloat(old_total);
        total = total * diff;

        $('#fee_waiver').val('');
        $('#fee_waiver').attr('base', '');
        $('#fee_total').val(total);
        $('#fee_total_base').val(total);
        $('#fee_total').attr('base', total);
        //$('#fine_amount').val(fine);

        //Final amount calculation
        final_calculation();

        $('#invoice_received_amount').val('');
        $('#invoice_return_amount').val('');
    }
});

$(document).delegate('#invoice_received_amount', 'keyup', function () {
    var ra = $(this).val();
    if (ra != "") {
        var ta = $('#invoice_total_receivable').val();
        var rta = ra - ta;
        $('#invoice_return_amount').val(rta);
    } else {
        $('#invoice_return_amount').val('');
    }
});

//Adjustment Handling Here
$(document).delegate('#adjustment_to', 'change', function () {
    var aa = $('#invoice_adjustment_amount').val();
    if (aa != "") {
        var at = $('#adjustment_to').val();
        if (at == "refund") {
            $("#payment_table").empty();

            //Adding Refund field
            var url = $('.site_url').html();
            url = url + 'assets/images/close.png';
            var html = '<tr id="refund"><td><button class="close refund" style="width: 15%;float: left"><img src="' + url + '"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="refund" id="refund" value="Refund" readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="refund_amount" id="refund_amount" value="' + aa + '" readonly></td><td></td><td><input type="text" class="form-control h_input" name="refund_comment" id="refund_comment"></td></tr>';
            $('#payment_table').append(html);

            //Final amount calculation
            final_calculation();

            //Adjustment of others field
            $('#invoice_vat').val(0);
            $('#invoice_total_receivable').val(0);
            $('#invoice_received_amount').val(0);
            $('#invoice_return_amount').val(0);
        } else {
            var clls = $('#adjustment_to option:selected').attr('class');
            if (clls) {
                clls = clls.split(',');
                var field_name = clls[0];
                var field_total_name = clls[1];
                var field_text = $('#adjustment_to option:selected').text();
                field_text = field_text.trim();

                var field_previous_total = $('#' + field_total_name).attr('base');
                if(field_previous_total < aa){
                    alert('Adjustment Amount Can Not Be Greater');
                }else{
                    var diff = field_previous_total - aa;
                    $('#'+field_total_name).val(aa);

                    var adue = $('#h_adjustment_due').val();
                    var ato = $('#h_adjustment_to').val();

                    if(ato==""){
                        ato =  field_name + ':' + field_text + ':' + field_total_name;
                    }else{
                        var splited_ato = ato.split(",");
                        for(var i=0;i<splited_ato.length;i++){
                            var n = splited_ato[i].indexOf(field_name);
                            if(n>-1){
                                break;
                            }
                        }
                        if(i==splited_ato.length){
                            ato = ato + ',' + field_name + ':' + field_text + ':' + field_total_name;
                        }
                    }

                    if(adue==""){
                        adue = diff;
                    }else{
                        if(i<splited_ato.length){
                            var splited_adue = adue.split(",");
                            var adue = "";
                            for(var j=0;j<splited_adue.length;j++){
                                if(i==j){
                                    splited_adue[j] = diff;
                                }
                                if(j==0){
                                    adue = splited_adue[j];
                                }else{
                                    adue = adue + ',' + splited_adue[j];
                                }
                            }
                        }else{
                            adue = adue + ',' + diff;
                        }
                    }

                    $('#h_adjustment_due').val(adue);
                    $('#h_adjustment_to').val(ato);

                    //Final amount calculation
                    final_calculation();
                }
            }
        }

        /*var at = $('#adjustment_to').val();
         if(at==1){
         $('#fee_total').val(aa);
         var diff = a_monthly_fee - aa;
         $('#h_adjustment_due').val(diff);
         $('#h_adjustment_to').val(1);

         //Final amount calculation
         final_calculation();
         }else if(at==2){
         $('#add_fee_totaladmission_fee').val(aa);
         var diff = a_admission_fee - aa;
         $('#h_adjustment_due').val(diff);
         $('#h_adjustment_to').val(2);

         //Final amount calculation
         final_calculation();
         }else if(at==3){
         $('#add_fee_totalevaluation_fee').val(aa);
         var diff = a_evaluation_fee - aa;
         $('#h_adjustment_due').val(diff);
         $('#h_adjustment_to').val(3);

         //Final amount calculation
         final_calculation();
         }else if(at==4){
         $('#fee_total').val(aa);
         var diff = a_monthly_fee - aa;
         $('#h_adjustment_due').val(diff);
         $('#h_adjustment_to').val(4);
         $('#fine').remove();

         //Final amount calculation
         final_calculation();

         //Adjustment of others field
         $('#invoice_vat').val(0);
         $('#invoice_total_receivable').val(0);
         $('#invoice_received_amount').val(0);
         $('#invoice_return_amount').val(0);
         }
         else if(at==5){
         var diff = 0;
         $('#h_adjustment_due').val(diff);
         $('#h_adjustment_to').val(5);
         $('#tuition_fee').remove();
         $('#fine').remove();

         //Adding Refund field
         var url = $('.site_url').html();
         url = url + 'assets/images/close.png';
         var html = '<tr id="refund"><td><button class="close refund" style="width: 15%;float: left"><img src="'+url+'"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="refund" id="refund" value="Refund" readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="refund_amount" id="refund_amount" value="'+aa+'" readonly></td><td></td><td><input type="text" class="form-control h_input" name="refund_comment" id="refund_comment"></td></tr>';
         $('#payment_table').append(html);

         //Final amount calculation
         final_calculation();

         //Adjustment of others field
         $('#invoice_vat').val(0);
         $('#invoice_total_receivable').val(0);
         $('#invoice_received_amount').val(0);
         $('#invoice_return_amount').val(0);
         }
         else{
         $('#fee_total').val(a_monthly_fee);
         $('#add_fee_totaladmission_fee').val(a_admission_fee);
         $('#add_fee_totalevaluation_fee').val(a_evaluation_fee);
         $('#invoice_adjustment_amount').val('');

         $('#h_adjustment_due').val('');
         $('#h_adjustment_to').val('');

         //Final amount calculation
         final_calculation();
         }*/

        $('#invoice_received_amount').val('');
        $('#invoice_return_amount').val('');
    }
});

//Remission month change action
//    var remission_old_to = $('#remission_month_to').val();
//
//    $(document).delegate('#remission_month_to','change',function(){
//        var from = $('#remission_month_from').val();
//        var to = $('#remission_month_to').val();
//        if(from>to){
//            alert('To month can not be smaller than From month');
//            $('#remission_month_to').val(remission_old_to);
//        }else{
//            remission_old_to = to;
//            var diff = Math.abs(parseInt(from)-parseInt(to)) + parseInt(1);
//            total = base_monthly_fee * diff;
//            $('#add_fee_totalremission').val(total);
//
//            //Final amount calculation
//            calculate_total_amount();
//            calculate_total_vat();
//            calculate_total_receivable();
//        }
//    });
//
//
//    var remission_old_from = $('#remission_month_from').val();
//    $(document).delegate('#remission_month_from','change',function(){
//        var from = $('#remission_month_from').val();
//        var to = $('#remission_month_to').val();
//        if(from>to){
//            alert('From month can not be greater than To month');
//            $('#remission_month_from').val(remission_old_from);
//        }else{
//            remission_old_from = from;
//            var diff = Math.abs(parseInt(from)-parseInt(to)) + parseInt(1);
//            total = base_monthly_fee * diff;
//            $('#add_fee_totalremission').val(total);
//
//            //Final amount calculation
//            calculate_total_amount();
//            calculate_total_vat();
//            calculate_total_receivable();
//        }
//    });

//Waiver change action
$(document).delegate('.waiver', 'keyup', function () {
    var wa = $(this).val();
    var c = $(this).attr('class');
    c = c.split(" ");
    var id = c[3];
    var base = $(this).attr('base');
    if (base == "") {
        base = $('#' + id).val();
        $(this).attr('base', base);
    }

    wa = parseFloat(wa);
    base = parseFloat(base);
    var new_total = base - (base * wa / 100);
    if (wa) {
//            if((new_total % 1) > 0.49){
//                new_total = Math.ceil(new_total);
//            }else{
//                new_total = Math.floor(new_total);
//            }
        $('#' + id).val(new_total);
        $('#' + id + '_base').val(new_total);
        $('#' + id).attr('base', new_total);
    } else {
        $('#' + id).val(base);
        $('#' + id + '_base').val(base);
        $('#' + id).attr('base', base);
    }

    //Final amount calculation
    final_calculation();

    $('#invoice_received_amount').val('');
    $('#invoice_return_amount').val('');
});

//Waiver change action
$(document).delegate('#refund_amount', 'keyup', function () {
    var re = $(this).val();

    $('#invoice_adjustment_amount').val('');
    $('#invoice_total_amount').val(re);
    $('#invoice_vat').val(0);
    $('#invoice_total_receivable').val(0);
    $('#invoice_received_amount').val(0);
    $('#invoice_return_amount').val(0);
});

function check_for_fine(from, to) {
    var id = $('#student_real_id').val();
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_fine_status/' + from + '/' + to + '/' + id,
        success: function (response) {
            var info = response;
            if (info == 0) {
                $('#fine').remove();
            } else {
                $('#fine').remove();
                var url = $('.site_url').html();
                url = url + 'assets/images/close.png';
                var html = '<tr id="fine"><td><button class="close fine" style="width: 15%;float: left"><img src="' + url + '"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="fine" id="fine" value="Fine" readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="fine_amount" id="fine_amount" value="' + info + '" readonly></td></tr>';
                $('#payment_table').append(html);

                //Final amount calculation
                final_calculation();
            }
        }
    });
}

function find_student() {
    var chk = $('#student_name').val();
    if (chk) {
        return;
    }
    var s_id = $('#student_id').val().trim();
    s_id = s_id.replace("/", "-");
    s_id = s_id.replace("/", "-");
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_students_by_id/' + s_id,
        success: function (response) {
            if (response == "") {
                alert('You are not allowed to access this Student');
            } else {
                var info = response.split("~");
                var name = info[0];
                var father = info[1];
                var cls = info[2];
                var monthly_fee = info[3];
                base_monthly_fee = info[3];
                var admission_fee = info[4];
                var evaluation_fee = info[5];
                var section = info[6];
                var parent_status = info[7];
                var waiver = info[8];
                var building_name = info[9];
                var year = info[10];

                $('#student_name').val(name);
                $('#parent_name').val(father);
                $('#student_class').val(cls);
                $('#building_name').val(building_name);
                $('#student_payment_year').val(year);

                //Checking for the waiver
                if (parent_status == 1 || parent_status == 2 || parent_status == 3 || parent_status == 4) {
                    monthly_fee = monthly_fee * (waiver / 100);
                    //                        if((monthly_fee % 1) > 0.49){
                    //                            monthly_fee = Math.ceil(monthly_fee);
                    //                        }else{
                    //                            monthly_fee = Math.floor(monthly_fee);
                    //                        }
                    jQuery('#fee_total').val(monthly_fee);
                    jQuery('#fee_total').attr('base', monthly_fee);
                } else {
                    jQuery('#fee_total').val(monthly_fee);
                    jQuery('#fee_total').attr('base', monthly_fee);
                }
                jQuery('#remission_total').val(base_monthly_fee);
                jQuery('#real_monthly_fee').val(monthly_fee);
                jQuery('#student_section').val(section);

                //Handling fees
                $("#af").remove();
                $("#ef").remove();
                $("#rmsn").remove();
                var fee = '';
                fee += '<option id="af" class="Admission Fee,' + admission_fee + '" value="admission_fee">Admission Fee</option>';
                fee += '<option id="ef" class="Evaluation Fee,' + evaluation_fee + '" value="evaluation_fee">Evaluation Fee</option>';
                fee += '<option id="rmsn" class="Remission On Fee,' + 0 + '" value="remission">Remission on Fee</option>';
                $('#additional_fee').append(fee);

                //Handling Adjustment
                $("#amf").remove();
                $("#aaf").remove();
                $("#aef").remove();
                var adj = '';
                adj += '<option id="amf" class="monthly_fee,fee_total" value="50">Monthly Fee</option>';
                adj += '<option id="aaf" class="add_fee_nameadmission_fee,add_fee_totaladmission_fee" value="51">Admission Fee</option>';
                adj += '<option id="aef" class="add_fee_nameevaluation_fee,add_fee_totalevaluation_fee" value="52">Evaluation Fee</option>';
                $('#adjustment_to').append(adj);

                //Final amount calculation
                final_calculation();

                old_total = monthly_fee;

                //Get student payment info
                $.ajax({
                    url: '<?php echo base_url();?>index.php?admin/get_students_payment_stauts_by_id/' + s_id,
                    success: function (response) {
                        var info = response.split("~");
                        console.log(info);
                        $('#student_real_id').val(info[0]);
                        $('#s_payment_info').html(info[1]);
                        if (info[2]) {
                            $('#month_from').html(info[2]);
                            $('#month_to').html(info[2]);
                        } else {
                            $('#month_from').html(info[2]);
                            $('#month_to').html(info[2]);
                            $('#fee_total').val('');
                        }
                        if (info[3]) {
                            $('#payment_table').append(info[3]);
                        }

                        if (info[4] > 0) {
                            //                    var url = $('.site_url').html();
                            //                    url = url + 'assets/images/close.png';
                            //                    var html = '<tr id="fine"><td><button class="close fine" style="width: 15%;float: left"><img src="'+url+'"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="fine" id="fine" value="Fine" autofocus readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="fine_amount" id="fine_amount" value="'+info[4]+'" autofocus readonly></td></tr>';
                            //                    $('#payment_table').append(html);
                        } else {
                            $('#fine').remove();
                        }

                        if (info[5]) {
                            $('#payment_table').append(info[5]);
                        }

                        var from = $('#month_from').val();
                        var to = $('#month_to').val();
                        check_for_fine(from, to);


                        //Final amount calculation
                        final_calculation();
                    }
                });
            }
        }
    });
}

function final_calculation() {
    //Final amount calculation
    calculate_total_amount();
    calculate_total_vat();
    calculate_total_receivable();
}

function calculate_total_amount() {
    var total = 0;
    $('.amount').each(function () {
        var s_total = $(this).val();
        total = total + parseFloat(s_total);
    });
    $('.minus_amount').each(function () {
        var s_total = $(this).val();
        total = total - parseFloat(s_total);
    });
//        if((total % 1) > 0.49){
//            total = Math.ceil(total);
//        }else{
//            total = Math.floor(total);
//        }
    $('#invoice_total_amount').val(total);
}

function calculate_total_vat() {
    var invoice_total_amount = $('#invoice_total_amount').val();
    var vat = $('#vat').val();
    var total_vat = invoice_total_amount * (vat / 100);
    //total_vat = Math.ceil(total_vat);
//        if((total_vat % 1) > 0.49){
//            total_vat = Math.ceil(total_vat);
//        }else{
//            total_vat = Math.floor(total_vat);
//        }
    $('#invoice_vat').val(total_vat);
}

function calculate_total_receivable() {
    var invoice_total_amount = $('#invoice_total_amount').val();
    var invoice_vat = $('#invoice_vat').val();
    var total_receivable = parseFloat(invoice_total_amount) + parseFloat(invoice_vat);
    if ((total_receivable % 1) > 0.49) {
        total_receivable = Math.ceil(total_receivable);
    } else {
        total_receivable = Math.floor(total_receivable);
    }
    $('#invoice_total_receivable').val(total_receivable);
}

$(document).delegate("#refresh", 'click', function () {
    //alert(first_row);
    $("#payment_table").empty();

    $('#student_id').val('');
    $('#student_class').val('');
    $('#student_section').val('');
    $('#student_name').val('');
    $('#student_payment_year').val('');
    $('#book_no').val('');
    $('#parent_name').val('');
    $('#building_name').val('');
    $('#s_payment_info').html('');

    $('#invoice_adjustment_amount').val('');
    $('#invoice_total_amount').val('');
    $('#invoice_vat').val('');
    $('#invoice_total_receivable').val('');
    $('#invoice_received_amount').val('');
    $('#invoice_return_amount').val('');

    var new_html = '<tr id="tuition_fee">';
    new_html += first_row;
    new_html += '</tr>';
    $("#payment_table").append(new_html);
});

</script>