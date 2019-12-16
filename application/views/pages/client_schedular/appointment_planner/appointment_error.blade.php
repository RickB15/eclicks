<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('appointment_error')))

@section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <h4><?= $error_msg; ?></h4>
                </div>
            </div>
        </div>
@endsection