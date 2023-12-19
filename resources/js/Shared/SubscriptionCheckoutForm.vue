<template>
    <div class="rounded-lg h-fit p-4 md:p-2 md:px-4 dark:border-2 dark:border-gray-500 dark:bg-black/10">
        <div class="px-2 md:px-2 pt-4 pb-1">
            <p class="mb-3 sh-para text-left">Price : {{ subscriptionplan.price }} AUD
                <span class="mb-3 sh-para text-left" v-if="subscriptionplan.payment_mode == 'recurring'">(Billed once per {{
                    subscriptionplan.payment_interval_count }} {{ subscriptionplan.payment_interval }}s)</span>
                <span class="mb-3 sh-para text-left" v-if="subscriptionplan.payment_mode == 'one-time'">( Valid for {{
                    subscriptionplan.payment_interval_count }} {{ subscriptionplan.payment_interval }}s)
                </span>
            </p>
        </div>
        <FormSimpleInput :label="'Card Holder Name'" :name="'name'" :type="'text'" v-model="name">
        </FormSimpleInput>
        <!-- <FormSimpleInput :label="'Card Holder Email'" :name="'email'" :type="'email'" v-model="checkoutInfo.email"
            :error="usePage().props.errors.email">
        </FormSimpleInput> -->
        <div id="card-element" class="py-10">
        </div>
        <button class="sh-button rounded-lg flex justify-center items-center w-full gap-2"
            @click.prevent="submitPaymentMethod">Purchase
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            
        </button>
        <p class="text-sm text-red-600">{{ addPaymentStatusError }}</p>


        <!--
             <button class="sh-button rounded-lg flex justify-center items-center w-full gap-2"
            @click.prevent="addToCart({ url: '/cart/add', product: item, type:type, only: ['flash', 'errors'] })">Add to
            Cart
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </button> 
    -->
    </div>
</template>
<script>
import { router } from "@inertiajs/vue3";
export default {
    props: ["subscriptionplan", "type", "errors", "stripeAPIToken", "intentToken"],
    data() {
        return {
            checkoutInfo: {},
            name: '',
            addPaymentStatus: 0,
            addPaymentStatusError: ''
        }
    },
    mounted() {
        this.includeStripe('js.stripe.com/v3/', function () {
            this.configureStripe();
        }.bind(this));
    },
    methods: {

        includeStripe(URL, callback) {
            // console.log(this.stripeAPIToken);
            let documentTag = document, tag = 'script',
                object = documentTag.createElement(tag),
                scriptTag = documentTag.getElementsByTagName(tag)[0];
            object.src = '//' + URL;
            if (callback) { object.addEventListener('load', function (e) { callback(null, e); }, false); }
            scriptTag.parentNode.insertBefore(object, scriptTag);
        },

        configureStripe() {
            this.stripe = Stripe(this.stripeAPIToken);
            this.elements = this.stripe.elements();
            var style = {
                style: {
                    base: {
                        iconColor: '#fff',
                        color: '#fff',
                        fontWeight: '400',
                        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                        fontSize: '17px',
                        fontSmoothing: 'antialiased',
                        ':-webkit-autofill': {
                            color: '#fff',
                        },
                        '::placeholder': {
                            color: '#fff',
                        },
                    },
                    invalid: {
                        iconColor: '#FFC7EE',
                        color: '#FFC7EE',
                    },
                },
            }
            this.card = this.elements.create('card', style);
            this.card.mount('#card-element');
        },

        submitPaymentMethod() {
            this.addPaymentStatus = 1;
            console.log(this.name);
            this.stripe.confirmCardSetup(
                this.intentToken.client_secret, {
                payment_method: {
                    card: this.card,
                    billing_details: {
                        name: this.name
                    }
                }
            }
            ).then(function (result) {
                if (result.error) {
                    this.addPaymentStatus = 3;
                    this.addPaymentStatusError = result.error.message;
                } else {
                    // console.log(result.setupIntent);
                    router.post('/subscription-plan/checkout', {
                        payment_method: result.setupIntent.payment_method,
                        plan : this.subscriptionplan
                    })
                    this.addPaymentStatus = 2;
                    this.card.clear();
                    this.checkoutInfo.name = '';
                }
            }.bind(this));
        },

        /*
    Saves the payment method for the user and
    re-loads the payment methods.
*/
        savePaymentMethod(method) {
            axios.post('/subscription-plan/checkout', {
                payment_method: method
            }).then(function (result) {
                console.log(result);
                // this.loadPaymentMethods();
            }.bind(this));
        },

    }
}
</script>
<script setup>
import { usePage } from '@inertiajs/vue3'
import { addToCart } from '../main';
import FormSimpleInput from './FormElements/FormSimpleInput.vue';
</script>