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
    #waiver{
        display: none;
    }
    #load_student{
        padding: 5px;
        border: 1px solid blue;
    }
    .load{
        margin-top: 18% !important;
    }
    #p_students{
        padding: 2%;
        font-size: 17px !important;
    }
    .p_success{
        color: blue !important;
        font-size: 17px !important;
    }
    .p_fail{
        color: red !important;
        font-size: 17px !important;
    }
</style>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('student Promotion');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php //echo form_open(base_url() . 'index.php?admin/student_promotion/promotion/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                <form class="form-horizontal form-groups-bordered validate" action="<?php echo base_url();?>index.php?admin/student_promotion/promotion/" method="post">
					
					<div class="form-group">

                        <div class="col-md-6" style="border-right: 1px solid;">

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Session');?></label>
                            <div class="col-sm-9">
                                <select name="from_session" id="from_session" class="form-control">
                                    <option value="01">JAN</option>
                                    <option value="07">JUL</option>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                            <div class="col-sm-9">
                                <select name="from_class_id" class="form-control" data-validate="required" id="from_class_id"
                                        data-message-required="<?php echo get_phrase('value_required');?>"
                                        onchange="return from_class(this.value)">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                    $classes = $this->db->get('class')->result_array();
                                    foreach($classes as $row):
                                        ?>
                                        <option value="<?php echo $row['class_id'];?>">
                                            <?php echo $row['name'];?>
                                        </option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
                            <div class="col-sm-9">
                                <select name="from_section_id" class="form-control" id="from_section_id">
                                    <option value=""><?php echo get_phrase('select_class_first');?></option>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Year');?></label>
                            <div class="col-sm-9">
                                <select name="from_year" class="form-control" id="from_year">

                                </select>
                                <div class="s-year" style="display: none;">
                                    <?php
                                    $year = date('Y')-2;
                                    for($i= $year;$i<=$year+5;$i++){
                                        echo '<option class="jan" value="'.$i.'">'.$i.'</option>';
                                        echo '<option class="july" value="'.$i.'-'.($i+1).'">'.$i.'-'.($i+1).'</option>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6" style="border-left: 1px solid;">

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Session');?></label>
                            <div class="col-sm-9">
                                <select name="to_session" id="to_session" class="form-control">
                                    <option value="01">JAN</option>
                                    <option value="07">JUL</option>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                            <div class="col-sm-9">
                                <select name="to_class_id" class="form-control" data-validate="required" id="to_class_id"
                                        data-message-required="<?php echo get_phrase('value_required');?>"
                                        onchange="return to_class(this.value)">
                                    <option value=""><?php echo get_phrase('select');?></option>
                                    <?php
                                    $classes = $this->db->get('class')->result_array();
                                    foreach($classes as $row):
                                        ?>
                                        <option value="<?php echo $row['class_id'];?>">
                                            <?php echo $row['name'];?>
                                        </option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
                            <div class="col-sm-9">
                                <select name="to_section_id" class="form-control" id="to_section_id">
                                    <option value=""><?php echo get_phrase('select_class_first');?></option>
                                </select>
                            </div>

                            <label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Year');?></label>
                            <div class="col-sm-9">
                                <select name="to_year" class="form-control" id="to_year">

                                </select>
                            </div>

                            <label class="col-sm-3 control-label"><?php echo get_phrase('Building');?></label>
                            <div class="col-sm-9">
                                <select id="building" name="building" class="form-control">
                                    <?php
                                    $buildings = $this->db->get('building')->result_array();
                                    foreach($buildings as $building):
                                        ?>
                                        <option value="<?php echo $building['id'];?>">
                                            <?php echo $building['building_name'];?>
                                        </option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <label class="col-sm-3 control-label"><?php echo get_phrase('Branch');?></label>
                            <div class="col-sm-9">
                                <select id="branch" name="branch" class="form-control">
                                    <?php
                                    $buildings = $this->db->get('branch')->result_array();
                                    foreach($buildings as $building):
                                        ?>
                                        <option value="<?php echo $building['branch_id'];?>">
                                            <?php echo $building['branch_name'];?>
                                        </option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
					</div>
                        <div class="col-sm-12 load">
                            <a id="load_student" href="javascript:void(0)">Load Student</a>
                        </div>
                </div>
                <div class="form-group" id="p_students">

                </div>
                <hr>
                <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('Promote Student');?></button>
						</div>
					</div>
                <?php //echo form_close();?>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	function from_class(class_id) {
        var session = $('#from_session').val();
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id + '/' + session,
            success: function(response)
            {
                jQuery('#from_section_id').html(response);
            }
        });

    }
    function to_class(class_id) {
        var session = $('#to_session').val();
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id + '/' + session,
            success: function(response)
            {
                jQuery('#to_section_id').html(response);
            }
        });

    }
    $(function(){
        var m = $('#from_session').val();
        var n = $('#to_session').val();
        var m_html = '';
        var n_html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    m_html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#from_year').html(m_html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    m_html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#from_year').html(m_html);
        }

        if(n=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    n_html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#to_year').html(n_html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    n_html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#to_year').html(n_html);
        }
    });

    $('#from_session').change(function(){
        var m = $('#from_session').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#from_year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#from_year').html(html);
        }
    });

    $('#to_session').change(function(){
        var m = $('#to_session').val();
        var html = '';
        if(m=="01"){
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'jan' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#to_year').html(html);
        }else{
            $('.s-year').find('option').each(function(){
                var t = $(this).html();
                if($(this).hasClass( 'july' )){
                    html += '<option value="'+t+'">'+t+'</option>';
                }
            });
            $('#to_year').html(html);
        }
    });

    $('#load_student').click(function(){
        var session = $('#from_session').val();
        var classs = $('#from_class_id').val();
        var section = $('#from_section_id').val();
        var year = $('#from_year').val();

        //Getting desired students
        $.ajax({
            url: '<?php echo base_url();?>index.php?admin/load_students/' + session + '/' + classs + '/' + section + '/' + year,
            success: function(response)
            {
                $('#p_students').empty();
                $('#p_students').html(response);
            }
        });
    });

    //Prevent form submit
    $("form").submit(function(e){

        var from_year = $('#from_year').val();
        var to_year = $('#to_year').val();

        var from_session = $('#from_session').val();
        var to_session = $('#to_session').val();

        if(from_session!=to_session){
            alert('Session need to be same');
            e.preventDefault();
        }

        var from_class_id = parseInt($('#from_class_id').val());
        var to_class_id = parseInt($('#to_class_id').val());
        var from_section_id = parseInt($('#from_section_id').val());
        var to_section_id = parseInt($('#to_section_id').val());

        if(from_class_id>=to_class_id){
            alert('To class need to be greater than from class');
            e.preventDefault();
        }

        var temp = from_year.split("-");
        from_year = parseInt(temp[0]);

        temp = to_year.split("-");
        to_year = parseInt(temp[0]);

        if(to_year<=from_year){
            alert('Promotion Year need to be greater');
            e.preventDefault();
        }
    });

</script>