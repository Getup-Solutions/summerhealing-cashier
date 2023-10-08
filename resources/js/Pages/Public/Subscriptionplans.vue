<template>
    <div class="">

        <div class="bg-cover bg-center h-[368px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-36 md:p-36">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-1 mb-10">SUBSCRIPTION PLANS</h1>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 md:px-0">
            <div class="py-10 md:py-20">
                <!-- <h1 class="sh-head-2 mb-10">SUBSCRIPTION PLANS</h1> -->

                <div>
                    <div class="grid gap-10">
                        <div v-for="subscriptionplan in subscriptionplans" :key="subscriptionplan.id">
                            <a href="#"
                                class="flex md:px-4 flex-col items-center bg-white border border-gray-200 rounded-xl shadow md:flex-row md:max-w-4xl hover:bg-gray-100 dark:border-gray-700 dark:hover:border-gray-400 dark:hover:shadow-xl dark:hover:shadow-sh_yellow/40 dark:bg-white/10 dark:hover:bg-white/10">
                                <img class="object-cover w-full md:ml-4 rounded-md md:rounded-2xl h-56 md:h-full md:w-56 md:shadow-xl md:shadow-sh_yellow/50"
                                    :src="subscriptionplan.thumbnail_url" alt="">
                                <div class="flex flex-col justify-between p-2 md:p-4 md:px-10 leading-normal w-full">
                                    <h5 class="mb-2 sh-head-3 tracking-tight text-left">{{ subscriptionplan.title }}</h5>
                                    <p class="mb-3 sh-para text-left line-clamp-4 text-sm">{{
                                        subscriptionplan.description }}</p>
                                    <p class="mb-3 sh-para text-left font-medium">Validity : {{ subscriptionplan.validity
                                    }}</p>
                                    <p class="mb-3 sh-para text-left font-medium">Price (AUD) : {{ subscriptionplan.price
                                    }}</p>
                                    <Link :href="`/subscriptionplans/${subscriptionplan.slug}`">
                                        <button class="sh-button flex justify-center items-center md:w-36">Learn more <svg
                                            class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg></button>
                                    </Link>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Join Our Telegram Group -->
            <JoinOurTelegramGroup>
            </JoinOurTelegramGroup>

        </div>

    </div>
</template>
<script>
export default {
    props: ["subscriptionplans"],
}
</script>
<script setup>
import JoinOurTelegramGroup from '../../Shared/JoinOurTelegramGroup.vue';
</script>