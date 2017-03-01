
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_parent_add/');" 
                class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_new_parent');?>
                </a> 
                <br><br>
            <div class="">
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                    <tr>
                        <th width="100">SL</th>
                        <th width="100">Parent ID</th>
                        <th><div>Father name</div></th>
                        <th><div>Mother name</div></th>
                        <th><div>email</div></th>
                        <th><div>phone</div></th>
                        <th><div>Address</div></th>
                        <?php
                        if($_SESSION['level']==1){
                            echo '<th><div>Added By</div></th>';
                            echo '<th><div>Edited By</div></th>';
                        }
                        ?>
                        <th><div><?php echo get_phrase('options');?></div></th>
                    </tr>
                    </thead>
                </table>
            </div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

    jQuery(document).ready(function($)
    {
        

        var datatable = $("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "aaSorting": [],
            "order": [[ 0, "desc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "<?php echo base_url('index.php?admin/server_processing_all_parent');?>",
            "iDisplayLength": 10,
            "adom": 'Bfrtip',
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
            "oTableTools": {
                "aButtons": [
                    
                    {
                        "sExtends": "xls",
                        "mColumns": [1,2,3,4,5,6]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [1,2,3,4,5,6]
                    },
                    {
                        "sExtends": "print",
                        "fnSetText"    : "Press 'esc' to return",
                        "fnClick": function (nButton, oConfig) {
                            //datatable.fnSetColumnVis(5, false);
                            
                            this.fnPrint( true, oConfig );
                            
                            window.print();
                            
                            $(window).keyup(function(e) {
                                  if (e.which == 27) {
                                      //datatable.fnSetColumnVis(5, true);
                                  }
                            });
                        }
                        
                    }
                ]
            }
            
        });
        
        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });
        
</script>

