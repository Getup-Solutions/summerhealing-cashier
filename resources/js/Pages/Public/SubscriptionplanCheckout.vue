<template>
    <div class="">
        <div class="bg-cover bg-center h-[368px]" style="background-image: url('/assets/static/img/home_banner.webp')">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-36 md:p-36">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-4 mb-10">- CHECKOUT -</h1>
                <h1 class="sh-head-1 mb-10">{{ subscriptionplan.title }}</h1>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-6 md:px-0">
            <div class="py-10 md:py-20">
                <!-- <h1 class="sh-head-2 mb-10">SUBSCRIPTION PLANS</h1> -->

                <div>
                    <div class="grid md:grid-cols-2 gap-20">
                        <div class="">
                            <p class="mb-6 sh-para text-left text-base">
                                {{ subscriptionplan.description }}
                            </p>
                            <h1 class="sh-head-4 text-left font-bold mb-4" v-if="subscriptionplan.credits.length">
                                Subscription benefits :
                            </h1>
                            <div v-if="sessionGenCredits">
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 mb-4">
                                    Get {{ sessionGenCredits }} credits for any Yoga classes!
                                </h1>
                            </div>
                            <div v-for="session in sessions_credits" :key="session">
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 mb-4">
                                    Get {{ session.credits }} credits for {{ session.title }}!
                                </h1>
                            </div>

                            <div v-if="facilityGenCredits">
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 mb-4">
                                    Get {{ facilityGenCredits }} credits for any Wellness center bookings!
                                </h1>
                            </div>
                            <div v-for="facility in facilities_credits" :key="facility">
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 mb-4">
                                    Get {{ facility.credits }} credits for {{ facility.title }}!
                                </h1>
                            </div>
                        </div>
                        <div>
                            <div v-if="user_have_subscriptionplan">
                                <div class="px-2 bg-white/20 border-2 border-white md:px-4 rounded-lg py-4">
                                    <p class="sh-para text-left">
                                        You already have this Subscription
                                    </p>
                                </div>
                            </div>

                            <!-- <AddToCartCard
                                v-else
                                :item="subscriptionplan"
                                :type="'subscription'"
                            ></AddToCartCard> -->
                            <!-- <FormSimpleInput v-else :label="'Your Email'" :name="'email'" :type="'email'"
                                            v-model="checkoutInfo.email" :error="errors.email">
                                        </FormSimpleInput> -->

                            <SubscriptionCheckoutForm v-else :subscriptionplan="subscriptionplan"
                                :stripeAPIToken="stripePublicKey" :intentToken="intent" :type="'subscription'">
                            </SubscriptionCheckoutForm>

                            <!-- <PayNowCard v-else :item="subscriptionplan" :url="'/subscription-plan/checkout'"
                                :type="'subscription'"></PayNowCard> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: [
        "subscriptionplan",
        "courses_included",
        "facilities_included",
        "user_have_subscriptionplan",
        "errors",
        "stripePublicKey",
        "intent",
        "sessions_credits",
        "facilities_credits"
    ],
    data() {
        return {
            checkoutInfo: {},
        };
    },
    computed: {
        sessionGenCredits() {
            var credit = this.subscriptionplan.credits.find((element) => element.creditable_id === 0 && element.creditable_type === 'App\\Models\\Session')
            if (credit) {
                return credit.credits;
            } else {
                return false;
            }
        },
        facilityGenCredits() {
            var credit = this.subscriptionplan.credits.find((element) => element.creditable_id === 0 && element.creditable_type === 'App\\Models\\Facility')
            console.log(credit);
            if (credit) {
                return credit.credits;
            } else {
                return false;
            }
        }
    }
};
</script>
<script setup>
import AddToCartCard from "../../Shared/AddToCartCard.vue";
import FormSimpleInput from "../../Shared/FormElements/FormSimpleInput.vue";
import PayNowCard from "../../Shared/PayNowCard.vue";
import SubscriptionCheckoutForm from "../../Shared/SubscriptionCheckoutForm.vue";
</script>
