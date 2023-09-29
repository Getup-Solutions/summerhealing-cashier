<template>
    <div class="">
        <!-- Main Hero Secction -->
        <div class="bg-cover bg-center h-[668px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-56 md:p-56">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-1 mb-10">SUMMER HEALING SOCIETY</h1>
                <p class="sh-para">Summer Healing is an International Religious Organisation, located in Sydney Road,
                    Brunswick, Summer Healing is a Yoga Studio, Wellness Center, Ministry and Ecstatic Temple for our
                    Members. The first of its kind in the Society; we offer the benefits of yoga—health, light,
                    spirituality—while maintaining the strength and diversity of the members we serve. Our focus is also on
                    teacher training, movement through music, collaboration, and heart. Summer Healing Brunswick welcomes
                    yoga practitioner members with good standing, inclusive of all levels and from all socio-economic
                    backgrounds that are in agreement with our Society Articles.</p>
                <div class='mouse-container mt-5'>
                    <div class='mouse'>
                        <span class='scroll-down'></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 md:px-0">
            <!-- Second section paras and images -->
            <div class="py-10 md:py-20">
                <div>
                    <h1 class="sh-head-4 text-left mb-10">Summer Healing Yoga’s mission is to develop and expand the
                        study of
                        consciousness into our society. This is implemented through connecting like-minded people whose
                        passion
                        and interests revolve around building a community of health and empathic awareness.</h1>
                    <p class="sh-para text-left mb-10">
                        We also seek to improve the bio-psycho-social-spiritual health of the general populous, and
                        aim
                        to do so via the intelligent science of yoga. By connecting the community to the precepts of
                        yoga in Melbourne, we aim to kindle a spiritual awakening, encompassing the health promoting
                        benefits of holistic, complementary and alternative healing methodologies to create a unity
                        of
                        body, mind and soul. This communion of action, thought and deed stems from an awareness of
                        our
                        place and part within the whole, and the interwoven nature of all life, and all levels of
                        consciousness.
                    </p>
                    <div class="grid md:grid-cols-2 gap-10 justify-items-center">
                        <div>
                            <img :src="'/assets/static/img/home_image_1.jpeg'" class=" h-full w-auto mx-auto rounded-xl"
                                alt="" srcset="">

                        </div>
                        <div>
                            <img :src="'/assets/static/img/home_image_2.jpg'" class=" h-full w-auto mx-auto rounded-xl"
                                alt="" srcset="">

                        </div>


                    </div>

                </div>

            </div>

            <!-- Subscription plans -->
            <div class="py-10 md:py-20">
                <h1 class="sh-head-2 mb-10">SUBSCRIPTION PLANS</h1>

                <div>
                    <div class="grid gap-10">
                        <Link href="/subscriptions" class="mx-auto">
                        <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all Subscription
                            plans</button>
                        </Link>

                        <div v-for="subscription_plan in subscription_plans" :key="subscription_plan.id">
                            <a href="#"
                                class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-4xl hover:bg-gray-100 dark:border-gray-700 dark:hover:border-gray-400 dark:hover:shadow-xl dark:hover:shadow-sh_yellow/40 dark:bg-white/10 dark:hover:bg-white/10">
                                <img class="object-cover w-full md:ml-4 rounded-md md:rounded-2xl h-56 md:h-full md:w-56"
                                    :src="subscription_plan.thumbnail_url" alt="">
                                <div class="flex flex-col justify-between p-2 md:p-4 leading-normal w-full">
                                    <h5 class="mb-2 sh-head-3 tracking-tight text-left">{{ subscription_plan.title }}</h5>
                                    <p class="mb-3 sh-para text-left line-clamp-3 dark:hover:line-clamp-4 text-sm">{{
                                        subscription_plan.description }}</p>
                                    <p class="mb-3 sh-para text-left font-medium">Validity : {{ subscription_plan.validity
                                    }}</p>
                                    <p class="mb-3 sh-para text-left font-medium">Price (AUD) : {{ subscription_plan.price
                                    }}</p>
                                    <Link :href="`subscriptions/${subscription_plan.slug}`">
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

                        <Link href="/subscriptions" class="mx-auto">
                        <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all Subscription
                            plans</button></Link>
                    </div>
                </div>
            </div>

            <!-- About Summer healing Society -->
            <div class="py-10 md:py-20 grid">
                <h1 class="sh-head-1 mb-10">ABOUT SUMMER HEALING SOCIETY</h1>
                <p class="sh-para text-center mb-10">
                    The Summer Healing Private Contract Association (“PCA”) is a private membership organisation nestled
                    within larger associations similarly established. By definition, a PCA is private, it is established by
                    contract, and it is a free association of like-minded, like spirited-beings with shared purpose,
                    intentions and aspirations. Summer Healing is a private society established as a sovereign
                    ecclesiastical global society, within which is the Summer Healing Society.
                </p>
                <img :src="'/assets/static/img/home.jpeg'" class="h-auto w-full md:w-[600px]  mx-auto rounded-xl mb-10"
                    alt="" srcset="">
                <button class="sh-button mx-auto w-44 text-base dark:hover:shadow-xl">Learn more</button>
            </div>

            <!-- Courses -->
            <div class="py-10 md:py-20">
                <h1 class="sh-head-2 mb-10">COURSES</h1>

                <div>
                    <div class="grid gap-10">
                        <Link href="/courses" class="mx-auto">
                        <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all Courses</button>

                        </Link>
                        <div class="grid md:grid-cols-2 gap-10 gap-y-10">

                            <div v-for="course in courses" :key="course.id">

                                <Card :image="course.thumbnail_url" :href="`/courses/${course.slug}`" :title="course.title"
                                    :text="course.description" :price="course.price" :buttonText="'Learn more'"></Card>

                            </div>


                        </div>
                        <Link href="/courses" class="mx-auto">
                        <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all Courses</button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Join Our Telegram Group -->
            <JoinOurTelegramGroup>
            </JoinOurTelegramGroup>

            <!-- Notice -->
            <div class="py-10 md:py-20 grid">
                <img :src="usePage().props.siteLogo" class="h-auto w-48 mx-auto rounded-xl mb-10" alt="" srcset="">
                <h1 class="sh-head-4 mb-10">-NOTICE-</h1>
                <h1 class="sh-head-4 text-base mb-10">For full details of all the relevant binding Public Notices, Bonds and
                    Accessions please go to Linkt Tree</h1>
                <h1 class="sh-head-4 text-base mb-10"> Summer Healing Society contains adoption with Summer Healing
                    Ministry, Ecstatic Temple, Rebirthing
                    Breathwork, Summer Healing PCA, Yoga Unity, AFB Trust and Summer Healing Trust.

                    Established without the intent of causing harm, injury or loss, and as defined by the undersigned
                    Authorised Representative of

                    AIMEE FREDERICA BRACKS exclusively.</h1>
                <h1 class="sh-head-4 text-base mb-10 underline">ALL RIGHTS RESERVED WITHOUT PREJUDICE OR RECOURSE, TRESPASS
                    FORBIDDEN.</h1>
                <h1 class="sh-head-4 text-base mb-10 underline">Summer Healing Society© </h1>

                <!-- <button class="sh-button mx-auto w-44 text-base dark:hover:shadow-xl">Learn more</button> -->
            </div>
        </div>

    </div>
</template>
<script>
export default {
    props: ["subscription_plans", "courses"],
}
</script>
<script setup>
import { usePage } from "@inertiajs/vue3";
import JoinOurTelegramGroup from '../../Shared/JoinOurTelegramGroup.vue';
import Card from "../../Shared/Card.vue";

// const page = usePage();
</script>
<style scoped>
/* body{ background: #000 } */

@-webkit-keyframes scroll-inner {
    from {
        margin-top: 15%;
    }

    to {
        margin-top: 50%;
    }
}

@keyframes scroll-inner {
    from {
        margin-top: 15%;
    }

    to {
        margin-top: 50%;
    }
}

@-webkit-keyframes scroll-mouse {
    from {
        margin-top: 0;
    }

    to {
        margin-top: 15px;
    }
}

@keyframes scroll-mouse {
    from {
        margin-top: 0;
    }

    to {
        margin-top: 15px;
    }
}

div.mouse-container {
    position: relative;
    display: block;
    /* border:solid 1px red; */
    height: 100px;
}

div.mouse {
    position: relative;
    margin: 0 auto;
    display: block;
    width: 30px;
    height: 50px;
    border: solid 2px #fff;
    border-radius: 25px;
    -webkit-animation: scroll-mouse 1.5s;
    animation: scroll-mouse 1.5s;
    -webkit-animation-iteration-count: infinite;
    animation-iteration-count: infinite;
    /* //-webkit-animation-timing-function:ease;
  //animation-timing-function:ease; */


}

.scroll-down {
    display: block;
    width: 10px;
    height: 10px;
    background: #fff;
    border-radius: 100%;
    margin: 15% auto auto auto;

    -webkit-animation: scroll-inner 1.5s;
    animation: scroll-inner 1.5s;
    -webkit-animation-iteration-count: infinite;
    animation-iteration-count: infinite;
    -webkit-animation-timing-function: ease;
    animation-timing-function: ease
}
</style>