<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('calendar')))

@section('content')
        <!-- Loader -->
        <div id="loader" class="loader loader-default"></div>
        <div class="container">
            <div class="row justify-content-center">
            <pre id="content" style="white-space: pre-wrap;"></pre>
                <div class="col-12 col-lg-10">
                    <h4 id="header-step-1" class="text-center"><?= ucfirst(lang('choose_date_and_time')); ?></h4>
                    <h4 id="header-step-2" class="text-center hidden"><?= ucfirst(lang('give_your_details')); ?></h4>

                    <div id="target-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="progress">
                                    <div id="progress-bar" class="progress-bar progress-bar--animation__to-half" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        <span class="sr-only">50% <?= ucfirst(lang('complete')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="v-cal">
                            <div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="false">
                                <div id="carousel-nav-top" class="carousel-nav">
                                    <!-- button from JS script if next is clicked -->
                                </div>

                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div id="step-1" class="text-center">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>
                                                        <span id="summary"><?= ucfirst(lang('appointment') . ': ') . $event->title; ?></span>
                                                        <?php
                                                            if( !empty($event->description) ):
                                                        ?>
                                                            <span id="description" class="d-inline-block" tabindex="0" data-toggle="popover"
                                                                title="<?= ucfirst(lang('description'). ':'); ?>"
                                                                data-content="<?= $event->description; ?>"
                                                                data-trigger="focus"
                                                                data-placement="bottom"
                                                            >
                                                                <span class="badge badge-pill badge-info" style="cursor: pointer;">
                                                                    <i class="fas fa-info-circle fa-sm"></i>
                                                                </span>
                                                            </span>
                                                        <?php
                                                            endif;
                                                        ?>
                                                    </h5>
                                                    <div class="vcal-header">
                                                        <button class="vcal-btn" data-calendar-toggle="previous">
                                                            <svg height="24" version="1.1" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path>
                                                            </svg>
                                                        </button>

                                                        <div class="vcal-header__label" data-calendar-label="month">
                                                            <?= date('F Y'); ?>

                                                        </div>

                                                        <button class="vcal-btn" data-calendar-toggle="next">
                                                            <svg height="24" version="1.1" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div id="vcal-week" class="vcal-week">
                                                        <span class="day"><?= ucfirst(lang('mon')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('tue')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('wed')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('thu')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('fri')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('sat')); ?></span>
                                                        <span class="day"><?= ucfirst(lang('sun')); ?></span>
                                                    </div>
                                                    <div id="vcal-body" class="vcal-body" data-calendar-area="month">
                                                        <!-- application from JS script -->
                                                    </div>
                                                </div>
                                                <div id='vcal-times-box' class="col-12 col-lg-4 text-left hidden">
                                                    <h5 class='text-center'><?php
                                                        $duration = explode(':', $event->duration);
                                                        $hour_text = ((int)$duration[0] === 1) ? ' ' . lang('hour') : ' ' . lang('hours');
                                                        if( (int)$duration[0] !== 0 && (int)$duration[1] !== 0 ){
                                                            $duration_text = (int)$duration[0] . $hour_text . ' ' . lang('and') . ' ' .
                                                            (int)$duration[1] . ' ' . lang('minutes');
                                                        } else if( (int)$duration[0] === 0 ){
                                                            $duration_text = (int)$duration[1] . strtolower(' ' . lang('minutes'));
                                                        } else {
                                                            $duration_text = (int)$duration[0] . $hour_text;
                                                        }
                                                        echo ucfirst(lang('duration') . ': ') . $duration_text;
                                                    ?>
                                                    </h5>
                                                    <div id="vcal-times" class="vcal-times" time-calendar-area="day">
                                                        <!-- application from JS script -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="carousel-item">
                                        <div id="step-2">
                                            
                                        </div>
                                    </div>
                                </div>

                                <div id="carousel-nav-bottom" class="carousel-nav text-right">
                                    <!-- button from JS script if time is clicked -->
                                </div>
                            </div>			
                        </div>		
                    </div>				
                    <p class="demo-picker">
                        <?= ucfirst(lang('date_picked')); ?>:

                        <strong>
                            <span id="picked-date" class="demo-picker__picked d-inline" data-calendar-label="picked-date"><?= strtolower(lang('none')); ?></span>
                            <span id="picked-time" class="demo-picker__picked d-inline" data-calendar-label="picked-time"></span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>
@endsection