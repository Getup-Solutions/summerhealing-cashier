<template>
    <div class="">

        <div class="bg-cover bg-center h-[368px] " style="background-image: url('/assets/static/img/home_banner.webp');">
            <div class="bg-sh_dark_blue/70 place-content-center grid h-full px-6 py-36 md:p-36">
                <!-- <img :src="usePage().props.siteLogo" class=" h-48 w-auto mx-auto" alt="" srcset=""> -->
                <h1 class="sh-head-1 mb-10">TRAINING PROGRAMS</h1>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 md:px-0">
            <div class="py-10 md:py-20">
                <!-- <h1 class="sh-head-2 mb-10">trainingS</h1> -->

                <div>
                    <div class="grid gap-10">
                        <!-- <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all trainings</button> -->
                        <div class="grid md:grid-cols-1 gap-10 gap-y-10">

                            <div v-for="training in trainings" :key="training.id" class="mx-auto">
                                <Card :image="training.thumbnail_url" :title="training.title" :titleSize="'2'"
                                    :text="training.description" :buttonText="'Learn more'" :fullWidth="true"></Card>
                            </div>


                        </div>
                        <!-- <button class="sh-button mx-auto w-60 text-base dark:hover:shadow-xl">See all trainings</button> -->
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
    props: ["trainings"],
}
</script>
<script setup>
import Card from "../../Shared/Card.vue";
import JoinOurTelegramGroup from '../../Shared/JoinOurTelegramGroup.vue';
</script>