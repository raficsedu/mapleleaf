<?php 
$payment =	$this->db->get_where('payment' , array('payment_id' => $param2) )->row();
$payment_items = $this->db->get_where('payment_items' , array('payment_id' => $param2) )->result_array();
$student = $this->db->get_where('student' , array('student_id' => $payment->student_id) )->row();
$parent = $this->db->get_where('parent' , array('parent_id' => $student->parent_id) )->row();
$class = $this->db->get_where('class' , array('class_id' => $student->class_id) )->row();
$section = $this->db->get_where('section' , array('section_id' => $student->section_id) )->row();
?>

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
</style>
<div class="row">
<div class="col-md-12">

<!------CONTROL TABS START------>
<ul class="nav nav-tabs bordered">
    <li class="active">
        <a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
            <?php echo get_phrase('edit_invoice/payment');?>
        </a></li>
</ul>
<!------CONTROL TABS END------>
<div class="tab-content">
<!----CREATION FORM STARTS---->
<div class="tab-pane box active" id="add" style="padding: 5px">
<?php echo form_open(base_url() . 'index.php?admin/invoice/do_update' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
<input type="hidden" name="vat" id="vat" value="<?php echo $this->db->get_where('settings', array('type' => 'vat'))->row()->description;?>">
<input type="hidden" name="h_adjustment_due" id="h_adjustment_due">
<input type="hidden" name="h_adjustment_to" id="h_adjustment_to">
<input type="hidden" name="student_real_id" id="student_real_id">
<input type="hidden" name="number_of_additional_fee" id="number_of_additional_fee">
<input type="hidden" name="real_monthly_fee" id="real_monthly_fee">
<div class="row">
<div class="col-md-6">
    <div class="panel panel-default panel-shadow" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?php echo get_phrase('Student Information');?></div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Student ID');?></label>
                <div class="col-sm-6">
                    <input type="text" id="student_id" class="form-control" name="student_id" data-validate="required" value="<?php echo $student->roll;?>" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                <div class="col-sm-6">
                    <input type="text" id="student_class" class="form-control" name="student_class" data-validate="required" value="<?php echo $class->name;?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
                <div class="col-sm-6">
                    <input type="text" id="student_section" class="form-control" name="student_section" data-validate="required" value="<?php echo $section->name;?>" disabled/>
                </div>
            </div>
            <!--                                <div class="form-group">-->
            <!--                                    <label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('class');?><!--</label>-->
            <!---->
            <!--                                    <div class="col-sm-5">-->
            <!--                                        <select name="class_id" class="form-control" data-validate="required" id="class_id"-->
            <!--                                                data-message-required="--><?php //echo get_phrase('value_required');?><!--"-->
            <!--                                                onchange="return get_class_sections(this.value)">-->
            <!--                                            <option value="">--><?php //echo get_phrase('select');?><!--</option>-->
            <!--                                            --><?php
            //                                            $classes = $this->db->get('class')->result_array();
            //                                            foreach($classes as $row):
            //                                                $info = $row['monthly_fee'].','.$row['admission_fee'].','.$row['evaluation_fee'];
            //                                                ?>
            <!--                                                <option class="--><?php //echo $info;?><!--" value="--><?php //echo $row['class_id'];?><!--">-->
            <!--                                                    --><?php //echo $row['name'];?>
            <!--                                                </option>-->
            <!--                                            --><?php
            //                                            endforeach;
            //                                            ?>
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!---->
            <!--                                <div class="form-group">-->
            <!--                                    <label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('section');?><!--</label>-->
            <!--                                    <div class="col-sm-5">-->
            <!--                                        <select name="section_id" class="form-control" id="section_selector_holder">-->
            <!--                                            <option value="">--><?php //echo get_phrase('select_class_first');?><!--</option>-->
            <!---->
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->

            <!--                                <div class="form-group">-->
            <!--                                    <label class="col-sm-3 control-label">--><?php //echo get_phrase('student');?><!--</label>-->
            <!--                                    <div class="col-sm-9">-->
            <!--                                        <select name="student_id" class="form-control" style="" id="student_selection">-->
            <!--                                            --><?php //
            //                                            $this->db->order_by('class_id','asc');
            //                                            $students = $this->db->get('student')->result_array();
            //                                            foreach($students as $row):
            //                                            ?>
            <!--                                                <option value="--><?php //echo $row['student_id'];?><!--">-->
            <!--                                                    class --><?php //echo $this->crud_model->get_class_name($row['class_id']);?><!-- --->
            <!--                                                    roll --><?php //echo $row['roll'];?><!-- --->
            <!--                                                    --><?php //echo $row['name'];?>
            <!--                                                </option>-->
            <!--                                            --><?php
            //                                            endforeach;
            //                                            ?>
            <!--                                        </select>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Student Name');?></label>
                <div class="col-sm-9">
                    <input type="text" id="student_name" class="form-control" name="student_name" value="<?php echo $student->name;?>" disabled/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default panel-shadow" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?php echo get_phrase('invoice_information');?></div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                <div class="col-sm-9">
                    <input type="text" class="datepicker form-control" name="date" value="<?php echo $payment->timestamp;?>"/ >
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Book No');?></label>
                <div class="col-sm-9">
                    <input type="text" id="book_no" class="form-control" name="book_no" value="<?php echo $payment->book_no;?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Parent Name');?></label>
                <div class="col-sm-9">
                    <input type="text" id="parent_name" class="form-control" name="parent_name" value="<?php echo $parent->father_name;?>" disabled/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('Branch Name');?></label>
                <div class="col-sm-9">
                    <select id="branch_name" name="branch_name" class="form-control">
                        <?php
                        $buildings = $this->db->get('building')->result_array();
                        foreach($buildings as $building):
                            ?>
                            <option value="<?php echo $building['id'];?>" <?php if($building['id']==$payment->branch_id)echo 'selected'?>>
                                <?php echo $building['building_name'];?>
                            </option>
                        <?php
                        endforeach;
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
            <div class="panel-title"><?php echo get_phrase('payment_informations');?></div>
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
                </tr>
                </thead>
                <tbody id="payment_table_edit">
                <?php
                $sl = 1;
                foreach($payment_items as $item){
                    if($sl==1){
                    ?>
                        <tr>
                            <?php
                            $this_month = date('m');
                            ?>
                            <td><input type="text" class="form-control h_input" name="<?php echo $item['form_item_name']?>" id="<?php echo $item['form_item_name']?>" value="<?php echo $item['item_name']?>" autofocus readonly></td>
                            <td>
                                <select id="month_from" name="month_from" class="form-control">
                                    <option value="01" <?php if($payment->month_from=='01')echo 'selected'?>>January</option>
                                    <option value="02" <?php if($payment->month_from=='02')echo 'selected'?>>February</option>
                                    <option value="03" <?php if($payment->month_from=='03')echo 'selected'?>>March</option>
                                    <option value="04" <?php if($payment->month_from=='04')echo 'selected'?>>April</option>
                                    <option value="05" <?php if($payment->month_from=='05')echo 'selected'?>>May</option>
                                    <option value="06" <?php if($payment->month_from=='06')echo 'selected'?>>June</option>
                                    <option value="07" <?php if($payment->month_from=='07')echo 'selected'?>>July</option>
                                    <option value="08" <?php if($payment->month_from=='08')echo 'selected'?>>August</option>
                                    <option value="09" <?php if($payment->month_from=='09')echo 'selected'?>>September</option>
                                    <option value="10" <?php if($payment->month_from=='10')echo 'selected'?>>October</option>
                                    <option value="11" <?php if($payment->month_from=='11')echo 'selected'?>>November</option>
                                    <option value="12" <?php if($payment->month_from=='12')echo 'selected'?>>December</option>
                                </select>
                            </td>
                            <td>
                                <select id="month_to" name="month_to" class="form-control">
                                    <option value="01" <?php if($payment->month_to=='01')echo 'selected'?>>January</option>
                                    <option value="02" <?php if($payment->month_to=='02')echo 'selected'?>>February</option>
                                    <option value="03" <?php if($payment->month_to=='03')echo 'selected'?>>March</option>
                                    <option value="04" <?php if($payment->month_to=='04')echo 'selected'?>>April</option>
                                    <option value="05" <?php if($payment->month_to=='05')echo 'selected'?>>May</option>
                                    <option value="06" <?php if($payment->month_to=='06')echo 'selected'?>>June</option>
                                    <option value="07" <?php if($payment->month_to=='07')echo 'selected'?>>July</option>
                                    <option value="08" <?php if($payment->month_to=='08')echo 'selected'?>>August</option>
                                    <option value="09" <?php if($payment->month_to=='09')echo 'selected'?>>September</option>
                                    <option value="10" <?php if($payment->month_to=='10')echo 'selected'?>>October</option>
                                    <option value="11" <?php if($payment->month_to=='11')echo 'selected'?>>November</option>
                                    <option value="12" <?php if($payment->month_to=='12')echo 'selected'?>>December</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control h_input amount" name="<?php echo $item['form_amount_name']?>" id="<?php echo $item['form_amount_name']?>" value="<?php echo $item['item_amount']?>" autofocus readonly></td>
                        </tr>
                    <?php
                    }else{
                        ?>
                        <tr>
                            <td><a href="javascript:void(0)" class="close <?php echo $sl-1;?>" style="width: 15%;float: left"><img src="<?php echo base_url();?>assets/images/close.png"></a><input style="width: 85%;float: left" type="text" class="form-control h_input" name="<?php echo $item['form_item_name']?>" id="<?php echo $item['form_item_name']?>" value="<?php echo $item['item_name']?>" autofocus readonly></td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control h_input amount" name="<?php echo $item['form_amount_name']?>" id="<?php echo $item['form_amount_name']?>" value="<?php echo $item['item_amount']?>" autofocus readonly></td>
                        </tr>
                    <?php
                    }
                    $sl++;
                }
                ?>
                </tbody>
            </table>
            <div class="form-group" style="margin-top: 3%;float: left;width: 50%;">
                <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Additional Fee');?></label>

                <div class="col-sm-7">
                    <select name="additional_fee" class="form-control" id="additional_fee">
                        <option value=""><?php echo get_phrase('select');?></option>
                        <?php
                        $fees = $this->db->get('fees')->result_array();
                        foreach($fees as $row):
                            $info = $row['fee_name'].','.$row['fee_amount'];
                            ?>
                            <option class="<?php echo $info;?>" value="<?php echo $row['id'];?>">
                                <?php echo $row['fee_name'];?>
                            </option>
                        <?php
                        endforeach;
                        //adding admission fee and evaluation fee
                        $class_admission = 'Admission Fee,'.$class->admission_fee;
                        $class_evaluation = 'Evaluation Fee,'.$class->evaluation_fee;
                        echo '<option class="'.$class_admission.'" value="admission_fee">Admission Fee</option>';
                        echo '<option class="'.$class_evaluation.'" value="evaluation_fee">Evaluation Fee</option>';
                        ?>
                    </select>
                </div>
            </div>
            <div class="right">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Adjustment Amount');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_adjustment_amount" class="form-control" name="invoice_adjustment_amount" value="<?php echo $payment->adjustment_amount;?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Adjustment to');?></label>

                    <div class="col-sm-5">
                        <select name="adjustment_to" class="form-control" id="adjustment_to">
                            <option value="0"><?php echo get_phrase('select');?></option>
                            <option value="1" <?php if($payment->adjustment_to==1)echo 'selected';?>>Monthly Fee</option>
                            <option value="2" <?php if($payment->adjustment_to==2)echo 'selected';?>>Admission Fee</option>
                            <option value="3" <?php if($payment->adjustment_to==3)echo 'selected';?>>Evaluation Fee</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Total Amount');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_total_amount" class="form-control" name="invoice_total_amount" value="<?php echo $payment->total_amount;?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <?php $vat = $this->db->get_where('settings', array('type' => 'vat'))->row()->description;?>
                    <label class="col-sm-3 control-label"><?php echo get_phrase('VAT ').' ('.$vat.'%)';?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_vat" class="form-control" name="invoice_vat" value="<?php echo $payment->vat;?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Total Receivable');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_total_receivable" class="form-control" name="invoice_total_receivable" value="<?php echo $payment->total_receivable;?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Received Amount');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_received_amount" class="form-control" name="invoice_received_amount" value="<?php echo $payment->received_amount;?>" data-validate="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Return Amount');?></label>
                    <div class="col-sm-9">
                        <input type="text" id="invoice_return_amount" class="form-control" name="invoice_return_amount" value="<?php echo $payment->returned_amount;?>" data-validate="required"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-5">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_invoice');?></button>
    </div>
</div>
<div class="site_url" style="display: none"><?php echo base_url();?></div>
</div>
<?php echo form_close();?>
</div>
<!----CREATION FORM ENDS-->

</div>
</div>
</div>

<script type="text/javascript">


function get_class_sections(class_id) {

    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
        success: function(response)
        {
            jQuery('#section_selector_holder').html(response);

            var c = $('#class_id').val();
            var s = $('#section_selector_holder').val();

            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_students_by_class_section/' + c + '/' + s,
                success: function(response)
                {
                    jQuery('#student_selection').html(response);
                    var clss = jQuery('#student_selection option:selected').attr('class');
                    var info = clss.split(",");
                    jQuery('#student_id').val(info[0]);
                    jQuery('#student_name').val(info[1]);
                    jQuery('#parent_name').val(info[2]);
                }
            });
        }
    });
}

$('#find_student').click(function(){
    var s_id = $('#student_id').val();
    s_id = s_id.replace("/", "-");
    s_id = s_id.replace("/", "-");
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_students_by_id/' + s_id,
        success: function(response)
        {
            var info = response.split("~");
            var name = info[0];
            var father = info[1];
            var cls = info[2];
            var monthly_fee = info[3];
            var admission_fee = info[4];
            var evaluation_fee = info[5];
            var section = info[6];
            var parent_status = info[7];
            var waiver = info[8];

            jQuery('#student_name').val(name);
            jQuery('#parent_name').val(father);
            jQuery('#student_class').val(cls);

            //Checking for the waiver
            if(parent_status==1){
                monthly_fee = monthly_fee * (waiver/100);
                jQuery('#fee_total').val(monthly_fee);
            }else{
                jQuery('#fee_total').val(monthly_fee);
            }
            jQuery('#real_monthly_fee').val(monthly_fee);
            jQuery('#student_section').val(section);

            //Handling fees
            $("#af").remove();
            $("#ef").remove();
            var fee = '';
            fee += '<option id="af" class="Admission Fee,'+admission_fee+'" value="admission_fee">Admission Fee</option>';
            fee += '<option id="ef" class="Evaluation Fee,'+evaluation_fee+'" value="evaluation_fee">Evaluation Fee</option>';
            $('#additional_fee').append(fee);

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();

            old_total = monthly_fee;
        }
    });

    //Get student payment info
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_students_payment_stauts_by_id/' + s_id,
        success: function(response)
        {
            var info = response.split("~");
            $('#student_real_id').val(info[0]);
            $('#s_payment_info').html(info[1]);
            $('#payment_table_edit').append(info[2]);

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();
        }
    });
});

$('#section_selector_holder').change(function(){
    var c = $('#class_id').val();
    var s = $('#section_selector_holder').val();

    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_students_by_class_section/' + c + '/' + s,
        success: function(response)
        {
            jQuery('#student_selection').html(response);
        }
    });
});

$(document).delegate('#additional_fee','change',function(){
    var v = $(this).val();
    var c = $(this).find('option:selected').attr('class');
    c = c.split(",");
    var add_fee_name = c[0];
    var add_fee_amount = c[1];
    var url = $('.site_url').html();
    url = url + 'assets/images/close.png';

    var html = '<tr id="'+v+'"><td><button class="close '+v+'" style="width: 15%;float: left"><img src="'+url+'"></button><input style="width: 85%;float: left" type="text" class="form-control h_input" name="add_fee_name'+v+'" id="fee_name'+v+'" value="'+add_fee_name+'" autofocus readonly></td><td></td><td></td><td><input type="text" class="form-control h_input amount" name="add_fee_total'+v+'" id="add_fee_total'+v+'" value="'+add_fee_amount+'" autofocus readonly></td></tr>';
    $('#payment_table_edit').append(html);
    //$('#payment_table').remove();

    //Final amount calculation
    calculate_total_amount();
    calculate_total_vat();
    calculate_total_receivable();

    //Adding additional fee number
//        var afn = $('#number_of_additional_fee').val();
//        afn = parseInt(afn) + 1;
//        $('#number_of_additional_fee').val(afn);
});

$(document).delegate('.close','click',function(){
    var clss = $(this).attr('class');
    clss = clss.split(" ");
    clss = clss[1];
    $('#'+clss).remove();

    //Final amount calculation
    calculate_total_amount();
    calculate_total_vat();
    calculate_total_receivable();
});

function calculate_total_amount(){
    var total = 0;
    $('.amount').each(function(){
        var s_total = $(this).val();
        total = total + parseFloat(s_total);
    });
    $('#invoice_total_amount').val(total);
}

function calculate_total_vat(){
    var invoice_total_amount = $('#invoice_total_amount').val();
    var vat = $('#vat').val();
    var total_vat = invoice_total_amount*(vat/100);
    total_vat = Math.ceil(total_vat);
    $('#invoice_vat').val(total_vat);
}

function calculate_total_receivable(){
    var invoice_total_amount = $('#invoice_total_amount').val();
    var invoice_vat = $('#invoice_vat').val();
    var total_receivable = parseFloat(invoice_total_amount) + parseFloat(invoice_vat);
    $('#invoice_total_receivable').val(total_receivable);
}

$(function(){
    calculate_total_amount();
    calculate_total_vat();
    calculate_total_receivable();

    //$('.datepicker').datepicker('option', 'dateFormat', 'd/m/Y');
    var dateFormat = $( ".selector" ).datepicker( "option", "dateFormat" );
})
var old_to = $('#month_to').val();
var old_total = $('#fee_total').val();
$('#month_to').change(function(){
    var from = $('#month_from').val();
    var to = $('#month_to').val();
    if(from>to){
        alert('To month can not be smaller than From month');
        $('#month_to').val(old_to);
    }else{
        old_to = to;
        var diff = Math.abs(parseInt(from)-parseInt(to)) + parseInt(1);
        var total = parseFloat(old_total);
        total = total * diff;
        $('#fee_total').val(total);

        //Final amount calculation
        calculate_total_amount();
        calculate_total_vat();
        calculate_total_receivable();
    }
});
var old_from = $('#month_from').val();
$('#month_from').change(function(){
    var from = $('#month_from').val();
    var to = $('#month_to').val();
    if(from>to){
        alert('From month can not be greater than To month');
        $('#month_from').val(old_from);
    }else{
        old_from = from;
        var diff = Math.abs(parseInt(from)-parseInt(to)) + parseInt(1);
        var total = parseFloat(old_total);
        total = total * diff;
        $('#fee_total').val(total);

        //Final amount calculation
        calculate_total_amount();
        calculate_total_vat();
        calculate_total_receivable();
    }
});

$('#invoice_received_amount').keyup(function(){
    var ra = $(this).val();
    if(ra!=""){
        var ta = $('#invoice_total_receivable').val();
        var rta = ra - ta ;
        $('#invoice_return_amount').val(rta);
    }else{
        $('#invoice_return_amount').val('');
    }
});

var a_monthly_fee = '';
var a_admission_fee = '' ;
var a_evaluation_fee = '';
$('#adjustment_to').change(function(){
    if(a_monthly_fee=="" || a_monthly_fee==0){
        a_monthly_fee = $('#fee_total').val();
    }
    if(a_admission_fee=="" || a_admission_fee==0){
        a_admission_fee = $('#add_fee_totaladmission_fee').val();
    }
    if(a_evaluation_fee=="" || a_evaluation_fee==0){
        a_evaluation_fee = $('#add_fee_totalevaluation_fee').val();
    }
    var aa = $('#invoice_adjustment_amount').val();
    if(aa!=""){
        var at = $('#adjustment_to').val();
        if(at==1){
            $('#fee_total').val(aa);
            var diff = a_monthly_fee - aa;
            $('#h_adjustment_due').val(diff);
            $('#h_adjustment_to').val(1);

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();
        }else if(at==2){
            $('#add_fee_totaladmission_fee').val(aa);
            var diff = a_admission_fee - aa;
            $('#h_adjustment_due').val(diff);
            $('#h_adjustment_to').val(2);

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();
        }else if(at==3){
            $('#add_fee_totalevaluation_fee').val(aa);
            var diff = a_evaluation_fee - aa;
            $('#h_adjustment_due').val(diff);
            $('#h_adjustment_to').val(3);

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();
        }else{
            $('#fee_total').val(a_monthly_fee);
            $('#add_fee_totaladmission_fee').val(a_admission_fee);
            $('#add_fee_totalevaluation_fee').val(a_evaluation_fee);
            $('#invoice_adjustment_amount').val('');

            $('#h_adjustment_due').val('');
            $('#h_adjustment_to').val('');

            //Final amount calculation
            calculate_total_amount();
            calculate_total_vat();
            calculate_total_receivable();
        }
    }
});

</script>