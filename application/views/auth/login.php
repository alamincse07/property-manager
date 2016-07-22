<?php $this->load->view('auth/header') ?>

<div class="loginFormInner">
    <div class="loginTitle"><?php echo lang('login_heading'); ?></div>
    <?php if ($message) { ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $message; ?>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $success; ?>
        </div>
    <?php } ?>
    <?php echo form_open("auth/login"); ?>
    <?php
    $identity = array_merge($identity, array('placeholder' => lang('login_identity_label'), 'class' => 'span5'));
    echo form_input($identity);
    ?>
    <?php
    $password = array_merge($password, array('placeholder' => 'Password', 'class' => 'span5'));
    echo form_input($password);
    ?>
    <p class="forgotP">Password  private, ask admin to get a password</p>
    <!--<p class="forgotP"><a href="forgot_password"><?php /*echo lang('login_forgot_password'); */?></a></p>-->
    <label class="checkbox">
        <?php
        echo form_checkbox('remember', '1', FALSE, 'id="remember"');
        echo lang('login_remember_label', 'remember')
        ?>
    </label> 
    <div class="loginButton">
        <a href="<?php echo site_url() ?>/register" class="btn btn-success"><?php echo lang('login_singup'); ?></a> 
        <?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-info span2"'); ?>
    </div>
    <?php echo form_close(); ?>  
</div>

<?php $this->load->view('auth/footer') ?>