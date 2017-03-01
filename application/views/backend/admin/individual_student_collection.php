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
    <form action="<?php echo base_url();?>index.php?admin/report/individual_student_collection" method="post">
        <div class="form-group">
            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Student ID');?></label>
            <div class="col-sm-2">
                <input type="text" id="roll" class="form-control" name="roll" value="<?php echo $_POST['roll'];?>" placeholder="Student ID" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
            </div>

            <label for="field-2" class="col-sm-1 control-label"><?php echo get_phrase('Year');?></label>
            <div class="col-sm-2">
                <input type="text" id="session" class="form-control" name="session" value="<?php echo $_POST['session'];?>" placeholder="Year" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
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
                <th><div><?php echo get_phrase('Student ID');?></div></th>
                <th><div><?php echo get_phrase('Name');?></div></th>
                <th><div><?php echo get_phrase('Description');?></div></th>
                <th><div><?php echo get_phrase('Pay Date');?></div></th>
                <th><div><?php echo get_phrase('Receipt No');?></div></th>
                <th><div><?php echo get_phrase('Month From');?></div></th>
                <th><div><?php echo get_phrase('Month To');?></div></th>
                <th><div><?php echo get_phrase('Amount');?></div></th>
                <th><div><?php echo get_phrase('Remark');?></div></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $total_collection = 0;
                $total_amount = 0;
                $total_refund = ($total_refund>0) ? $total_refund : 0;
                $total_vat = 0;
                $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                foreach($payments as $payment):
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
                            <td>'.$item['item_name'].'</td>
                            <td>'.date('d/m/Y',strtotime($payment['timestamp'])).'</td>
                            <td>'.$payment['book_no'].'</td>
                            <td>'.$m_from.'</td>
                            <td>'.$m_to.'</td>
                            <td>'.$item['item_amount'].'</td>
                            <td>Collected</td>
                        </tr>
                        ';
                    }
                    $total_amount += $payment['total_amount'];
                    $total_collection += $payment['total_receivable'];
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
    <h3 style="width: 100%;float: left;text-align: center">Report Name : Individual Student Collection</h3>
    <hr>
    <div style="width: 100%;float: left;">
        <div style="width: 20%;float: left;">
            <h4>Student ID : <?php if($_POST['roll'])echo $_POST['roll'];?></h4>
        </div>
        <div style="width: 20%;float: left;">
            <h4>Year : <?php if($_POST['session'])echo $_POST['session'];?></h4>
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
                        "mColumns": [0, 1,2, 3, 4,5,6,7,8]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4,5,6,7,8]
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