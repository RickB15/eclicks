<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
                <nav class="nav nav-tabs flex-column">    
@if(isset($segment) && $segment === 'overview' )
                    <a class="nav-link active" href="<?= base_url('settings/overview'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/overview'); ?>">{{ ucwords(lang('overview')) }}
@endif
                    </a>

@if(isset($segment) && $segment === 'general' )
                    <a class="nav-link active" href="<?= base_url('settings/general'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/general'); ?>">{{ ucwords(lang('general')) }}
@endif
                    </a>

@if(isset($segment) && $segment === 'availability' )
                    <a class="nav-link active" href="<?= base_url('settings/availability'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/availability'); ?>">{{ ucwords(lang('availability')) }}
@endif
                    </a>

@if(isset($segment) && $segment === 'events' )
                    <a class="nav-link active" href="<?= base_url('settings/events'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/events'); ?>">{{ ucwords(lang('events')) }}
@endif
                    </a>

@if(isset($segment) && $segment === 'notifications' )
                    <a class="nav-link active" href="<?= base_url('settings/notifications'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/notifications'); ?>">{{ ucwords(lang('notifications')) }}
@endif
                    </a>

@if(isset($segment) && $segment === 'associated_calendars' )
                    <a class="nav-link active" href="<?= base_url('settings/associated-calendars'); ?>">
                        {{ ucwords(lang($segment)) }}
                        <span style="float: right;">
                            <i class="fas fa-arrow-right fa-1x"></i>
                        </span>
@else
                    <a class="nav-link" href="<?= base_url('settings/associated-calendars'); ?>">{{ ucwords(lang('associated_calendars')) }}
@endif
                    </a>
                </nav>