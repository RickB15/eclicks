<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('overview')))

@section('settings-content')
                <div class="row">
                    <div class="col-md col-sm-12 overview">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-user fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('account')); ?>
                        </h4>
                        <ol class="overview-list">
                            <li class="overview-list__item">
                                <?php echo ucwords(lang('username')); ?>:&nbsp;
                                <?php echo (isset($cs_user['cs_username'])) ? ucfirst($cs_user['cs_username']) : ucfirst(lang('no_username'));?>
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucwords(lang('email')); ?>:&nbsp;
                                <?php echo (isset($cs_user['email'])) ? $cs_user['email'] : ucfirst(lang('no_email'));?>
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucwords(lang('company')); ?>:&nbsp;
                                <?php echo (isset($cs_user['company']) && !empty($cs_user['company'])) ? ucfirst($cs_user['company']) : '';?>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md col-sm-12 overview">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-edit fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('general_settings')); ?>
                        </h4>
                        <ol class="overview-list">
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('time_settings')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/general#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>                        
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('redirect_url')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/general#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md col-sm-12 overview">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-calendar-plus fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('events')); ?>
                        </h4>
                        <ol class="overview-list">
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('duration'));?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/events#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('title'));?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/events#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('description'));?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/events#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                        </ol>                    
                    </div>
                    <div class="col-md col-sm-12 overview">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-clock fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('availability')); ?>
                        </h4>
                        <ol class="overview-list">                     
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('slots')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/availability#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md col-sm-12 overview">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-bell fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('notifications')); ?>
                        </h4>
                        <ol class="overview-list">                     
                            <li class="overview-list__item">
                                <?php echo ucfirst(lang('direct')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/notifications#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucfirst('24 ' . lang('hours') . ' ' . lang('in_advance')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/notifications#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                            <li class="overview-list__item">
                                <?php echo ucfirst('10 ' . lang('minutes') . ' ' . lang('in_advance')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/notifications#'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                        </ol>
                    </div>
                    <div class="col-md col-sm-12 overview overview-last">
                        <h4 class="h4 overview-title">
                            <span class="overview-icon">
                                <i class="far fa-share-square fa-fw"></i>
                            </span>
                            <?php echo ucwords(lang('associated_calendars')); ?>
                        </h4>
                        <ol class="overview-list">
                            <li class="overview-list__item">
                                <?php echo ucwords(lang('accounts')); ?>&nbsp;
                                (<a class="link" href="<?= base_url('settings/associated-calendars'); ?>"><?php echo strtolower(lang('edit')); ?></a>)
                            </li>
                        </ol>
                    </div>
                </div>
@endsection