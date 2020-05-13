@extends('layouts.master')
@section('title', 'Browse Jobs')

@section('content')

<!-- Spacer -->
<div class="margin-top-90"></div>
<!-- Spacer / End-->

<!-- Page Content
================================================== -->
<div id="app">
	<browse-jobs :user="{{ Auth::user()->profile }}"></browse-jobs>
</div>

<script src="{{ asset('js/app.js') }}" ></script>

@endsection