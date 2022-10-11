@extends('layouts.main')

@section('content')
    <section class="fdb-block pt-5 pb-5" style="background-image: url(/images/vackground-com-7K1_uSnNoy4-unsplash.jpg);background-size: cover;">
        <div class="container">
            <div class="row text-center">
                <div class="col">
                    <h1 class="text-dark">Pricing</h1>
                </div>
            </div>

            <div class="row mt-5 align-items-center">
                <div class="col-12 col-sm-10 col-md-8 col-md-8 m-auto col-lg-4 text-center">
                    <div class="fdb-box shadow pb-5 pt-5 pl-3 pr-3 rounded bg-white">
                        <h2>Hobby</h2>
                        <p class="lead"><strong>£9 / month</strong></p>
                        <p class="h3 font-weight-light">Even the all-powerful Pointing has no control about.</p>

                        <ul class="text-left mt-5 mb-5">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>

                        <p><button id="9" class="btn btn-outline-primary mt-4 subscribe-now txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Subscribe</button></p>
                    </div>
                </div>

                <div class="col-12 col-sm-10 col-md-8 col-md-8 m-auto col-lg-4 text-center pt-4 pt-lg-0">
                    <div class="fdb-box shadow pb-5 pt-5 pl-3 pr-3 fdb-touch rounded bg-white">
                        <h2 class="text-primary">Professional</h2>
                        <p class="lead"><strong>£19 / month</strong></p>
                        <p class="h3 font-weight-light">Far far away, behind the word mountains, far from.</p>

                        <ul class="text-left mt-5 mb-5">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>

                        <p><button id="19" class="btn btn-primary mt-4 subscribe-now txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Subscribe</button></p>
                    </div>
                </div>

                <div class="col-12 col-sm-10 col-md-8 col-md-8 m-auto col-lg-4 text-center pt-4 pt-lg-0">
                    <div class="fdb-box shadow pb-5 pt-5 pl-3 pr-3 rounded bg-white">
                        <h2>Business</h2>
                        <p class="lead"><strong>£49 / month</strong></p>
                        <p class="h3 font-weight-light">Wild Question Marks, but the Little Blind Text didn’t listen.</p>

                        <ul class="text-left mt-5 mb-5">
                            <li>Item 1</li>
                            <li>Item 2</li>
                            <li>Item 3</li>
                        </ul>

                        <p><button id="49" class="btn btn-outline-primary mt-4 subscribe-now txt-brand-color-one bkg-brand-color-two bdr-brand-color-two">Subscribe</button></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modals -->
    <div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subscribe Now</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="action-content">
                    <div class="p-3" id="dropin-container">
                    </div>
                    <div class="p-3" id="dropin-loading">
                    </div>
                    <div class="p-3" id="success-or-failure" style="display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection