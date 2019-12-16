<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('appointment_made')))

@section('content')
        <div class="container ">
            <div class="jumbotron">
                <div class="text-center">
                    <h1 class="display-4"><?= ucfirst(lang('thank_you')); ?></h1>
                    <p class="lead"><?= ucfirst(lang('appointment_has_been_made')) ?></p>
                    <hr class="my-4">
                    <i><?= ucfirst(lang('the_details')); ?></i>
                </div>
                <div id="details" class="details px-5">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="h5 content-title"><?= ucfirst(lang('appointment')); ?></h5>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('name')); ?>:&nbsp;</strong>
                                <span id="appointment-title"><?= ucfirst($details['event']); ?></span>
                            </p>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('date')); ?>:&nbsp;</strong>
                                <span id="appointment-date"><?= $details['appointment']['date']; ?></span>
                            </p>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('start_time')); ?>:&nbsp;</strong>
                                <span id="appointment-start-time"><?= explode(':', $details['appointment']['start_time'])[0] . ':' .  explode(':', $details['appointment']['start_time'])[1]; ?></span>
                            </p>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('end_time')); ?>:&nbsp;</strong>
                                <span id="appointment-end-time"><?= explode(':', $details['appointment']['end_time'])[0] . ':' .  explode(':', $details['appointment']['end_time'])[1]; ?></span>
                            </p>
                        </div>
                        <div class="col-12 mt-4">
                            <h5 class="h5 content-title"><?= ucfirst(lang('host')); ?></h5>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('name')); ?>:&nbsp;</strong>
                                <span id="host-email"><?= ucfirst($details['host']); ?></span>
                            </p>
                        </div>
                        <div class="col-12 mt-4">
                            <h5 class="h5 content-title"><?= ucfirst(lang('attendee')); ?></h5>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('name')); ?>:&nbsp;</strong>
                                <span id="name"><?= ucfirst($details['attendee']['name']); ?></span></p>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('email')); ?>:&nbsp;</strong>
                                <span id="email"><?= $details['attendee']['email']; ?></span></p>
                            <p class="content-item">
                                <strong><?= ucfirst(lang('phone')); ?>:&nbsp;</strong>
                                <span id="phone"><?= $details['attendee']['phone']; ?></span></p>
                        </div>                                            
                        <div class="col-12 text-right">
                            <a class="btn btn-primary mt-3" role="button" href="<?= $referred_from; ?>">
                                <?= ucfirst(lang('make_another')); ?>
                            </a>
                        </div>
                    </div>
                </div>       
            </div>
        </div>
@endsection