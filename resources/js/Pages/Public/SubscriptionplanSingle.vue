<template>
    <div class="">
        <div
            class="bg-cover bg-center h-[368px]"
            style="background-image: url('/assets/static/img/home_banner.webp')"
        >
            <div
                class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-36 md:p-36"
            >
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-4 mb-10">- SUBSCRIPTION PLAN -</h1>
                <h1 class="sh-head-1 mb-10">{{ subscriptionplan.title }}</h1>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-6 md:px-0">
            <div class="py-10 md:py-20">
                <!-- <h1 class="sh-head-2 mb-10">SUBSCRIPTION PLANS</h1> -->

                <div>
                    <div class="grid md:grid-cols-3 gap-20">
                        <div class="md:col-span-2">
                            <p class="mb-6 sh-para text-left text-base">
                                {{ subscriptionplan.description }}
                            </p>
                            <h1 class="sh-head-4 text-left font-bold mb-4" v-if="courses_included.length || facilities_included.length">
                                Subscription benefits :
                            </h1>
                            <div class="mb-10" v-if="courses_included.length">
                                <h1
                                    class="sh-para text-lg text-left dark:text-gray-200 font-bold mb-4 underline"
                                >
                                    Courses
                                </h1>
                                <div
                                    class="grid grid-cols-5 gap-4"
                                    v-for="course in courses_included"
                                    :key="course.id"
                                >
                                    <p
                                        class="mb-3 sh-para text-left text-base col-span-2"
                                    >
                                        {{ course.title }}
                                    </p>
                                    <p class="mb-3 sh-para text-base">-</p>
                                    <p
                                        class="mb-3 sh-para text-left text-base col-span-2"
                                    >
                                        {{ course.offer_price }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="facilities_included.length">
                                <h1
                                    class="sh-para text-lg text-left dark:text-gray-200 font-bold mb-4 underline"
                                >
                                    Wellness Center
                                </h1>
                                <div
                                    class="grid grid-cols-5 gap-4"
                                    v-for="facility in facilities_included"
                                    :key="facility.id"
                                >
                                    <p
                                        class="mb-3 sh-para text-left text-base col-span-2"
                                    >
                                        {{ facility.title }}
                                    </p>
                                    <p class="mb-3 sh-para text-base">-</p>
                                    <p
                                        class="mb-3 sh-para text-left text-base col-span-2"
                                    >
                                        {{ facility.offer_price }}
                                    </p>
                                </div>
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

                            <PayNowCard v-else
                                :item="subscriptionplan"
                                :url="'/subscription-plan/checkout'"
                                :type="'subscription'"></PayNowCard>
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
    ],
};
</script>
<script setup>
import AddToCartCard from "../../Shared/AddToCartCard.vue";
import PayNowCard from "../../Shared/PayNowCard.vue";
</script>
