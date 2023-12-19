<template>
    <div class="">
        <!-- Main Hero Secction -->
        <div class="bg-cover bg-center h-[268px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-1 mb-10">About Us</h1>
                <!-- <p class="sh-para">Summer Healing is an International Religious Organisation, located in Sydney Road,
                    Brunswick, Summer Healing is a Yoga Studio, Wellness Center, Ministry and Ecstatic Temple for our
                    Members. The first of its kind in the Society; we offer the benefits of yoga—health, light,
                    spirituality—while maintaining the strength and diversity of the members we serve. Our focus is also on
                    teacher training, movement through music, collaboration, and heart. Summer Healing Brunswick welcomes
                    yoga practitioner members with good standing, inclusive of all levels and from all socio-economic
                    backgrounds that are in agreement with our Society Articles.</p> -->
                <!-- <div class='mouse-container mt-5'>
                    <div class='mouse'>
                        <span class='scroll-down'></span>
                    </div>
                </div> -->
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
                            <img :src="'/assets/static/img/about_image_1.jpg'" class=" h-full object-cover w-full mx-auto rounded-xl"
                                alt="" srcset="">

                        </div>
                        <div>
                            <img :src="'/assets/static/img/about_image_2.jpg'" class=" h-full w-auto mx-auto rounded-xl"
                                alt="" srcset="">

                        </div>


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
    // props: ["subscriptionplans", "courses"],
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