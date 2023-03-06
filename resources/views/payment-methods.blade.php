@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row mt-2">
      <div class="col-12">
        <h1 class="text-center">Manage Payment Methods</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <p class="text-center">
          Add, remove, or manage your account's payment methods. This will allow you to easily checkout across all channels very quickly in future
        </p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div id="dropin-container" class=" col-md-8 col-sm-12 mt-3"></div>
    </div>
  </div>
@endsection
