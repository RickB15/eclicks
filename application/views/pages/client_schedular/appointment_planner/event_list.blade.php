<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')
@section('title', ucwords(lang('event_list')))

@section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <h4 class='title'><?= ucfirst(lang('list_of_events').$cs_username); ?></h4>
                    <small class="sub-title"><?= ucfirst(lang('choose_to_continue')); ?></small>
                    <div id="accordion">
                        <?php foreach ($events as $key => $event): ?>
                        <ul class="list-group card">
                            <?php if( empty($event->description) ): ?>
                                <div class="list-group-item text-center text-primary card-header">
                                    <?= $event->title; ?>
                                    <div class="info-list-item">
                                        <a href="<?= base_url('appointment/'.url_title($cs_username).'/'.url_title($event->title,'dash',true)); ?>" class="btn btn-primary btn-ghost right"><?= ucfirst(lang('go_to') . ' ' . lang('event')); ?></a>
                                    </div>
                                </div>
                            <?php else: ?>
                            <button id="heading-<?= $key; ?>" class="list-group-item btn btn-link card-header" data-toggle="collapse" data-target="#collapse-<?= $key; ?>" aria-expanded="<?php if(!empty($event->description)){ echo 'true'; }else{ echo 'false'; } ?>"" aria-controls="collapse-<?= $key; ?>">
                                <?= $event->title; ?><small class="text-muted"> (<?= lang('click_me'); ?>)</small>
                                <div class="info-list-item">
                                    <a href="<?= base_url('appointment/'.url_title($cs_username).'/'.url_title($event->title,'dash',true)); ?>" class="btn btn-primary btn-ghost right"><?= ucfirst(lang('go_to') . ' ' . lang('event')); ?></a>
                                </div>
                            </button>
                            <?php endif; ?>
                            <?php if( !empty($event->description) ): ?>
                            <div id="collapse-<?= $key; ?>" class="collapse" aria-labelledby="heading-<?= $key; ?>" data-parent="#accordion">
                                <div class="card-body px-5">
                                    <?= $event->description; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
@endsection