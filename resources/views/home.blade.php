@extends('layouts.main')

@section('content')
  <div class="row mt-2">
    <div class="col-md-6 offset-md-3 col-xs-12">
      <div class="card">
        <div class="card-body">
          <div class="btn-group-vertical w-100">
            <button id="editDemo" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/create-demo-manual">Edit Demo</a></button>
            <button id="downloadDemo" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a id="downloadDemoHref" class="txt-brand-color-two" href="" download>Download Configuration</a></button>
            <button id="callCenter" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/custom-call-center">Call Center</a></button>
            <button id="unifiedCommerce" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/unified-commerce">Unified Commerce</a></button>
            <button id="saasSubscriptions" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/saas-subscriptions">SaaS Subscriptions</a></button>
            <button id="hotelCheckin" type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/hotel-checkin">Hotel Check-in / Check-out</a></button>
            <button id="payAsYouGo-register" type="button" class="w-100 btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/payg-registration">PAYG - Registration</a></button>
            <button id="payAsYouGo-interface" type="button" class="w-100 btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2"><a class="txt-brand-color-two" href="/payg-interface">PAYG - Interface</a></button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
