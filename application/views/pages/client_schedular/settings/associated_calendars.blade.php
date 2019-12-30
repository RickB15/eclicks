<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('associated_calendars')))

@section('settings-content')
                <?php
                // $calendars = array('google', 'windows', 'facebook');
                $calendars = array('google');
                foreach ($calendars as $calendar):
                ?>
                <div class="row mb-4">
                    <div class="col">
                        <?php if( $calendar === 'google' ): ?>
                            <!--Add buttons to initiate auth sequence and sign out-->                            
                            <button id="authorize_button" class="btn btn-social btn-social-<?= $calendar; ?>" style="display: none;">
                                <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                                <?= ucfirst(lang('sign_in').' '.lang('with').' ').ucfirst($calendar); ?>
                            </button>
                            <button id="signout_button" class="btn btn-social btn-social-<?= $calendar; ?>" style="display: none;">
                                <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                                <?= ucfirst(lang('sign_out').' '.lang('with').' ').ucfirst($calendar); ?>
                            </button><br>
                            <small id="auth-status" class="text-danger"></small>
                            
            <pre id="content" style="white-space: pre-wrap;"></pre>
                        <?php else: ?>
                        <button type="button" class="btn btn-social btn-social-<?= $calendar; ?>" onclick="hello('<?= $calendar; ?>').login()">
                            <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                            <?= ucfirst(lang('sign_in')).' '.ucfirst($calendar); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
@endsection

@section('script')
<script async defer src="https://apis.google.com/js/api.js"
    onload="this.onload=function(){};handleClientLoad()"
    onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
@endsection