<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo" style="">
            <a href="<?php echo base_url(); ?>">
                <img src="uploads/logo.png"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse" style="">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>

    <div style=""></div>	
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
        <?php
            $level = $_SESSION['level'];
        ?>

        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>

        <!-- STUDENT -->
        <?php if($level==1 || $level==10 || $level==11){?>
        <li class="<?php
        if ($page_name == 'student_add' ||
                $page_name == 'student_bulk_add' ||
                $page_name == 'student_information' ||
                $page_name == 'student_promotion' ||
                $page_name == 'building' ||
                $page_name == 'comment')
            echo 'opened active has-sub';
        ?> ">
            <a href="#">
                <i class="fa fa-group"></i>
                <span><?php echo get_phrase('student'); ?></span>
            </a>
            <ul>
                <!-- STUDENT ADMISSION -->
                <?php if($level==1 || $level==11){?>
                <li class="<?php if ($page_name == 'student_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_add">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_student'); ?></span>
                    </a>
                </li>
                <?php }?>

                <!-- STUDENT BULK ADMISSION -->
<!--                --><?php //if($level==1 || $level==10 || $level==11){?>
<!--                <li class="--><?php //if ($page_name == 'student_bulk_add') echo 'active'; ?><!-- ">-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?admin/student_bulk_add">-->
<!--                        <span><i class="entypo-dot"></i> --><?php //echo get_phrase('admit_bulk_student'); ?><!--</span>-->
<!--                    </a>-->
<!--                </li>-->
<!--                --><?php //}?>

                <!-- STUDENT PROMOTION -->
                <?php if($level==1 || true){?>
                <li class="<?php if ($page_name == 'student_promotion') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_promotion">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('Promotion'); ?></span>
                    </a>
                </li>
                <?php }?>

                <!-- BUILDING INFORMATION -->
                <?php if($level==1){?>
                <li class="<?php if ($page_name == 'building') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/building">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('building'); ?></span>
                    </a>
                </li>
                <?php }?>

                <!-- STUDENT INFORMATION -->
                <?php if($level==1 || $level==10 || $level==11){?>
                <li class="<?php if ($page_name == 'student_information') echo 'opened active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_information'); ?></span>
                    </a>
                    <ul>
                        <?php
                        $classes = $this->db->get('class')->result_array();
                        foreach ($classes as $row):
                            ?>
                            <li class="<?php if ($page_name == 'student_information' && $class_id == $row['class_id']) echo 'active'; ?>">
                                <a href="<?php echo base_url(); ?>index.php?admin/student_information/<?php echo $row['class_id']; ?>">
                                    <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php }?>

                <!-- COMMENT -->
                <?php if($level==1 || $level==10 || $level==11){?>
                <li class="<?php if ($page_name == 'comment') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/comment">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('Comment'); ?></span>
                    </a>
                </li>
                <?php }?>
            </ul>
        </li>
        <?php }?>

        <?php if($level==1 || $level==10 || $level==11){?>
        <!-- TEACHER -->
        <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/teacher">
                <i class="entypo-users"></i>
                <span><?php echo get_phrase('teacher'); ?></span>
            </a>
        </li>
        <?php }?>


        <?php if($level==1 || $level==10 || $level==11){?>
        <!-- PARENTS -->
        <li class="<?php if ($page_name == 'parent') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/parent">
                <i class="entypo-user"></i>
                <span><?php echo get_phrase('parents'); ?></span>
            </a>
        </li>
        <?php }?>


        <?php if($level==1){?>
        <!-- CLASS -->
        <li class="<?php
        if ($page_name == 'class' ||
                $page_name == 'section')
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-flow-tree"></i>
                <span><?php echo get_phrase('class'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'class') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/classes">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_classes'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'section') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/section">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_sections'); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <?php }?>


        <?php if($level==1 || $level==11){?>
        <!-- PAYMENT -->
        <li class="<?php
        if ($page_name == 'invoice' ||
            $page_name == 'branch' ||
            $page_name == 'payment' ||
            $page_name == 'fees')
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-credit-card"></i>
                <span><?php echo get_phrase('payment'); ?></span>
            </a>
            <ul>
                <?php if($level==1 || $level==11){?>
                <li class="<?php if ($page_name == 'invoice') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/invoice">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('take payment'); ?></span>
                    </a>
                </li>
                <?php }?>

                <?php if($level==1 || $level==11){?>
                <li class="<?php if ($page_name == 'payment') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/payment">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('give payment'); ?></span>
                    </a>
                </li>
                <?php }?>

                <?php if($level==1){?>
                <li class="<?php if ($page_name == 'fees') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/fees">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('fees'); ?></span>
                    </a>
                </li>
                <?php }?>

                <?php if($level==1){?>
                <li class="<?php if ($page_name == 'branch') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/branch">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('branch'); ?></span>
                    </a>
                </li>
                <?php }?>
            </ul>
        </li>
        <?php }?>

        <?php if($level==1 || $level==11){?>
        <!-- ACCOUNTING -->
        <li class="<?php
        if ($page_name == 'income' ||
                $page_name == 'expense' ||
                    $page_name == 'expense_category')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('accounting'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'income') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/income">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('income'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'expense') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'expense_category') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense_category">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense_category'); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <?php }?>

        <?php if($level==1 || $level==11 || $level==10){?>
            <!-- STUDENT REPORT -->
            <li class="<?php
            if ($page_name == 'all_student'
            || $page_name == 'class_section_wise_student'
            || $page_name == 'guardian_wise_student'
            || $page_name == 'gender_wise_student'
            || $page_name == 'leaved_student'
            || $page_name == 'building_wise_student'
                || $page_name == 'parent_report'
            )
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-book-open"></i>
                    <span><?php echo get_phrase('Student Report'); ?></span>
                </a>
                <ul>
                    <li class="<?php if ($page_name == 'all_student') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/all_student">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('All Student'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'class_section_wise_student') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/class_section_wise_student">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Class Section wise Student'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'guardian_wise_student') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/guardian_wise_student">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Guardian wise Student'); ?></span>
                        </a>
                    </li>
<!--                    <li class="--><?php //if ($page_name == 'gender_wise_student') echo 'active'; ?><!-- ">-->
<!--                        <a href="--><?php //echo base_url(); ?><!--index.php?admin/report/gender_wise_student">-->
<!--                            <span><i class="entypo-dot"></i> --><?php //echo get_phrase('Gender wise Student'); ?><!--</span>-->
<!--                        </a>-->
<!--                    </li>-->
                    <li class="<?php if ($page_name == 'parent_report') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/parent_report">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Parent'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'leaved_student') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/leaved_student">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Leaved Student'); ?></span>
                        </a>
                    </li>
<!--                    <li class="--><?php //if ($page_name == 'building_wise_student') echo 'active'; ?><!-- ">-->
<!--                        <a href="--><?php //echo base_url(); ?><!--index.php?admin/report/building_wise_student">-->
<!--                            <span><i class="entypo-dot"></i> --><?php //echo get_phrase('Building Wise Student'); ?><!--</span>-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </li>
        <?php }?>


        <?php if($level==1 || $level==11){?>
            <!-- PAYMENT REPORT -->
            <li class="<?php
            if ($page_name == 'daily_collection'
            || $page_name == 'vat_collection'
            || $page_name == 'all_session_collection'
            || $page_name == 'collection_summary'
            || $page_name == 'individual_student_collection'
            || $page_name == 'all_student_collection'
            || $page_name == 'paid_students'
            || $page_name == 'unpaid_students'
            || $page_name == 'cancel_receipt'
            || $page_name == 'refund_receipt'
            )
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-docs"></i>
                    <span><?php echo get_phrase('Payment Report'); ?></span>
                </a>
                <ul>
                    <li class="<?php if ($page_name == 'daily_collection') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/daily_collection">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Daily Collection'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'vat_collection') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/vat_collection">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('VAT Collection'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'all_session_collection') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/all_session_collection">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('All Session Collection'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'collection_summary') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/collection_summary">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Collection Summary'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'individual_student_collection') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/individual_student_collection">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Individual Student Collection'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'all_student_collection') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/all_student_collection">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('All Student Collection'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'paid_students') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/paid_students">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Paid Students'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'unpaid_students') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/unpaid_students">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Un-Paid Students'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'cancel_receipt') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/cancel_receipt">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Cancel Receipt'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'refund_receipt') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/report/refund_receipt">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('Refund Receipt'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <!-- MESSAGE -->
<!--        <li class="--><?php //if ($page_name == 'message') echo 'active'; ?><!-- ">-->
<!--            <a href="--><?php //echo base_url(); ?><!--index.php?admin/message">-->
<!--                <i class="entypo-mail"></i>-->
<!--                <span>--><?php //echo get_phrase('message'); ?><!--</span>-->
<!--            </a>-->
<!--        </li>-->

        <?php if($level==1){?>
        <!-- SETTINGS -->
        <li class="<?php
        if ($page_name == 'system_settings' ||
                $page_name == 'manage_language' ||
                $page_name == 'manage_admin' ||
                $page_name == 'sms_settings' ||
                $page_name == 'parent_occupation' ||
                $page_name == 'academic_fees'
        )
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-lifebuoy"></i>
                <span><?php echo get_phrase('settings'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/system_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('general_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'sms_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/sms_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('sms_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_language') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_language">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('language_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_admin') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_admin">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('Manage Admin'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'parent_occupation') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/parent_occupation">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('Parent Occupation'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'academic_fees') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/academic_fees">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('Academic Fees'); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <?php }?>


        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>

    </ul>

</div>