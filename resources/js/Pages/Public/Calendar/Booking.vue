<template>
    <div class=" h-10 flex items-center justify-between p-4 max-w-6xl mx-auto mt-6">
        <div>
            <Link href="/calendar" class="flex items-center gap-2 group">
            <ArrowLeftCircleIcon class=" h-6 w-auto text-white group-hover:text-blue-300 group-hover:cursor-pointer">
            </ArrowLeftCircleIcon>
            <p class="text-white text-sm group-hover:cursor-pointer group-hover:text-blue-300">Back to <br />Calendar</p>
            </Link>

        </div>
        <div>
            <!-- <h3>Book an Event</h3> -->
        </div>
        <div>
            <!-- <h3>dd</h3> -->
        </div>
    </div>
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-y-10 mt-4 md:mt-20 md:divide-x px-4 mb-10">
        <div class="md:col-span-2 md:pr-10">
            <div class="grid md:grid-cols-2 gap-y-4 md:gap-8">
                <div>
                    <img :src="eventable.thumbnail_url" class=" w-full h-auto rounded-lg" alt="">

                    <p class="text-white mt-4 text-sm">Time: {{ eventTime }}</p>
                    <p class="text-white mt-2 text-sm">Date: {{ eventDate }}</p>
                </div>
                <div class="">
                    <h2>{{ eventTitle }}</h2>
                    <p class=" text-white mt-1 text-sm">{{ eventable.description }}</p>
                </div>
            </div>
            <!-- <img :src="eventable.thumbnail_url" class=" w-40 h-40 rounded-full mx-auto" alt="">
            <h2 class="text-center mt-4">{{ eventTitle }}</h2>
            <p class="text-center text-white mt-4 text-sm w-1/2 mx-auto">{{ eventable.description }}</p>
            <div class="w-1/2 mx-auto grid grid-cols-2">
                <p class="text-white mt-4 text-sm">Time: {{ eventTime }}</p>
                <p class="text-white text-right mt-4 text-sm w-3/4 mx-auto">Date: {{ eventDate }}</p>
            </div>   -->
            <!-- <h2 class="text-center mt-10">Book Event</h2>
            <div class="grid gap-2 max-w-sm mx-auto mt-6">
                <FormSelect :label="'Select User Email'" :name="'user_id'" v-model="bookingInfo.user_id"
                    :error="errors.user_id" :optionsArray="users" :optionName="'email'" :optionValue="'id'">
                </FormSelect>
                <Button
                    @click.prevent="createRequest({ url: '/calendar/book-event', data: bookingInfo, only: ['flash', 'errors', 'bookings'] })"
                    :text="'Book for User'" :color="'blue'" :fullWidth="true"></Button>
            </div> -->
            <!-- <p class="text-white mt-4 text-sm w-1/2 mx-auto">Time: {{ eventable.description }}</p> -->
        </div>
        <div class="md:pl-10 flex flex-col justify-center">
            <div>
                <h3 class="text-center w-full">Book Event</h3>
                <div class="grid gap-2 md:max-w-sm w-full mx-auto mt-6">
                    <FormSelect :label="'Select User Email'" :name="'user_id'" v-model="bookingInfo.user_id"
                        :error="errors.user_id" :optionsArray="users" :optionName="'email'" :optionValue="'id'">
                    </FormSelect>
                    <Button
                        @click.prevent="createRequest({ url: '/calendar/book-event', data: bookingInfo, only: ['flash', 'errors', 'bookings'] })"
                        :text="'Book for this User'" :color="'blue'" :fullWidth="true"></Button>
                </div>
            </div>
            <div class="mt-8" v-if="bookings.length">
                <h3 class="text-center">Bookings - ({{ totalBookingsForThis }}/{{ eventSize }})</h3>
                <div class="mt-4">
                    <div class="flex justify-between bg-blue-900 py-2 px-2 rounded-xl my-2" v-for="booking in bookings"
                        :key="booking">
                        <p class="text-white pl-4">{{ booking.user.full_name }}</p>
                        <p class="px-1 py-1 bg-red-600 text-gray-200 rounded-full text-center text-sm w-20 hover:bg-red-800 cursor-pointer"
                            @click.prevent="deleteRequest({ url: '/calendar/book-event/', dataId: booking.id, only: ['flash', 'errors', 'bookings'] })">
                            Cancel
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="my-10 grid md:gap-0 border border-gray-300 rounded-2xl py-4 max-w-lg mx-auto">
            <div class="px-4">
                <h4 class="text-center">Bookings</h4>
                <div class="mt-4" v-if="bookings.length">
                    <div class="flex justify-between bg-blue-900 py-2 px-2 rounded-xl my-2" v-for="booking in bookings"
                        :key="booking">
                        <p class="text-white pl-4">{{ booking.user.full_name }}</p>
                        <p class="px-1 py-1 bg-red-600 text-gray-200 rounded-full text-center text-sm w-20 hover:bg-red-800 cursor-pointer"
                            @click.prevent="deleteRequest({ url: '/calendar/book-event/', dataId: booking.id, only: ['flash', 'errors', 'bookings'] })">
                            Cancel
                        </p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>
<script>
export default {
    props: ["eventTitle", "eventable", "eventTime", "eventDate", "errors", "users", "eventId", "calendarId", "bookings","totalBookingsForThis","eventSize"],
    data() {
        return {
            bookingInfo: { event_id: this.eventId, calendar_id: this.calendarId }
        }
    }
}
</script>
<script setup>
import FormSelect from "../../../Shared/FormElements/FormSelect.vue";
import Button from "../../../Shared/FormElements/Button.vue";
import {
    ClockIcon,
    ArrowLeftCircleIcon
} from "@heroicons/vue/24/solid";
import { createRequest, deleteRequest } from '../../../main.js'
</script>