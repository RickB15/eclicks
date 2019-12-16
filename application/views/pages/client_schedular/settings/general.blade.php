<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('general')))

@section('settings-content')
                <form methode="post" action="#" class="form" onsubmit="return false;">
                    <div id="time-zone" class="form-group mt-4">
                        <h5><?= ucfirst(lang('time_zone')); ?></h5>
                        <div class="form-row">
                            <div class="col-lg-4 col-12">
                                <div class="input-group mb-3">
                                    <select id="time-zone-select" class="custom-select" onchange="autoSubmit(this)">
                                        <?php for ($i = -11; $i <= 14; $i++): ?>
                                            <option value="<?= $i; ?>" 
                                                <?php
                                                if( isset($settings['time_zone']) && (int)$settings['time_zone'] === $i ): ?>
                                                    selected="true"
                                                <?php elseif( !isset($settings['time_zone']) && $i === 0 ): ?>
                                                    selected="true"
                                                <?php endif; ?>
                                            >
                                                <?php
                                                    if( $i > 0 ){ $utc = "+"; }
                                                    elseif( $i === 0 ){ $utc = "±"; }
                                                    else { $utc = ''; }
                                                    echo 'UTC'.$utc.$i;
                                                ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <label for="time-zone" class="input-group-text"><?= ucfirst(lang('time_zone')) ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="start-times" class="form-group mt-4">
                        <h5><?= ucfirst(lang('start_times')); ?></h5>
                        <?php for ($i = 0; $i <= 55; $i+=5): ?>
                            <div class="form-check form-check-inline">
                                <input id="start-times-input-<?= $i ?>" class="form-check-input" type="checkbox" value="<?= $i ?>" onclick="autoSubmit(this)"
                                    <?php 
                                        if( isset($settings['start_times']) ): 
                                            foreach($settings['start_times'] as $time):
                                                if( $time === $i ):
                                    ?>
                                                checked="true"
                                    <?php   
                                                endif;
                                            endforeach;
                                        endif; 
                                    ?>
                                />
                                <label for="start-times-input-<?= $i ?>" class="form-check-label">
                                    <?php if(strlen((string)$i) === 1) { echo "0";} echo $i ?>
                                </label>
                            </div>
                        <?php endfor; ?>
                        <small class="text-muted d-block"><?= ucfirst(lang('for_example')); ?>: 10:00am, 10:30am, 11:00am...</small>
                    </div>
                    <div id="interim" class="form-group mt-4">
                        <h5 class="mb-0"><?= ucfirst(lang('interim')); ?></h5>
                        <div class="row">
                            <div class="col">
                                <p class="d-inline-block">
                                    <?= ucfirst(lang('time_between_at_leased')); ?>&nbsp;
                                </p>
                                <div class="d-inline-block">
                                    <select id="interim-select" class="custom-select" onchange="autoSubmit(this)">
                                        <?php for ($i = 0.5; $i <= 5; $i+=.5): ?>
                                            <option value="<?= $i; ?>"                                            
                                                <?php if( isset($settings['interim'])
                                                          && $settings['interim'] === $i ): ?>
                                                    selected="true"
                                                <?php endif; ?>
                                            >
                                                <?php 
                                                    if(is_numeric($i) && floor($i) != $i){ echo floor($i).' ' . lang('hours') . ' ' . lang('and') . ' 30 ' . lang('minutes'); }
                                                    else { echo $i.' '. lang('hours'); }
                                                ?>                                                
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <p class="d-inline-block">
                                    &nbsp;<?= strtolower(lang('apart')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="amount" class="form-group mt-4">
                        <h5 class="mb-0"><?= ucfirst(lang('amount_a_day')); ?></h5>
                        <div class="row">
                            <div class="col">
                                <div class="d-inline-block">
                                    <select id="amount-select" class="custom-select" onchange="autoSubmit(this)">
                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?= $i; ?>"                                            
                                                <?php if( isset($settings['amount'])
                                                          && $settings['amount'] === $i ): ?>
                                                    selected="true"
                                                <?php endif; ?>
                                            >
                                                <?php 
                                                    echo $i;
                                                ?>                                                
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <p class="d-inline-block">
                                    <?= ucfirst(lang('appointments_a_day')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="redirect" class="form-group mt-4">
                        <h5 class="mb-0"><?= ucfirst(lang('redirect_url')); ?></h5>
                        <div class="form-row">
                            <div class="col-12">
                                <div class="input-group">
                                    <input id="redirect-input" class="form-control" type="text" name="redirect-url" onchange="autoSubmit(this)" 
                                        <?php if( isset($settings['redirect_url']) ): ?>
                                            placeholder="<?= $settings['redirect_url'] ?>"
                                        <?php endif; ?>
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <a id="reset-url" class="link" href="#">
                                                <?= ucfirst(lang('reset')); ?>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <small class="form-text text-muted"><?= ucfirst(lang('set a') . ' ' . lang('redirect_url')); ?></small>
                            </div>
                        </div>
                    </div>
                </form>
@endsection