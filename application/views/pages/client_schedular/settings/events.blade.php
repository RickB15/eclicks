<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('events')))

@section('settings-content')
                <div class="container">
                    <div class="row">
                        <div class="col col-md-6 col-lg-4 align-self-center text-center">
                            <button type="button" class="btn btn-add" data-toggle="modal" data-target="#<?= 'make'; ?>" data-backdrop="static" data-event-id="0">
                                <span class="btn-add__icon">                                
                                    <i class="fas fa-plus-circle fa-5x"></i>
                                </span>
                            </button><br>
                            <?php if( !isset($events) ){ echo ucfirst(lang('add') . ' ' . lang('first_event')); }else{ echo ucfirst(lang('add') . ' ' . lang('new_event')); } ?>
                        </div>
                        <?php if(isset($events)):
                        foreach ($events as $event): ?>
                        <div class="col col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <h5 class="card-header"><?= ucfirst($event->title); ?></h5>
                                <div class="card-body">
                                    <h6 class="card-title"><?= ucfirst(lang('duration') . ': ') ?></h6>
                                    <div class="duration-times">
                                        <?php
                                        $duration = explode(':', $event->duration);
                                        $hour_text = ((int)$duration[0] === 1) ? ' ' . lang('hour') : ' ' . lang('hours');
                                        if( (int)$duration[0] !== 0 && (int)$duration[1] !== 0 ){
                                            echo (int)$duration[0] . $hour_text . ' '. lang('and'). ' ' .
                                            (int)$duration[1] . ' '. lang('minutes');
                                        } else if( (int)$duration[0] === 0 ){
                                            echo (int)$duration[1] . strtolower(' '. lang('minutes'));
                                        } else {
                                            echo (int)$duration[0] . $hour_text;
                                        }
                                        ?>
                                    </div>
                                    <?php if( isset($event->description) && !empty($event->description) ): ?>
                                        <hr class="hr">
                                        <h6 class="h6"><?= ucfirst(lang('description') . ': ') ?></h6>
                                        <p class="card-text"><?= ucfirst($event->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-muted text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?= 'edit'; ?>" data-backdrop="static" data-event-id="<?= $event->event_id ?>">
                                        <?= ucfirst(lang('edit')); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
@endsection