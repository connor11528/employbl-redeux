@extends('spark::layouts.app')

@section('scripts')
    <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
@endsection

@section('content')
<spark-register-braintree inline-template>
    <div>
        <div class="spark-screen container">
            <!-- Common Register Form Contents -->
            @include('spark::auth.register-common')

            <!-- Billing Information -->
            <div class="row justify-content-center" v-show="selectedPlan && selectedPlan.price > 0">
                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-header"><i class="fa fa-btn fa-credit-card"></i> {{__('Billing')}}</div>

                        <div class="card-body">
                            <!-- Generic 500 Level Error Message / Stripe Threw Exception -->
                            <div class="alert alert-danger" v-if="registerForm.errors.has('form')">
                                {{__('We had trouble validating your card. It\'s possible your card provider is preventing us from charging the card. Please contact your card provider or customer support.')}}
                            </div>

                            <form role="form">
                                <!-- Braintree Container -->
                                <div id="braintree-container" class="mb-3"></div>

                                <!-- Coupon Code -->
                                <div class="form-group row" v-if="query.coupon">
                                    <label for="number" class="col-md-4 col-form-label text-md-right">{{__('Coupon Code')}}</label>

                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="coupon" v-model="registerForm.coupon" :class="{'is-invalid': registerForm.errors.has('coupon')}">

                                        <span class="invalid-feedback" v-show="registerForm.errors.has('coupon')">
                                            @{{ registerForm.errors.get('coupon') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Terms And Conditions -->
                                <div class="form-group" :class="{'is-invalid': registerForm.errors.has('terms')}">
                                    <div class="col-sm-6 offset-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" v-model="registerForm.terms">
                                                {!! __('I Accept :linkOpen The Terms Of Service :linkClose', ['linkOpen' => '<a href="/terms" target="_blank">', 'linkClose' => '</a>']) !!}
                                            </label>
                                            <span class="invalid-feedback" v-show="registerForm.errors.has('terms')">
                                                <strong>@{{ registerForm.errors.get('terms') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Register Button -->
                                <div class="form-group row mb-0">
                                    <div class="col-sm-6 offset-sm-4">
                                        <button type="submit" class="btn btn-primary" :disabled="registerForm.busy">
                                            <span v-if="registerForm.busy">
                                                <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Registering')}}
                                            </span>

                                            <span v-else>
                                                <i class="fa fa-btn fa-check-circle"></i> {{__('Register')}}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Features Modal -->
        @include('spark::modals.plan-details')
    </div>
</spark-register-braintree>
@endsection
