<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function set_register_validations()
{
	$user_vali = array(
		$email_vali = array('field' => 'email', 'lable' => 'Email', 'rules' => 'trim|required|valid_email'),
		$password_vali = array('field' => 'password', 'lable' => 'Password', 'rules' => 'trim|required|min_length[3]'), //TODO change back to 8 length
		$passwordconfirm_vali = array('field' => 'password_confirm', 'lable' => 'Password Confirmation', 'rules' => 'trim|required|matches[password]'),
		$initials_vali = array('field' => 'initials', 'lable' => 'Initials', 'rules' => 'trim|required'),
		$firstname_vali = array('field' => 'first_name', 'lable' => 'FirstName', 'rules' => 'trim|required|alpha'),
		$prefix_vali = array('field' => 'prefix', 'lable' => 'Prefix', 'rules' => 'trim|alpha'),
		$lastname_vali = array('field' => 'last_name', 'lable' => 'LastName', 'rules' => 'trim|required|alpha'),
		$mobile_vali = array('field' => 'mobile', 'lable' => 'Mobile', 'rules' => 'trim'),
		$address_vali = array('field' => 'address', 'lable' => 'Address', 'rules' => 'trim|required|alpha'),
		$housenumber_vali = array('field' => 'house_number', 'lable' => 'HouseNumber', 'rules' => 'trim|required|numeric'),
		$addition_vali = array('field' => 'addition', 'lable' => 'Addition', 'rules' => 'trim|alpha_numeric'),
		$zipcode_vali = array('field' => 'zipcode', 'lable' => 'Zipcode', 'rules' => 'trim|required|alpha_numeric_spaces|max_length[7]'),
		$city_vali = array('field' => 'city', 'lable' => 'City', 'rules' => 'trim|required|alpha'),
		$country_vali = array('field' => 'country', 'lable' => 'Country', 'rules' => 'trim|required|alpha'),
		$company_name_vali = array('field' => 'company_name', 'lable' => 'CompanyName', 'rules' => 'trim|alpha'),
		$company_phone_vali = array('field' => 'company_phone', 'lable' => 'CompanyPhone', 'rules' => 'trim'),
	);

	return $user_vali;
}

function set_login_validations($username)
{
	if( strpos($username, '@') !== FALSE )
	{
		$username_vali = array('field' => 'username', 'lable' => 'Email', 'rules' => 'trim|required|valid_email');
	}
	else
	{
		$username_vali = array('field' => 'username', 'lable' => 'Username', 'rules' => 'trim|required');
	}
	$password_vali = array('field' => 'password', 'lable' => 'Password', 'rules' => 'trim|required');

	return array($username_vali, $password_vali);
}

function setErrorDelimiters()
{
	$delimiters_prefix = '<div class="alert-danger">';
	$delimiters_suffix = '</div>';
	return array($delimiters_prefix, $delimiters_suffix);
}

function setErrorMessages()
{

}