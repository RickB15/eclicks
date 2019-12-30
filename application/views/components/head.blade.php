<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<head>
    <meta charset="{{ $meta_charset }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9,IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />
    <meta name="keywords" content="{{ $meta_keywords }}" />
    <meta name="description" content="{{ $meta_description }}" />
    <meta name="author" content="{{ $meta_author }}" />
    <meta name="packages" content="
        https://github.com/chrisssycollins/vanilla-calendar,
        https://getbootstrap.com/docs/4.3/getting-started/download/,
        https://code.jquery.com/jquery-3.4.1.min.js,
        https://fontawesome.com/how-to-use/on-the-web/setup/hosting-font-awesome-yourself
    "/>

    <title>
@if(isset($pageName))
        {{  ucwords(lang(str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]\s+/', '_', $pageName)))) }}
@if (isset($segment) && $segment !== 'index')
• {{ ucwords(lang(str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]\s+/', '_', $segment)))) }}
@endif
@if ($pageName === 'appointment_planner' && isset($segment) && $segment === 'index' && isset($cs_username) && !empty($cs_username))
• {{ ucfirst($cs_username) }}
@endif
@else
        @yield('title', ucwords(lang('no_title')))
@endif
    </title>

    <link type="image/ico" rel="icon" href="<?= IMGPATH ?>favicons/favicon-16x16.ico" sizes="16x16" />
    <link type="image/ico" rel="icon" href="<?= IMGPATH ?>favicons/favicon-32x32.ico" sizes="32x32" />
    <link type="image/png" rel="icon" href="<?= IMGPATH ?>favicons/favicon.png" />

    <noscript>Sorry, your browser does not support JavaScript!</noscript>

    <!-- ** SET GLOBAL JS BASE URL ** -->
    <script>
        const base_url = '<?php echo base_url(); ?>';
        const locale = '<?php echo $language; ?>';
        <?php 
        if( isset($pageName) && isset($segment) && ($pageName === 'appointment_planner' || $pageName === 'share & publish') && $segment === 'index'):
            if( isset($cs_username) ): ?>
                const cs_username = '<?php echo url_title($cs_username); ?>';
        <?php
            endif;
            if( isset($cs_duration) && isset($cs_title) ): ?>
                const eventDuration = '<?php echo $cs_duration; ?>';
                const eventTitle = '<?php echo url_title($cs_title); ?>';
        <?php
            endif;
        endif;
        ?>
        const globalLang = {
           january: '<?php echo lang('january'); ?>',
           febuary: '<?php echo lang('febuary'); ?>',
           march: '<?php echo lang('march'); ?>',
           april: '<?php echo lang('april'); ?>',
           may: '<?php echo lang('may'); ?>',
           june: '<?php echo lang('june'); ?>',
           july: '<?php echo lang('july'); ?>',
           august: '<?php echo lang('august'); ?>',
           september: '<?php echo lang('september'); ?>',
           october: '<?php echo lang('october'); ?>',
           november: '<?php echo lang('november'); ?>',
           december: '<?php echo lang('december'); ?>',
           something_went_wrong: '<?php echo lang('error_something_went_wrong'); ?>',
           edit: '<?php echo lang('edit_'); ?>',
           availability: '<?php echo lang('availability'); ?>',
           delete: '<?php echo lang('delete'); ?>',
           cancel: '<?php echo lang('cancel'); ?>',
           duplicate: '<?php echo lang('duplicate'); ?>',
           reset: '<?php echo lang('reset'); ?>',
           all: '<?php echo lang('all'); ?>',
           make: '<?php echo lang('make'); ?>',
           an: '<?php echo lang('an'); ?>',
           event: '<?php echo lang('event'); ?>',
           save_changes: '<?php echo lang('save_changes'); ?>',
           required: '<?php echo lang('required'); ?>',
           hours: '<?php echo lang('hours'); ?>',
           minutes: '<?php echo lang('minutes'); ?>',
           seconds: '<?php echo lang('seconds'); ?>',
           hour: '<?php echo lang('hour'); ?>',
           minute: '<?php echo lang('minute'); ?>',
           second: '<?php echo lang('second'); ?>',
           title: '<?php echo lang('title'); ?>',
           duration: '<?php echo lang('duration'); ?>',
           description: '<?php echo lang('description'); ?>',
           enter_an_hour: '<?php echo lang('enter_an_hour'); ?>',
           enter_a_minute: '<?php echo lang('enter_a_minute'); ?>',
           ok: '<?php echo lang('ok'); ?>',
           morning: '<?php echo lang('morning'); ?>',
           afternoon: '<?php echo lang('afternoon'); ?>',
           evening: '<?php echo lang('evening'); ?>',
           at: '<?php echo lang('at'); ?>',
           next: '<?php echo lang('next'); ?>',
           previous: '<?php echo lang('previous_'); ?>',
           no_times_available: '<?php echo lang('no_times_available'); ?>',
           name: '<?php echo lang('name'); ?>',
           email: '<?php echo lang('email'); ?>',
           phone: '<?php echo lang('phone'); ?>',
           click_me: '<?php echo lang('click_me'); ?>',
           appointment: '<?php echo lang('appointment'); ?>',
           sign_in: '<?php echo lang('sign_in'); ?>',
           sign_out: '<?php echo lang('sign_out'); ?>'
        };
    </script>

    <!-- ** PACKAGES ASSETS ** -->
    <!-- jQuery -->
    <script type="application/javascript" src="<?= JSPATH ?>lib/jquery-3.4.1/jquery.min.js"></script>

    <!-- Popper -->
    <script type="application/javascript" src="<?= JSPATH ?>lib/popper-1.16.0/popper.min.js"></script>

    <!-- Bootstrap -->
    <link type="text/css" href="<?= CSSPATH ?>lib/bootstrap-4.3.1-dist/bootstrap.min.css" rel="stylesheet" />
    <script type="application/javascript" src="<?= JSPATH ?>lib/bootstrap-4.3.1-dist/bootstrap.min.js"></script>

    <!-- Fontawesome -->
    <!-- <link type="text/css" href="<?= CSSPATH ?>lib/fontawesome-5.11.2-web/all.min.css" rel="stylesheet" /> -->
    <script type="application/javascript" src="<?= JSPATH ?>lib/fontawesome-5.11.2-web/all.min.js"></script>

    <!-- jQuery Widgets -->
    <link type="text/css" href="<?= CSSPATH ?>lib/jqwidgets-ver8.3.2/styles/jqx.base.css"  rel="stylesheet"/>
    <script type="application/javascript" src="<?= JSPATH ?>lib/jqwidgets-ver8.3.2/jqwidgets/jqxcore.js"></script>
    <script type="application/javascript" src="<?= JSPATH ?>lib/jqwidgets-ver8.3.2/jqwidgets/jqxdraw.js"></script>
    <script type="application/javascript" src="<?= JSPATH ?>lib/jqwidgets-ver8.3.2/jqwidgets/jqxtimepicker.js"></script>

    <!-- Moment -->
    <script type="application/javascript" src="<?= JSPATH ?>lib/moment-2.24.0/moment.js"></script>
    <script type="application/javascript" src="<?= JSPATH ?>lib/moment-2.24.0/moment-locales.js"></script>
    <script type="application/javascript" src="<?= JSPATH ?>lib/moment-2.24.0/moment-timezone-with-data.js"></script>

    <!-- Eclicks JS -->
    <script  type="application/javascript" src="<?= JSPATH ?>core.js"></script>

    <!-- ** THEME ASSETS ** -->
@if( isset($appName) && strtolower($appName) === 'eclicks' && isset($path) && strtolower($path) !== 'auth' && strtolower($path) !== 'policies' && strtolower($path) !== 'conditions')
    <!-- SB Admin 2 -->
    <link type="text/css" href="<?= THEMEPATH ?>sb-admin-2/css/sb-admin-2.min.css"  rel="stylesheet"/>
	<script type="application/javascript" src="<?= THEMEPATH ?>sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script type="application/javascript" src="<?= THEMEPATH ?>sb-admin-2/js/sb-admin-2.min.js"></script>
    <script type="application/javascript" src="<?= THEMEPATH ?>sb-admin-2/vendor/chart.js/Chart.min.js"></script>
    <script type="application/javascript" src="<?= THEMEPATH ?>sb-admin-2/js/demo/chart-area-demo.js"></script>
	<script type="application/javascript" src="<?= THEMEPATH ?>sb-admin-2/js/demo/chart-pie-demo.js"></script>
@endif

    <!-- ** PAGE SPECIFIC ASSETS ** -->
@if( isset($css) )
<?php //TODO error handeler file not found ?>
@foreach($css as $file)
@if( file_exists(FCPATH.CSSPATH.$file) && filesize(FCPATH.CSSPATH.$file) > 0 )
    <link type="text/css" href="<?= CSSPATH.$file ?>"  rel="stylesheet"/>
@elseif( file_exists(FCPATH.VENDORPATH.$file) && filesize(FCPATH.VENDORPATH.$file) > 0 )
    <link type="text/css" href="<?= VENDORPATH.$file ?>"  rel="stylesheet"/>
@endif
@endforeach
@endif

@if( isset($js) )
<?php //TODO error handeler file not found ?>
@foreach($js as $file => $position)
@if( $position === 'start' && file_exists(FCPATH.JSPATH.$file) && filesize(FCPATH.JSPATH.$file) > 0 )
    <script type="application/javascript" src="<?= JSPATH.$file ?>"></script>
@elseif( $position === 'start' && file_exists(FCPATH.VENDORPATH.$file) && filesize(FCPATH.VENDORPATH.$file) > 0 )
    <script type="application/javascript" src="<?= VENDORPATH.$file ?>"></script>
@endif
@endforeach
@endif

@if( isset($fonts) )
<?php //TODO error handeler file not found ?>
@foreach($fonts as $file)
@if( file_exists(FCPATH.FONTSPATH.$file) && filesize(FCPATH.FONTSPATH.$file) > 0 )
    <link href="<?= FONTPATH.$file ?>" type="font/collection" rel="stylesheet" />
@endif
@endforeach
@endif
    
    <!-- ** CUSTOM ASSETS ** -->

    <!-- Custom CSS -->
@if( isset($appName) && strtolower($appName) === 'client schedular' || isset($path) && (strtolower($path) === 'auth' || strtolower($path) === 'policies' || strtolower($path) === 'conditions'))
    <link type="text/css" href="<?= CSSPATH ?>main.css" rel="stylesheet" />
@endif

    <!-- Custom JS -->
    <!-- type="application/javascript" <script src="<?= JSPATH ?>core,js"></script> -->
</head>