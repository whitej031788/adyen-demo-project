@extends('layouts.main')

@section('content')
  <div class="row">
    <div class="col-md-6 offset-md-3 col-xs-12">
      <div class="card">
        <div class="card-body">
          <div class="btn-group-vertical w-100">
              {{$pusherId}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
