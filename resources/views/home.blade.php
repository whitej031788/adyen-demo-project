@extends('layouts.main')

@section('content')
  <div class="row">
    <div class="col-md-6 offset-md-3 col-xs-12">
      <div class="card">
        <div class="card-body">
          <div class="btn-group-vertical w-100">
            <button id="editDemo" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/create-demo">Edit Demo</a></button>
            <button id="callCenter" type="button" class="btn btn-primary bkg-brand-color-two bdr-brand-color-two mt-2"><a class="txt-brand-color-one" href="/custom-call-center">Call Center</a></button>
            <button id="standardEcom" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/standard-ecom">Standard ECOM / POS</a></button>
            <button id="hotelCheckin" type="button" class="btn btn-primary bkg-brand-color-two bdr-brand-color-two mt-2"><a class="txt-brand-color-one" href="/hotel-checkin">Manage Payment</a></button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
