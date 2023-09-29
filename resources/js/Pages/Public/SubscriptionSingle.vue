<template>
    <div class="">

        <div class="bg-cover bg-center h-[368px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-36 md:p-36">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-4 mb-10">- SUBSCRIPTION PLAN -</h1>
                <h1 class="sh-head-1 mb-10">{{ subscription_plan.title }}</h1>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-6 md:px-0">
            <div class="py-10 md:py-20">
                <!-- <h1 class="sh-head-2 mb-10">SUBSCRIPTION PLANS</h1> -->

                <div>
                    <div class="grid md:grid-cols-3 gap-20">
                        <div class="md:col-span-2">
                            <p class="mb-6 sh-para text-left text-base">{{
                                subscription_plan.description }}</p>
                            <h1 class="sh-head-4 text-left font-bold mb-4">Subscription benefits :</h1>
                            <div class="mb-10">
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 font-bold mb-4 underline">Courses
                                </h1>
                                <div class="grid grid-cols-5 gap-4" v-for="course in courses_included" :key="course.id">
                                    <p class="mb-3 sh-para text-left text-base col-span-2">{{
                                        course.title }}</p>
                                    <p class="mb-3 sh-para text-base">-</p>
                                    <p class="mb-3 sh-para text-left text-base col-span-2">{{
                                        course.offer_price }}</p>
                                </div>


                            </div>

                            <div>
                                <h1 class="sh-para text-lg text-left dark:text-gray-200 font-bold mb-4 underline">Wellness
                                    Center</h1>
                                <div class="grid grid-cols-5 gap-4" v-for="facility in facilities_included"
                                    :key="facility.id">
                                    <p class="mb-3 sh-para text-left text-base col-span-2">{{
                                        facility.title }}</p>
                                    <p class="mb-3 sh-para text-base">-</p>
                                    <p class="mb-3 sh-para text-left text-base col-span-2">{{
                                        facility.offer_price }}</p>
                                </div>
                            </div>
                        </div>

                        <AddToCartCard :item="subscription_plan" :type="'subscription'"></AddToCartCard>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    props: ["subscription_plan", "courses_included", "facilities_included"],
}
</script>
<script setup>
import AddToCartCard from '../../Shared/AddToCartCard.vue';
</script>