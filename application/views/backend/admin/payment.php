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
    .deleted{
        background-color: #F5A9A9;
    }
    .old{
        background-color: #D8D8D8;
    }
    #remission{
        /*display: none;*/
    }
</style>
<hr />
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/payment_add/');"
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('New Payment');?>
    </a> 
<br>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('All Payments');?></span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th class="span3"><div><?php echo get_phrase('Payment ID');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Pay To');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Address');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Purpose');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Amount');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Branch');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Payment By');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('Date');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                </table>
                    
            </div>
        </div>
        
        
    </div>
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
            "sAjaxSource": "<?php echo base_url('index.php?admin/server_processing_all_payment');?>",
            "iDisplayLength": 10,
            "adom": 'Bfrtip',
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>