<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.settings')
@section('title', ucwords(lang('associated_calendars')))

@section('settings-content')
                <?php
                $calendars = array('google', 'windows', 'facebook');
                foreach ($calendars as $calendar):
                ?>
                <div class="row mb-4">
                    <div class="col">
                        <?php if( $calendar === 'google' ): ?>
                        <div class="g-signin2" data-onsuccess="onSignIn"></div>
                        <?php else: ?>
                        <button type="button" class="btn btn-social btn-social-<?= $calendar; ?>" onclick="hello('<?= $calendar; ?>').login()">
                            <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                            <?= ucfirst('sign in to ').ucfirst($calendar); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
@endsection