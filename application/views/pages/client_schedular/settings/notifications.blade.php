<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('notifications')))

@section('settings-content')
                <form method="post" action="#" class="form" onsubmit="return false;">
                    <?php
                        $times = array('direct', '24', '10');
                        $ways = array('email', 'sms');
                        foreach ($times as $time):
                    ?>
                    <h5 class="h5">
                    <?php 
                        if( $time === 'direct' ){
                            echo ucfirst(lang($time)); 
                        } else {
                            echo ucfirst($time.' '.lang('hours').' '.lang('in_advance'));
                        }
                    ?>
                    </h5>                    
                    <?php foreach ($ways as $way): ?>
                    <div id="<?= $time.'-'.$way; ?>" class="form-group mb-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label for="<?= $time.'-'.$way; ?>-select" class="input-group-text"><?= ucfirst(lang($way).' '.lang('template')); ?>:</label>
                            </div>
                            <select id="<?= $time.'-'.$way; ?>-select" name="<?= $time.'-'.$way; ?>-select" class="custom-select" aria-describedby="<?= $time.'-'.$way; ?>-select" onchange="autoSubmit(this)">
                                <!-- Generating dynamic value -->
                                <option selected="true" disabled="true"><?= ucfirst(lang('select_item'));?>...</option>
                                <?php 
                                if(isset(${'notification_'.$way})):
                                    foreach(${'notification_'.$way} as $value): 
                                        if( !empty($value['id']) ): ?>
                                            <option value="<?= $value['id']?>" <?php
                                            if( isset($notifications_settings->{$way.'_'.$time})
                                            && (int)$notifications_settings->{$way.'_'.$time} === (int)$value['id'] ){
                                                echo 'selected="true"';
                                            }
                                            ?>><?= $value['name']?></option>
                                    <?php endif;
                                    endforeach; 
                                endif;
                                ?>
                            </select>
                            <div class="input-group-append">
                                <span id="<?= $time.'-'.$way; ?>-new" class="input-group-text">
                                    <a class="link" href="<?= bizz_url_ui().$way; ?>" target="_blank" ><?= strtolower(lang('new').' '.lang('template')); ?></a>
                                </span>
                            </div>
                        </div>
                        <small id="<?= $time.'-'.$way; ?>-help" class="form-text text-muted"><?= ucfirst(lang('choose_template').' '.lang($way)); ?></small>
                    </div>
                    <?php endforeach; ?>
                    <?php foreach ($ways as $way): ?>                    
                    <div class="form-check form-check-inline">
                        <input id="<?= $time.'-'.$way; ?>-input" name="<?= $time.'-'.$way; ?>-input" class="form-check-input" type="checkbox" value="email" checked="checked">
                        <label for="<?= $time.'-'.$way; ?>-input" class="form-check-label"><?= ucfirst(lang('send').' '.lang($way)); ?></label>
                    </div>
                    <?php endforeach; ?>
                    <hr class="hr">
                    <?php endforeach; ?>
                </form>
@endsection