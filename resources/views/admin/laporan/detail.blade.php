@extends('layouts.admin')

@section('title','Detail Laporan')

@section('content')

@include('admin.laporan.partials.stepper')

<div class="row">

<div class="col-lg-8">

@include('admin.laporan.partials.info-laporan')

@include('admin.laporan.partials.dokumentasi')

@include('admin.laporan.partials.timeline')

{{-- Form sesuai status --}}
@include('admin.laporan.status.' . strtolower($report->status))

</div>

<div class="col-lg-4">

@include('admin.laporan.partials.pelapor')

</div>

</div>

@endsection