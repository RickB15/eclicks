<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', 'Login')

@section('content')
		<div class="form-login">	
			<?php 
				$attributes = array('class' => 'form', 'id' => 'login_form');
				echo form_open('auth/login_user',$attributes);
			?>
			<div class="row from-group">
				<label for="username"><span class="i-symbol"><i class="fa fa-user fa-1x"></i></span></label>
				<?php $data = array(
					'name' 			=> 'username',
					'id' 			=> 'username',
					'class'			=> 'input',
					'type'			=> 'text',
					'placeholder'	=> 'Username',
					'value'			=> set_value('username'),
					'autofocus'		=> 'true',
					'required'		=> 'true'
				);
				echo form_input($data);
				?>
				<span class="text-danger"><?php echo form_error('username'); ?></span>
			</div>
			<div class="row form-group">
				<label for="password"><span class="i-symbol"><i class="fa fa-lock fa-1x"></i></span></label>
				<?php $data = array(
					'name' 			=> 'password',
					'id' 			=> 'password',
					'class'			=> 'input',
					'type'			=> 'password',
					'placeholder'	=> 'Password',
					'value'			=> '',
					'required'		=> 'true'
				);
				echo form_input($data);
				?>
				<span class="text-danger"><?php echo form_error('password'); ?></span>
			</div>
			<div class="form-group form-submit">
				<?php $data = array(
					'id' 			=> 'submit',
					'name' 			=> 'submit',
					'class'			=> 'submit submit-button',
					'type'			=> 'submit',
					'alt'			=> 'submit'
				);
				$content = 
					'<span class="fa-stack fa-2x submit-icon h-m xy right">
						<i class="fa fa-circle fa-stack-2x i-background"></i>
						<i class="fas fa-arrow-right fa-stack-1x i-symbol"></i>
					</span>';
				echo form_button($data, $content);
				?>
			</div>
			<?php echo form_close(); ?>
			<div class="row">
				<!-- <a href="<?php base_url(); ?>register" class="pull-left">register</a> -->
				<!-- <a href="<?php base_url(); ?>reset_password" class="pull-right">forgot password</a> -->
			</div>
		</div>
@endsection