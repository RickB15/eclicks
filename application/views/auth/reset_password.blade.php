<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', 'Reset Password')

@section('content')
		<div class="m-screen">
			<?php 
				$attributes = array('class' => 'form login_form', 'id' => 'login_form');
				echo form_open('auth/reset_user_password',$attributes);
			?>
			<div class="row from-group">
				<label for="email"><span><i class="fa fa-envelope fa-1x i-symbol"></i></span></label>
				<?php $data = array(
					'id' 			=> 'email',
					'name' 			=> 'email',
					'class'			=> 'input',
					'type'			=> 'email',
					'placeholder'	=> 'email',
					'value'			=> set_value('email'),
					'autofocus'		=> 'true',
					'required'		=> 'true'
				);
				echo form_input($data);
				?>
				<span class="text-danger"><?php echo form_error('username'); ?></span>
			</div>
			<div class="form-group">
				<label for="submit" class="submit-button h-m xy right">
					<span class="fa-stack fa-1x submit-icon">
						<i class="fa fa-circle fa-stack-2x i-background"></i>
						<i class="fas fa-arrow-right fa-stack-1x i-symbol"></i>
					</span>
				</label>
				<?php $data = array(
					'id' 			=> 'submit',
					'name' 			=> 'submit',
					'class'			=> 'submit',
					'type'			=> 'submit',
					'alt'			=> 'submit',
					'hidden'		=> 'true'
				);
				echo form_input($data);
				?>
			</div>
			<?php echo form_close(); ?>
			<div class="row">
				<a href="<?php base_url() ?>login" class="pull-left">login</a>
			</div>
		</div>
@endsection