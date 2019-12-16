<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
@include('components.head')

<body id="page-top">
	@include('components.header')
@if( isset($access) && $access === 'public' )
	<main class="page-margin">
@else
	<main class="page-margin-full">
@endif
@yield('content')
	</main>

	@include('components.footer')

@if( isset($js) )
@foreach($js as $file => $position)
@if( $position === 'end' && file_exists(FCPATH.JSPATH.$file) && filesize(FCPATH.JSPATH.$file) > 0 )
	<script src="<?= JSPATH.$file ?>" type="application/javascript"></script>
@elseif( $position === 'end' && file_exists(FCPATH.VENDORPATH.$file) && filesize(FCPATH.VENDORPATH.$file) > 0 )
	<script src="<?= VENDORPATH.$file ?>" type="application/javascript"></script>
@endif
@endforeach
@endif

@hasSection('script')
	@yield('script')
@endif

</body>
</html>