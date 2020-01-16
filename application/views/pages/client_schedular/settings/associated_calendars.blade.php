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
                            <?php if( $signIn === FALSE ): ?>
                            <a id="signout_button" class="btn btn-social btn-social-<?= $calendar; ?>" href="<?= base_url('OAuth/revoke_api'); ?>">
                                <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                                <?= ucfirst(lang('sign_out').' '.lang('with').' ').ucfirst($calendar); ?>
                            </a>
                            <?php else: ?>
                            <a id="authorize_button" class="btn btn-social btn-social-<?= $calendar; ?>" href="<?= base_url('OAuth'); ?>">
                                <span class="btn-social__icon"><i class="fab fa-<?= $calendar; ?> fa-2x"></i></span>
                                <?= ucfirst(lang('sign_in').' '.lang('with').' ').ucfirst($calendar); ?>
                            </a>
                            <?php endif; ?>
                            <br>
                            <small id="auth-status" class="<?php if($signIn === FALSE){echo 'text-success';}else{echo 'text-danger';} ?>">
                            <?php if( $signIn === FALSE ): ?>
                                you are signed in to <?= ucfirst($calendar) ?>
                            <?php else: ?>
                                you are not signed in to <?= ucfirst($calendar) ?>
                            <?php endif; ?>
                            </small>
                            
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

<!-- @section('script')
<script async defer src="https://apis.google.com/js/api.js"
    onload="this.onload=function(){};handleClientLoad()"
    onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>
@endsection -->