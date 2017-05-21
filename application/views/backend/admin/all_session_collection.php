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
    <form action="<?php echo base_url();?>index.php?admin/report/all_session_collection" method="post">
        <div class="form-group">
            <label for="field-2" class="col-sm-2 control-label"><?php echo get_phrase('Branch Information');?></label>
            <div class="col-sm-2">
                <select name="branch_info" class="form-control" data-validate="required" id="branch_info"
                        data-message-required="<?php echo get_phrase('value_required');?>">
<!--                    <option value="">--><?php //echo get_phrase('select branch');?><!--</option>-->
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

            <div class="col-sm-2">
                <input type="text" id="date_from" class="form-control datepicker" name="date_from" value="<?php if(isset($_POST['date_from'])){echo $_POST['date_from'];}else{echo date('d/m/Y');}?>" placeholder="Date From" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-start-view="2">
            </div>

            <div class="col-sm-2">
                <input type="text" id="date_to" class="form-control datepicker" name="date_to" value="<?php if(isset($_POST['date_to'])){echo $_POST['date_to'];}else{echo date('d/m/Y');}?>" placeholder="Date To" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" data-start-view="2">
            </div>

            <div class="col-sm-2">
                <select name="session" class="form-control" data-validate="required" id="session"
                        data-message-required="<?php echo get_phrase('value_required');?>">
                    <!--                    <option value="">--><?php //echo get_phrase('select branch');?><!--</option>-->
                    <?php
                    $year = date('Y') - 5;

                    for($i=$year;$i<=date('Y');$i++){
                        $j = $i.'-'.($i+1);
                        if($_POST['session']==$i){
                            $s = 'selected';
                        }else{
                            $s = '';
                        }

                        if($_POST['session']==$j){
                            $ss = 'selected';
                        }else{
                            $ss = '';
                        }

                        echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
                        echo '<option value="'.$j.'" '.$ss.'>'.$j.'</option>';
                    }
                    ?>
<!--                    <option value="--><?php //echo date('Y');?><!--" --><?php //if($_POST['session']==$year)echo 'selected';?><!-->-->
<!--                        --><?php //echo date('Y');?>
<!--                    </option>-->
<!--                    <option value="--><?php //echo date('Y').'-'.(date('Y')+1);?><!--" --><?php //if($_POST['session']==$year1)echo 'selected';?><!-->-->
<!--                        --><?php //echo date('Y').'-'.(date('Y')+1);?>
<!--                    </option>-->
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
                <th><?php echo get_phrase('Session');?></th>
                <th><?php echo get_phrase('Total Tuition Fees');?></th>
                <th><?php echo get_phrase('Total Others Fees');?></th>
                <th><?php echo get_phrase('Total VAT');?></th>
                <th><?php echo get_phrase('Net Collection');?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $total_collection = 0;
                $total_payment = ($total_paid > 0) ? $total_paid : 0;
                $total_refund = 0;

                $total_tuition_fee = 0;
                $total_others_fee = 0;
                $total_vat = 0;
                $net_collection = 0;
                foreach($payments as $payment):
                    //Calculations
                    if($payment['payment_type']==1){
                        $row_tuition_fee = $payment['monthly_fee'] * (intval($payment['month_to']) - intval($payment['month_from']) + 1);
                        $total_tuition_fee += $row_tuition_fee;
                        $total_others_fee += $payment['total_amount'] - $row_tuition_fee;
                        $total_vat += $payment['vat'];
                        $net_collection += $payment['total_receivable'];
                    }else if($payment['payment_type']==2){
                        $total_refund = $total_refund + $payment['total_amount'];
                    }
                endforeach;
                if($payments){
                    echo '<tr>
                    <td>'.$_POST['session'].'</td>
                    <td>'.$total_tuition_fee.'</td>
                    <td>'.$total_others_fee.'</td>
                    <td>'.$total_vat.'</td>
                    <td>'.$net_collection.'</td>
                </tr>';
                }

                //Printing bottom Final row
                echo '
                <tr>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue">Total Collection</td>
                    <td style="font-weight:bold;color:blue">'.$net_collection.'</td>
                </tr>
                ';

                echo '
                <tr>
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
                    <td style="font-weight:bold;color:blue">Total Refund</td>
                    <td style="font-weight:bold;color:blue">'.$total_refund.'</td>
                </tr>
                ';

                echo '
                <tr>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue"></td>
                    <td style="font-weight:bold;color:blue">Total Balance</td>
                    <td style="font-weight:bold;color:blue">'.($net_collection - $total_payment - $total_refund).'</td>
                </tr>
                ';
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="table_header" style="display: none;">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : All Session Collection</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 20%;float: left;">
            <h4>Branch : <?php if($_POST['branch_info'])echo $this->crud_model->get_type_name_by_id('branch',$_POST['branch_info'],'branch_name');?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Date From : <?php if($_POST['date_from'])echo $_POST['date_from'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Date To : <?php if($_POST['date_to'])echo $_POST['date_to'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Session : <?php if($_POST['session'])echo $_POST['session'];?></h4>
        </div>
    </div>
</div>

<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->
<script type="text/javascript">

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
                        "mColumns": [0, 1,2, 3, 4]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4]
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