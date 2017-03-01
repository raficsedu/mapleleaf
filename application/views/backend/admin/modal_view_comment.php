<style>
    .cp-studying th{
        text-align: center !important;
    }
    .h_plus{
        width: 10%;
        float: left;
        padding-top: 5%;
    }
    .h_input{
        width: 90% !important;
        float: left;
    }
    .cl{
        font-size: 15px;
        width: 100%;
        text-align: center !important;
        margin-bottom: 2% !important;
    }
    .modal-dialog {
        width: 960px !important;
        padding-top: 30px;
        padding-bottom: 30px;
    }
    .print_div{
        width: 10%;
        float: right;
    }
    .print_div a{
        font-size: 20px;
    }
</style>
<?php
$edit_data		=	$this->db->get_where('comment' , array('comment_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
    ?>
    <div class="row">
    <div class="col-md-12">
    <div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title" >
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('comment');?>
        </div>
        <div class="print_div">
            <a onclick="printDiv('printableArea')" href="javascript:void(0)"><i class="entypo-print"></i> Print</a>
        </div>
    </div>
    <div class="panel-body" id="printableArea">

    <?php echo form_open(base_url() . 'index.php?admin/student/'.$row['class_id'].'/do_update/'.$row['student_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Student ID');?></label>

            <div class="col-sm-5">
                <input type="text" class="form-control" name="roll" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $this->crud_model->get_type_name_by_id('student',$row['student_id'],'roll');?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Comment');?></label>

            <div class="col-sm-5">
                <textarea rows="5" cols="100" name="comment" disabled><?php echo $row['comment'];?></textarea>
            </div>
        </div>
    <?php echo form_close();?>
    </div>
    </div>
    </div>
    </div>

<?php
endforeach;
?>

<script type="text/javascript">

    function get_class_sections(class_id) {

        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

    }

    var class_id = $("#class_id").val();

    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
        success: function(response)
        {
            jQuery('#section_selector_holder').html(response);
        }
    });

    $(function(){
        var m = $('#month').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }

        //Selected class action for Tutuition fee
        var c = $('#fee_class_id option:selected').attr("class");
        var fees = c.split(",");
        var monthly_fee = parseFloat(fees[0]);
        var admission_fee = parseFloat(fees[1]);
        var evaluation_fee = parseFloat(fees[2]);
        var vat = parseFloat(fees[3]);
        var total = monthly_fee + admission_fee +  evaluation_fee;
        var final_total = total + (total * (vat/100));

        //Putting to necessary field
        $('#monthly_fees').val(monthly_fee);
        $('#admission_fees').val(admission_fee);
        $('#evaluation_fees').val(evaluation_fee);
        $('#vat').val(vat);
        $('#total').val(final_total);

        var s = $('#parent_status').val();
        alert(s);
        if(s==1){
            $('#waiver').show();
        }else if(s==0){
            $('#waiver').hide();
        }
    });

    $('#month').change(function(){
        var m = $('#month').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#year').html(html);
        }
    });

    $(document).delegate('#fee_class_id','change',function(){
        var c = $('#fee_class_id option:selected').attr("class");
        var fees = c.split(",");
        var monthly_fee = parseFloat(fees[0]);
        var admission_fee = parseFloat(fees[1]);
        var evaluation_fee = parseFloat(fees[2]);
        var vat = parseFloat(fees[3]);
        var total = monthly_fee + admission_fee +  evaluation_fee;
        var final_total = total + (total * (vat/100));

        //Putting to necessary field
        $('#monthly_fees').val(monthly_fee);
        $('#admission_fees').val(admission_fee);
        $('#evaluation_fees').val(evaluation_fee);
        $('#vat').val(vat);
        $('#total').val(final_total);
    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>