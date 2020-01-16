<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('share_publish')))

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
                <div class="col-12">
                    <h2 class="h2 page-title text-center"><?= ucfirst(lang('copy') . ' ' . lang('embed_code')); ?></h2>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupEmbedCode"><?= ucfirst(lang('choose_event')) ?></label>
                        </div>
                        <select class="custom-select" id="inputGroupEmbedCode" onchange="createTag()">
                            <?php if(!empty($events)): foreach ($events as $key => $event): ?>
                                <option value="<?= $event->title ?>" <?php if($key === 0){ echo 'selected="true"'; }?>><?= ucfirst($event->title); ?></option>
                            <?php endforeach; else: ?>
                                <option value="" selected="true" disabled="true"><?= ucfirst(lang('no_events')); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <code id="embed-code">
                        <div id="target" class="mb-3 embed-code">
                            <!-- js code -->
                        </div>
                    </code>
                    <button class="btn btn-warning float-right" onclick="copyCode()"><?= ucfirst(lang('copy')); ?></button>
                </div>
            </div>
        </div>
@endsection