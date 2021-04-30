@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-center">
        <div class="card w-50">
            <div class="card-body">
                <div id="accountCreated" class="d-none">
                    <div class="alert alert-success" role="alert">
                        Account Created!
                    </div>
                    <button type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2 txt-brand-color-two" id="nextButton">
                        Continue
                    </button>
                </div>


                <form id="onboardingForm">
                    <div class="mb-3">
                        <label for="legalEntity">Account Type</label>
                        <select class="form-control w-100" id="legalEntity">
                            <option value="Individual" selected>Individual</option>
                            <option value="Business">Business</option>
                            <option value="Non-Profit">Non-Profit</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="flex-fill mr-2">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control w-100" id="firstName" placeholder="John">
                        </div>
                        <div class="flex-fill ml-2">
                            <label for="gender">Gender</label>
                            <select class="form-control w-100" id="gender">
                                <option value="MALE" selected>Male</option>
                                <option value="FEMALE">Female</option>
                                <option value="UNKNOWN">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="sellerId">Seller ID</label>
                        <input type="text" class="form-control w-100" id="sellerId" >
                    </div>
                    <div class="mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control w-100" id="email" >
                    </div>
                    <button type="button" class="btn btn-primary bkg-brand-color-one bdr-brand-color-two mt-2 txt-brand-color-two" id="createAccountHolder">
                        Create Account
                    </button>
                </form >
            </div>
        </div>
</div>
@endsection
