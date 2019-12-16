<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
@extends('template.skeleton')

@section('content')
        <div class="container-fluid">
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