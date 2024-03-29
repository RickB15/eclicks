<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <nav class="navbar navbar-dark navbar-main">        
            <a href="<?= base_url(); ?>" class='d-lg-block d-none' style="margin-right: -50px;">
                <img src="<?= IMGPATH ?>logos/navbar/eclicks-logo.png" width="50" height="50" alt="">
            </a>
            <div class="container">
                <div class="navbar-brand">
                    <h2 class="title">
@if(isset($pageName))
                        {{  ucwords(lang(str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]\s+/', '_', $pageName)))) }}
@if(isset($segment) && $segment !== 'index')
        • {{ ucwords(lang(str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]\s+/', '_', $segment)))) }}
@endif
@else
                        @yield('title', ucwords(lang('no_title')))
@endif
                    </h2>
                    <small class="title-sub d-md-block d-none">
@if($pageName === 'appointment_planner' && isset($segment) && $segment === 'index' && isset($cs_username) && !empty($cs_username))
                        <?= ucfirst(lang('make_appointment')); ?> <span id="username"><?= ucfirst($cs_username); ?></span>
@else
                        Sub title
@endif
                    </small>
                </div>
                <small class="text-muted application-name">
@if(isset($appName))
                    {{ $appName }}
@endif
                </small>
                <ul class="navbar-lang">
                    <span class="flag-icon">
                        <a href="<?= base_url('change-language/nl'); ?>" class="flag-icon__link">
                            <img src="<?= IMGPATH ?>language-flags/dutch.png" class="flag-icon__nl" alt="NL" />
                        </a>
                    </span>
                    <span class="flag-icon">
                        <a href="<?= base_url('change-language/en'); ?>" class="flag-icon__link">
                            <img src="<?= IMGPATH ?>language-flags/english.png" class="flag-icon__en" alt="EN" />
                        </a>
                    </span>
                </ul>
            </div>
        </nav>
@if(isset($path) && isset($access) && isset($appName) && (strtolower($path) !== 'auth' && strtolower($path) !== 'policies' && strtolower($path) !== 'conditions') && $access !== 'public' && $appName === 'Client Schedular')
        <nav class="navbar navbar-expand-lg navbar-dark navbar-second">
            <div class="container">
                <a href="<?= base_url(); ?>" class='d-lg-none'>
                    <img src="<?= IMGPATH ?>logos/navbar/eclicks-logo.png" width="40" height="40" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('appointment/'.$cs_username); ?>" target="_blank">
                                <span class="nav-icon">
                                    <i class="far fa-calendar-alt fa-2x"></i>
                                </span>
                            </a>
                        </li>
@if(isset($pageName) && $pageName === 'activities' )
                        <li class="nav-item active">
@else
                        <li class="nav-item">
@endif
                            <a class="nav-link" href="<?= base_url('activities'); ?>"><?= ucwords(lang('activities')); ?></a>
                        </li>
@if(isset($pageName) && $pageName === 'settings' )
                        <li class="nav-item active">
@else
                        <li class="nav-item">
@endif
                            <a class="nav-link" href="<?= base_url('settings'); ?>"><?= ucwords(lang('settings')); ?></a>
                        </li>
@if(isset($pageName) && $pageName === 'share & publish' )
                        <li class="nav-item active">
@else
                        <li class="nav-item">
@endif
                            <a class="nav-link" href="<?= base_url('share'); ?>"><?= ucwords(lang('share_publish')); ?></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout_user'); ?>"><?= ucwords(lang('logout')); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
@endif