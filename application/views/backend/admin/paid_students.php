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
    <form id="filter_form" action="<?php echo base_url();?>index.php?admin/report/paid_students" method="post">
        <div class="row">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Branch');?></label>
            <div class="col-sm-2">
                <select name="branch_info" class="form-control" data-validate="required" id="branch_info"
                        data-message-required="<?php echo get_phrase('value_required');?>">
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
                    }
                    ?>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Building');?></label>
            <div class="col-sm-2">
                <select name="building_info" class="form-control" id="building_info">
                    <option value=""><?php echo get_phrase('select building');?></option>
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

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Month');?></label>
            <div class="col-sm-2">
                <select name="month" id="month" class="form-control">
                    <option value="01" <?php if($_POST['month'] == "01")echo "selected";?>>January</option>
                    <option value="02" <?php if($_POST['month'] == "02")echo "selected";?>>February</option>
                    <option value="03" <?php if($_POST['month'] == "03")echo "selected";?>>March</option>
                    <option value="04" <?php if($_POST['month'] == "04")echo "selected";?>>April</option>
                    <option value="05" <?php if($_POST['month'] == "05")echo "selected";?>>May</option>
                    <option value="06" <?php if($_POST['month'] == "06")echo "selected";?>>June</option>
                    <option value="07" <?php if($_POST['month'] == "07")echo "selected";?>>July</option>
                    <option value="08" <?php if($_POST['month'] == "08")echo "selected";?>>August</option>
                    <option value="09" <?php if($_POST['month'] == "09")echo "selected";?>>September</option>
                    <option value="10" <?php if($_POST['month'] == "10")echo "selected";?>>October</option>
                    <option value="11" <?php if($_POST['month'] == "11")echo "selected";?>>November</option>
                    <option value="12" <?php if($_POST['month'] == "12")echo "selected";?>>December</option>
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
                    <option value="01" <?php if($_POST['session']=='01')echo 'selected';?>><?php echo get_phrase('JAN');?></option>
                    <option value="07" <?php if($_POST['session']=='07')echo 'selected';?>><?php echo get_phrase('JULY');?></option>
                </select>
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Year');?></label>
            <div class="col-sm-2">
                <select id="year" name="year" class="form-control">
                    <?php
                    $current_year = date('Y');
                    if(isset($_POST['session']) && $_POST['session'] == "01"){
                        for($i = $current_year-3;$i <= $current_year;$i++){
                            if($i == $_POST['year']){
                                $s = "selected";
                            }else{
                                $s = "";
                            }
                            echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
                        }
                    }else if(isset($_POST['session']) && $_POST['session'] == "07"){
                        for($i = $current_year-3;$i <= $current_year;$i++){
                            $j = $i+1;
                            $y = $i.'-'.$j;
                            if($y == $_POST['year']){
                                $s = "selected";
                            }else{
                                $s = "";
                            }
                            echo '<option value="'.$y.'" '.$s.'>'.$y.'</option>';
                        }
                    }else{
                        for($i = $current_year-3;$i <= $current_year;$i++){
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    }
                    ?>
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
                <th><?php echo get_phrase('VAT');?></th>
                <th><?php echo get_phrase('TOTAL');?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $sl = 0;
                $total_collection = 0;
                $row_total = 0;
                $vat_total = 0;
                foreach($payments as $payment){
                    $sl++;
                    echo '<tr>';
                    echo '<td>'.$this->crud_model->get_type_name_by_id('branch',$payment['branch_id'],'branch_name').'</td>';
                    echo '<td>'.$payment['roll'].'</td>';
                    echo '<td>'.$payment['name'].'</td>';
                    echo '<td>'.$this->crud_model->get_type_name_by_id('class',$payment['class_id']).'</td>';
                    echo '<td>'.$this->crud_model->get_type_name_by_id('section',$payment['section_id']).'</td>';

                    for($i=1;$i<=12;$i++){
                        $month[$i] = '';
                    }
                    if($_POST['session'] == "01"){
                        $to_month = intval($_POST['month']);
                    }else{
                        $to_month = (intval($_POST['month']) <= 6) ? intval($_POST['month']) + 12 : intval($_POST['month']);
                    }
                    //Student Payment
                    $sql = "SELECT * FROM payment WHERE student_id='$payment[student_id]' AND payment_type='1' AND deleted='0' AND payment_year='$_POST[year]' AND CAST(month_from AS UNSIGNED)<= '$to_month'";
                    $query = $this->db->query($sql);
                    $student_payments = $query->result_array();
                    foreach($student_payments as $sp){
                        $from = intval($sp['month_from']);
                        $to = (intval($sp['month_to']) > $to_month) ? $to_month : intval($sp['month_to']);

                        for($i=$from;$i<=$to;$i++){
                            $j = $i%12;
                            if($j==0)
                                $j = 12;
                            $month[$j] = ($sp['monthly_fee_given'] < $sp['monthly_fee']) ? $sp['monthly_fee_given'] : $sp['monthly_fee'];
                            $row_total += $month[$j];
                            $vat_total += $sp['vat'];
                            $row_total += $vat_total;
                        }
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

                    $admission_fee = 0;
                    $evaluation_fee = 0;
                    $c_lab = 0;
                    $p_lab = 0;

                    //Printing payment items
                    $sql = "SELECT payment_items.* FROM payment_items INNER JOIN payment ON payment.payment_id=payment_items.payment_id WHERE payment.student_id='$payment[student_id]' AND payment.payment_type='1' AND payment.deleted='0' AND payment.payment_year='$_POST[year]' AND CAST(payment.month_from AS UNSIGNED)<= '$to_month'";
                    $query = $this->db->query($sql);
                    $payment_item = $query->result_array();

                    foreach($payment_item as $item){
                        if($item['form_item_name']=='add_fee_nameadmission_fee'){
                            $admission_fee = $item['item_amount'];
                            $row_total += $admission_fee;
                            continue;
                        }
                        else if($item['form_item_name']=='add_fee_nameevaluation_fee'){
                            $evaluation_fee = $item['item_amount'];
                            $row_total += $evaluation_fee;
                            continue;
                        }
                        else if($item['form_item_name']=='add_fee_namec_lab_fee'){
                            $c_lab = $item['item_amount'];
                            $row_total += $c_lab;
                            continue;
                        }
                        else if($item['form_item_name']=='add_fee_namep_lab_fee'){
                            $p_lab = $item['item_amount'];
                            $row_total += $p_lab;
                            continue;
                        }
                    }
                    echo '<td>'.$evaluation_fee.'</td>';
                    echo '<td>'.$admission_fee.'</td>';
                    echo '<td>'.$c_lab.'</td>';
                    echo '<td>'.$p_lab.'</td>';
                    //Printing out VAT and TOTAL
                    echo '<td>'.$vat_total.'</td>';
                    echo '<td>'.$row_total.'</td>';
                    echo '</tr>';

                    //Calculations
                    $total_collection+= $row_total;
                }

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
                        <td style="font-weight:bold;color:blue">Total Amount</td>
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
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Paid Students</h3>
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
            <h4>Month : <?php if($_POST['month'])echo $_POST['month'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Gender : <?php if($_POST['gender'])echo $_POST['gender'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Year : <?php if($_POST['year'])echo $_POST['year'];?></h4>
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

    function set_month(){
        var session = $('#session').val();
        if(session == "01"){
            var html = '<option value="01">January</option>' +
                '<option value="02">February</option>' +
                '<option value="03">March</option>' +
                '<option value="04">April</option>' +
                '<option value="05">May</option>' +
                '<option value="06">June</option>' +
                '<option value="07">July</option>' +
                '<option value="08">August</option>' +
                '<option value="09">September</option>' +
                '<option value="10">October</option>' +
                '<option value="11">November</option>' +
                '<option value="12">December</option>';
        }else if(session == "07"){
            var html = '<option value="07">July</option>' +
                '<option value="08">August</option>' +
                '<option value="09">September</option>' +
                '<option value="10">October</option>' +
                '<option value="11">November</option>' +
                '<option value="12">December</option>' +
                '<option value="01">January</option>' +
                '<option value="02">February</option>' +
                '<option value="03">March</option>' +
                '<option value="04">April</option>' +
                '<option value="05">May</option>' +
                '<option value="06">June</option>';
        }
        $('#month').html(html);
    }

    function set_year(){
        var session = $('#session').val();
        var current_year = new Date().getFullYear();
        var html = '';
        if(session == "01"){
            for(var i = current_year-3;i<=current_year;i++){
                html += '<option value="'+i+'">'+i+'</option>';
            }
        }else if(session == "07"){
            for(var i = current_year-3;i<=current_year;i++){
                var j = i+1;
                html += '<option value="'+i+'-'+j+'">'+i+'-'+j+'</option>';
            }
        }
        $('#year').html(html);
    }

    jQuery('#session').change(function(){
        var class_id = jQuery('#class_id').val();
        get_class_sections(class_id);
        set_month();
        set_year();
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
                        "mColumns": [0, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
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