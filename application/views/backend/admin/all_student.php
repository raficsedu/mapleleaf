<style>
    .deleted{
        background-color: #F5A9A9;
    }
    .old{
        background-color: #D8D8D8;
    }
</style>
<hr />
<div class="row">
    <div class="col-md-12">

        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">

                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                    <tr>
                        <th width="70"><div>SL</div></th>
                        <th><div>Student ID</div></th>
                        <th><div>name</div></th>
                        <th><div>class</div></th>
                        <th><div>section</div></th>
                        <th><div>parent</div></th>
                        <th><div>DOB</div></th>
                        <th><div>Gender</div></th>
                        <th><div>SMS No</div></th>
                        <th><div>phone</div></th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>
<div id="table_header" style="display: none">
    <h1 style="width: 100%;float: left;text-align: center"><?php echo $system_name;?></h1>
    <h3 style="width: 100%;float: left;text-align: center">Report Name : All Current Student</h3>
</div>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->
<script type="text/javascript">

    jQuery(function($)
    {
        var datatable = jQuery("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "aaSorting": [],
            "order": [[ 0, "desc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "<?php echo base_url('index.php?admin/server_processing_all_student');?>",
            "iDisplayLength": 10,
            "adom": 'Bfrtip',
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
            "oTableTools": {
                "aButtons": [

                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1,2, 3, 4, 5, 6, 7,8,9]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2, 3, 4, 5, 6, 7,8,9]
                    },
                    {
                        "sExtends": "print",
                        "fnSetText"	   : "Press 'esc' to return",
                        "sMessage": jQuery('#table_header').html(),
                        "fnClick": function (nButton, oConfig) {
                            //datatable.fnSetColumnVis(2, false);
                            //datatable.fnSetColumnVis(5, false);

                            this.fnPrint( true, oConfig );

                            window.print();

                            jQuery(window).keyup(function(e) {
                                if (e.which == 27) {
                                    //datatable.fnSetColumnVis(2, true);
                                    //datatable.fnSetColumnVis(5, true);
                                }
                            });
                        }
                    }
                ]
            }
        });

        jQuery(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });

</script>