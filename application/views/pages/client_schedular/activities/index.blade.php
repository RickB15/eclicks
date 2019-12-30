<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('activities')))

@section('content')
        <div class="container">
        <?php if( isset($availability_redirect) || isset($events_redirect) ): ?>
            <div class="row justify-content-center" style="margin-top: -2rem; margin-bottom: 2rem;">
                <?php if( isset($availability_redirect) ): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= ucfirst(lang('availability_not_set_error')); ?>
                    <a class="alert-link" href="<?= base_url($availability_redirect); ?>"><?= ucfirst(lang('go_to').' '.lang('availability')); ?></a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                <?php if( isset($events_redirect) ): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= ucfirst(lang('events_not_set_error')); ?>
                    <a class="alert-link" href="<?= base_url($events_redirect); ?>"><?= ucfirst(lang('go_to').' '.lang('events')); ?></a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>            
            <div class="row">
                <div class="col justify-content-right">
                    <h2 class="page-title"><?= ucwords(lang($pageName)); ?></h2>
                    <form class="form-inline" onsubmit="return false;">
                        <div class="input-group search-bar">
                            <input class="form-control search-bar__input" type="search" placeholder="<?= strtolower(lang('search')) ?>..." title="<?= ucfirst(lang('search_for_title')); ?>" aria-label="<?= strtolower(lang('search')) ?>" onkeyup="search(this)" onsearch="searchChange(this)">
                            <div class="input-group-append search-bar__nav">
                                <button id="serach-icon" class="input-group-text btn">
                                    <span class="search-icon">
                                        <i class="fas fa-search fa-1x"></i>
                                    </span>
                                </button>
                                <!-- <button id="filter-icon" class="input-group-text btn" onclick="filterOn(this)" title="<?= ucfirst(lang('filter_on_time')); ?>">
                                    <span class="fiter-icon">
                                        <i class="fas fa-filter"></i>
                                    </span>
                                    <span class="time-icon">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                </button> -->
                            </div>
                        </div>
                    </form>
                    <div class="list-group">
                        <div id="upcoming-bar" class="upcoming-bar">
                            <?= ucwords(lang('upcoming').' '.lang('events')); ?>
                            <button class="btn float-right upcoming-bar__button">
                                <span class="upcoming-bar__button--icon">
                                    <i id="icon-upcoming" class="fa fa-minus"></i>
                                </span>
                            </button>
                        </div>
                        <ul id="upcoming-list" class="list-group item-list upcoming-list">
                            <div id="accordion-upcoming" class="accordion">
                            <?php if(isset($upcoming_activities)):
                                foreach($upcoming_activities as $activity){
                                    echo createList($activity, $host, 'upcoming');
                                }else: ?>
                                <span id="no-upcoming"><?= ucfirst(lang('no_').' '.lang('upcoming').' '.lang('events')); ?></span><!-- will be hidden when list can be showed -->
                            <?php endif; ?>
                            </div>
                        </ul>
                        <div id="previous-bar" class="previous-bar">
                            <?= ucwords(lang('previous').' '.lang('events')); ?>
                            <button class="btn float-right previous-bar__button">
                                <span class="previous-bar__button--icon">
                                    <i id="icon-previous" class="fa fa-plus"></i>
                                </span>
                            </button>
                        </div>
                        <ul id="previous-list" class="list-group item-list previous-list hidden">
                            <div id="accordion-previous" class="accordion">
                            <?php if(isset($previous_activities)):
                                foreach($previous_activities as $activity){
                                    echo createList($activity, $host, 'previous');
                                }else: ?>
                                <span id="no-previous"><?= ucfirst(lang('no_').' '.lang('previous').' '.lang('events')); ?></span><!-- will be hidden when list can be showed -->
                            <?php endif; ?>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection

<?php
function createList(Array $activity, $host, $type) {
    ob_start(); ?>
    <li class="list-group-item flex-column align-items-start searchable collapsed" type="button" data-toggle="collapse" data-target="#details-<?= $activity['id'] ?>" aria-expanded="false" aria-controls="details-<?= $activity['id'] ?>">
        <div class="row">
            <div class="col-3 col-lg-1 text-center">
                <span class="fa-stack fa-1x text-center">
                    <i class="far fa-calendar fa-stack-2x calendar-icon"></i>
                    <strong class="fa-stack-1x calendar-icon-text"><?= $activity['day'] ?></strong>
                </span>
            </div>
            <div class="col-9 d-lg-none">
                <h5 class="mb-0 d-inline"><?= ucfirst($activity['name']); ?>, </h5>
                <small><?= $activity['email']; ?></small>
                <h5 class="mb-0 d-lg-none"><?= ucfirst($activity['title']); ?></h5>
                <small class="d-lg-none"><?= $activity['date'] . ', ' . $activity['start_time'] . ' - ' . $activity['end_time']; ?></small>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <h5 class="mb-0"><?= ucfirst($activity['name']); ?></h5>
                <small><?= $activity['email']; ?></small>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <h5 class="mb-0"><?= ucfirst($activity['title']); ?></h5>
                <small><?= $activity['date'] . ', ' . $activity['start_time'] . ' - ' . $activity['end_time']; ?></small>
            </div>
            <div class="col-lg-2 text-right d-none d-lg-block">
                <h5 id="status" class="header-status mb-0"><?= ucfirst(lang('status').':'); ?></h5>
                <small class="<?php 
                    switch ($activity['status']) {
                        case 'confirm':
                            echo 'text-primary';
                            break;
                        case 'confirmed':
                            echo 'text-success';
                            break;
                        case 'canceled':
                            echo 'text-muted';
                            break;
                        default:
                            echo 'text-danger';
                            break;
                    }
                ?>"
                ><?= ucfirst(lang($activity['status'], false)); ?></small>
            </div>
        </div>
        <span style="position: absolute; right: 5px; top: 0;"><?= ucfirst('in ' . $activity['from_now'] . ' '.lang('days')) ?></span>
    </li>

    <div id="details-<?= $activity['id'] ?>" class="details collapse" aria-labelledby="heading-<?= $activity['id'] ?>" data-parent="#accordion-<?= $type ?>">
        <div class="details__content">
            <div class="row">
                <i class='w-100 text-center'><?= ucfirst(lang('appointment').' '.lang('details')) ?></i>    
                <div class="col-12">
                    <h5 class="h5 content-title"><?= ucfirst(lang('details')); ?></h5>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('title')); ?>:&nbsp;</strong>
                        <span id="appointment-title"><?= ucfirst($activity['title']); ?></span>
                    </p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('date')); ?>:&nbsp;</strong>
                        <span id="appointment-date"><?= ucfirst($activity['date']); ?></span>
                    </p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('time')); ?>:&nbsp;</strong>
                        <span id="appointment-time"><?= $activity['start_time'] . ' - ' . $activity['end_time']; ?></span>
                    </p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('duration')); ?>:&nbsp;</strong>
                        <span id="appointment-duration"><?= $activity['duration'] .' '. lang('hours'); ?></span>
                    </p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('status')); ?>:&nbsp;</strong>
                        <span id="appointment-status" class="<?php 
                            switch ($activity['status']) {
                                case 'confirm':
                                    echo 'text-primary';
                                    break;
                                case 'confirmed':
                                    echo 'text-success';
                                    break;
                                case 'canceled':
                                    echo 'text-muted';
                                    break;
                                default:
                                    echo 'text-danger';
                                    break;
                            }
                        ?>"><?= ucfirst(lang($activity['status'], false)); ?></span>
                    </p>
                </div>
                <div class="col-12 mt-4">
                    <h5 class="h5 content-title"><?= ucfirst(lang('host')); ?></h5>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('name')); ?>:&nbsp;</strong>
                        <span id="host-email"><?= ucfirst($host['name']); ?></span>
                    </p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('email')); ?>:&nbsp;</strong>
                        <span id="host-email"><?= $host['email']; ?></span>
                    </p>
                </div>
                <div class="col-12 mt-4">
                    <h5 class="h5 content-title"><?= ucfirst(lang('appointment').' '.lang('description')); ?></h5>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('description'));?>:&nbsp;</strong>
                        <span id="description"><?= ucfirst($activity['description']); ?></span>
                    </p>
                </div>
                <div class="col-12 mt-4">
                    <h5 class="h5 content-title"><?= ucfirst(lang('attendee')); ?></h5>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('name')); ?>:&nbsp;</strong>
                        <span id="name"><?= ucfirst($activity['name']); ?></span></p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('email')); ?>:&nbsp;</strong>
                        <span id="email"><?= $activity['email']; ?></span></p>
                    <p class="content-item">
                        <strong><?= ucfirst(lang('phone')); ?>:&nbsp;</strong>
                        <span id="phone"><?= $activity['phone']; ?></span></p>
                </div>                                            
                <div class="col-12 text-right">
                    <button class="btn btn-danger mt-3" role="button">
                        <?= ucfirst(lang('cancel').' '.lang('appointment')) ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="details__footer">
            <div class="row">
                <div class="col footer-item">
                    <small><?= ucfirst(lang('created'));?>:&nbsp;
                        <span id="date-made"><?= $activity['created']; ?></span>
                    </small>
                </div>
                <div class="col footer-item">
                    <small><?= ucfirst(lang('tracking_id')); ?>:&nbsp;
                        <span id="tracking-id"><?= $activity['id']; ?></span>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
} ?>