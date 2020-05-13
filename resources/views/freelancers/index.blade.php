@extends('layouts.master')
@section('title', 'Browse Freelancers')

@section('content')

<!-- Spacer -->
<div class="margin-top-90"></div>
<!-- Spacer / End-->

<!-- Page Content
================================================== -->
<div id="app">
	<browse-freelancers></browse-freelancers>
</div>

<script src="{{ asset('js/app.js') }}"></script>


@endsection