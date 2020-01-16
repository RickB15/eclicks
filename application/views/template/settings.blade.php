<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')

@section('content')
        <div class="container-fluid">        
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
                <div class="col-lg-3 col-12" id="side-nav">
                    @include('components.side_nav')
                </div>
                <div class="col-1 d-none d-lg-block">
                    <hr class="vr">
                </div>
                <div class="col">
                    <h2 class="h2 page-title"><?= ucwords(lang(str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]\s+/', '_', $segment)))) ?></h2>
 @yield('settings-content')
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>
@endsection