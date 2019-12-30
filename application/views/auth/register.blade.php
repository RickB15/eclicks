<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', 'Register')

@section('content')
		<div class="form-register">
			<?php 
				$attributes = array('class' => 'form', 'id' => 'register_form');
				echo form_open('auth/register_user',$attributes);
			?>
			<div class="row">
				<div class="col-md-3">
					<div class="row">
						<div class="text-center">
							Welkom
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-2">
							<div class="row text-center">
								User
							</div>
						</div>
						<div class="col-md-10">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label for="username"><span><i class="fa fa- fa-xs i-symbol"></i></span></label>
										<input id="username" class="input" type="username" name="username" placeholder="username" value="<?php echo set_value('username'); ?>" required="true" />
										<?php echo form_error('username'); ?>
										<label for="email"><span><i class="fa fa- fa-xs i-symbol"></i></span></label>
										<input id="email" class="input" type="email" name="email" placeholder="email" value="<?php echo set_value('email'); ?>" required="true" />
										<?php echo form_error('email'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="row text-center">
								Password
							</div>
						</div>
						<div class="col-md-10">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label for="password"><span><i class="fa fa-lock fa-xs i-symbol"></i></span></label>
										<input id="password" class="input" type="password" name="password" placeholder="password" value="" required="true" />
										<?php echo form_error('password'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<input id="password_confirm" class="input" type="password" name="password_confirm" placeholder="confirm password" value="" required="true" />
										<?php echo form_error('password_confirm'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid-md-w4">
				<div class="gcol c2-w3">
					<div class="row">
						<div class="grid-xs-w1 grid-md-w5">
							<div class="gcol c1-w1 text-center">
								User
							</div>
							<div class="gcol c2-w2 form-group">
								
							</div>
							<div class="gcol c4-w2 form-group">
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="grid-xs-w1 grid-md-w5">
							<div class="gcol c1-w1 text-center">
								Password
							</div>
							<div class="gcol c2-w2 form-group">
								
							</div>
							<div class="gcol c4-w2 form-group">
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<input id="initials" class="input" type="text" name="initials" placeholder="initials" value="<?php echo set_value('initials'); ?>" required="true" />
							<?php echo form_error('initials'); ?>
						</div>
						<div class="grid-xs-w1 grid-md-w5">
							<div class="gcol c1-w1 text-center">
								Name
							</div>
							<div class="gcol c2-w2 form-group">
								<label for="first_name"><span><i class="fa fa- fa-xs i-symbol"></i></span></label>
								<input id="first_name" class="input" type="text" name="first_name" placeholder="first name" value="<?php echo set_value('first_name'); ?>" required="true" />
								<?php echo form_error('first_name'); ?>
							</div>
							<div class="gcol c4-w1 form-group">
								<input id="prefix" class="input" type="text" name="prefix" placeholder="prefix" value="<?php echo set_value('prefix'); ?>" />
								<?php echo form_error('prefix'); ?>
							</div>
							<div class="gcol c4-w2 form-group">
								<input id="last_name" class="input" type="text" name="last_name" placeholder="last name" value="<?php echo set_value('last_name'); ?>" required="true" />
								<?php echo form_error('last_name'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label for="address"><span><i class="fa fa- fa-xs i-symbol"></i></span></label>
				<input id="address" class="input" type="text" name="address" placeholder="address" value="<?php echo set_value('address'); ?>" required="true" />
				<?php echo form_error('address'); ?>

				<input id="house_number" class="input" type="text" name="house_number" placeholder="house number" value="<?php echo set_value('house_number'); ?>" required="true" />
				<?php echo form_error('house_number'); ?>

				<input id="addition" class="input" type="text" name="addition" placeholder="addition" value="<?php echo set_value('addition'); ?>" />
				<?php echo form_error('addition'); ?>
			</div>
			<div class="row form-group">
				<input id="zipcode" class="input" type="text" name="zipcode" placeholder="zipcode" value="<?php echo set_value('zipcode'); ?>" required="true" />
				<?php echo form_error('zipcode'); ?>
			</div>
			<div class="row form-group">
				<input id="city" class="input" type="text" name="city" placeholder="city" value="<?php echo set_value('city'); ?>" required="true" />
				<?php echo form_error('city'); ?>
			</div>
			<div class="row form-group">
				<input id="country" class="input" type="text" name="country" placeholder="country" value="<?php echo set_value('country'); ?>" required="true" />
				<?php echo form_error('country'); ?>
			</div>
			<div class="row form-group">
				<input id="mobile" class="input" type="number" name="mobile" placeholder="mobile" value="<?php echo set_value('mobile'); ?>" />
				<?php echo form_error('mobile'); ?>
			</div>	
			<div class="row form-group">
				<input id="company_name" class="input" type="text" name="company_name" placeholder="company_name" value="<?php echo set_value('company_name'); ?>" />
				<?php echo form_error('company_name'); ?>
			</div>	
			<div class="row form-group">
				<label for="company_phone"><span><i class="fa fa- fa-xs i-symbol"></i></span></label>
				<input id="company_phone" class="input" type="number" name="company_phone" placeholder="Company Phone" value="<?php echo set_value('company_phone'); ?>" />
				<?php echo form_error('company_phone'); ?>
			</div>	
			<div class="row form-group">
				<label for="gender">Man</label>
				<input id="gender-man" class="input" type="checkbox" name="gender-man" value="true">
				<span class="text-danger"><?php echo form_error('gender_man'); ?></span>
				<label for="gender">Woman</label>
				<input id="gender-woman" class="input" type="checkbox" name="gender-woman" value="true">
				<span class="text-danger"><?php echo form_error('gender_woman'); ?></span>
				<label for="gender">Other</label>
				<input id="gender-other" class="input" type="checkbox" name="gender-other" value="true">
				<span class="text-danger"><?php echo form_error('gender_other'); ?></span>
			</div>
			<div class="row form-group">
				<label for="submit" class="submit-button h-m xy right">
					<span class="fa-stack fa-2x submit-icon">
						<i class="fa fa-circle fa-stack-2x i-background"></i>
						<i class="fas fa-arrow-right fa-stack-1x i-symbol"></i>
					</span>
				</label>
				<input id="submit" class="submit" type="submit" name="submit" alt="submit" hidden="true" />
			</div>
			<?php echo form_close(); ?>
			<div class="row">
				<a href="<?php base_url() ?>login" class="pull-left">login</a>
			</div>
		</div>
@endsection