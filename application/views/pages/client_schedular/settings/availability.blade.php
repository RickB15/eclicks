<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('availability')))

@section('settings-content')
                    <h5 class="h5 page-sub-title"><?= ucfirst(lang('drag_mouse')); ?></h5 class="h5 page-sub-title">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?= ucfirst(lang('recurring') . ' ' . lang('availability')); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="specific-tab" data-toggle="tab" href="#specific" role="tab" aria-controls="specific" aria-selected="false"><?= ucfirst(lang('date_specific') . ' ' . lang('availability')); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div id="schedular" class="schedular" >
                            <!-- schedular application by JS script -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="specific" role="tabpanel" aria-labelledby="specific-tab">
                            <div id="schedular-specific" class="schedular" >
                            <!-- schedular application by JS script -->
                            </div>
                        </div>
                    </div>
@endsection