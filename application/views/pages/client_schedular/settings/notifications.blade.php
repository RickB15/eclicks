<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('notifications')))

@section('settings-content')
                <form method="post" action="#" class="form" onsubmit="return false;">
                    <?php
                        $times = array(lang('direct'), '24 '. lang('hours') . ' ' . lang('in advance'), '10 '. lang('minutes') . ' ' . lang('in advance'));
                        $ways = array(lang('mail'), lang('sms'));
                        foreach ($times as $time):
                    ?>
                    <h5 class="h5"><?= ucfirst($time); ?></h5>                    
                    <?php foreach ($ways as $way): ?>
                    <div id="<?= $time; ?>" class="form-group mb-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label for="<?= $time.'-'.$way; ?>-select" class="input-group-text"><?= ucfirst($way.' '.lang('template')); ?>:</label>
                            </div>
                            <select id="<?= $time.'-'.$way; ?>-select" name="<?= $time.'-'.$way; ?>-select" class="custom-select" aria-describedby="<?= $time.'-'.$way; ?>-select">
                                <!-- Generating dynamic value -->
                                <option selected="true" disabled="true"><?= ucfirst(lang('default_item'));?>...</option>
                                <!-- <?php foreach($notificationEmails as $value): ?>
                                    <option value="<?= $value['id']?>" <?= ($row_notifications['email_direct'] == $value['id'])?'selected="selected"':'';?> ><?= $value['name']?></option>
                                <?php endforeach; ?> -->
                            </select>
                            <div class="input-group-append">
                                <span id="<?= $time.'-'.$way; ?>-new" class="input-group-text">
                                    <a class="link" href="<?= base_url('eclicks/email'); ?>" target="_blank" ><?= strtolower(lang('new').' '.lang('template')); ?></a>
                                </span>
                            </div>
                        </div>
                        <small id="<?= $time.'-'.$way; ?>-help" class="form-text text-muted"><?= ucfirst(lang('choose_template').' '.$way); ?></small>
                    </div>
                    <?php endforeach; ?>
                    <?php foreach ($ways as $way): ?>                    
                    <div class="form-check form-check-inline">
                        <input id="<?= $time.'-'.$way; ?>-input" name="<?= $time.'-'.$way; ?>-input" class="form-check-input" type="checkbox" value="email" checked="checked">
                        <label for="<?= $time.'-'.$way; ?>-input" class="form-check-label"><?= ucfirst(lang('send').' '.$way); ?></label>
                    </div>
                    <?php endforeach; ?>
                    <hr class="hr">
                    <?php endforeach; ?>
                </form>
@endsection