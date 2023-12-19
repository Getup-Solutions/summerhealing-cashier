<template>
    <div class="">
        <!-- Main Hero Secction -->
        <div class="bg-cover bg-center h-[268px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-1 mb-10">Contact Us</h1>
            </div>
        </div>
        <div class="max-w-4xl mx-auto px-6 md:px-0">
            <!-- Second section paras and images -->
            <div class="py-10 md:py-20">
                <div>
                    <div class="grid md:grid-cols-2 md:gap-10">
                        <div class="flex flex-col gap-4">
                            <a href="tel:+61432293294" class="group/tel">
                                <div class="flex gap-2">
                                    <component :is="PhoneIcon" class="w-5 h-5 text-sh_yellow group-hover/tel:text-white">
                                    </component>
                                    <p class="text-base text-sh_yellow font-sh_font_para group-hover/tel:text-white"> (+61)
                                        432293294 </p>
                                </div>
                            </a>
                            <a href="mailto:Info@summerhealing.org" class="group/mail">
                                <div class="flex gap-2">
                                    <component :is="EnvelopeIcon"
                                        class="w-5 h-5 text-sh_yellow group-hover/mail:text-white">
                                    </component>
                                    <p class="text-base text-sh_yellow font-sh_font_para group-hover/mail:text-white"> (+61)
                                        432293294 </p>
                                </div>
                            </a>
                            <div>
                                <p class="text-sm font-sh_font_para text-sh_yellow mb-4">

                                    Physical Location:<br />
                                    Australian Service Office<br />

                                    c/o temporary post location Postal Official:<br /> 156, Brunswick,

                                    Victoria [3056] Australia Mob:<br />(+61) 432293294
                                </p>
                                <p class="text-sm font-sh_font_para text-sh_yellow">
                                    Service Addresses:<br />

                                    International Office<br />

                                    9169 W State St #1054<br />

                                    Garden City, ID [83714]<br />

                                    United States
                                </p>
                            </div>


                        </div>
                        <div>

                            <div class="grid md:grid-cols-2 gap-3 bg-white/10 p-4 rounded-xl">

                                <div class=" col-span-2 border-b border-b-white/20">
                                    <h4 class="mb-6 text-center">Have Any Questions?</h4>
                                </div>

                                <FormSimpleInput :label="'First Name'" :name="'first_name'" :type="'text'"
                                    v-model="contactForm.first_name" :error="errors.first_name">
                                </FormSimpleInput>
                                <FormSimpleInput :label="'Last Name'" :name="'last_name'" :type="'text'"
                                    v-model="contactForm.last_name" :error="errors.last_name">
                                </FormSimpleInput>
                                <FormSimpleInput :label="'Email'" :name="'email'" :type="'email'"
                                    v-model="contactForm.email" :error="errors.email">
                                </FormSimpleInput>
                                <FormSimpleInput :label="'Phone Number'" :name="'phone_number'" :placeholder="'+61'"
                                    :type="'text'" v-model="contactForm.phone_number" :error="errors.phone_number">
                                </FormSimpleInput>
                                <div class=" col-span-2">
                                    <FormTextArea :label="'Message'" :name="'message'" v-model="contactForm.message"
                                        :error="errors.message">
                                    </FormTextArea>
                                </div>
                                <div class="col-span-2">
                                    <button
                                        class="sh-button font-semibold text-sh_darker_blue rounded-lg flex justify-center items-center w-full gap-2"
                                        @click.prevent="submitContactForm">Submit
                                    </button>
                                </div>

                                <!-- <FormSimpleInput :label="'Source'" :name="'source'" :type="'text'" v-model="contactForm.source"
                                    :error="errors.source">
                                </FormSimpleInput> -->
                            </div>
                        </div>


                    </div>

                </div>

            </div>

            <div class="grid w-full mx-auto">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.558275668812!2d144.95823497588765!3d-37.77695443193518!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65ccb29642bbb%3A0x93b692d394d648be!2sSummer%20Healing%20Society!5e0!3m2!1sen!2sin!4v1700535475440!5m2!1sen!2sin"
                    class="w-full h-96 rounded-xl" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
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
    props: ["errors"],
    data() {
        return {
            contactForm: {}
        }
    },
    methods: {
        submitContactForm() {
            router.post("/contact", this.contactForm, {
                preserveState: true,
                preserveScroll: true,
                only: ["errors","flash"],
            })
        }
    }
}
</script>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import JoinOurTelegramGroup from '../../Shared/JoinOurTelegramGroup.vue';
import Card from "../../Shared/Card.vue";
import { PhoneIcon, EnvelopeIcon } from "@heroicons/vue/24/solid";
import FormSimpleInput from "../../Shared/FormElements/FormSimpleInput.vue";
import FormTextArea from "../../Shared/FormElements/FormTextArea.vue";


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