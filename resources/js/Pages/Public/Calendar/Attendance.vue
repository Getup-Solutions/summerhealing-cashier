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
            <!-- <h3>attendance an Event</h3> -->
        </div>
        <div>
            <!-- <h3>dd</h3> -->
        </div>
    </div>
    <div class="max-w-6xl mx-auto grid md:grid-cols-12 gap-y-10 mt-4 md:mt-20 md:divide-x px-4 mb-10">
        <div class="md:col-span-7 md:pr-10">
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
        </div>
        <div class="md:pl-5 flex flex-col justify-center md:col-span-5">
            <div>
                <h3 class="text-center w-full">Mark attendance</h3>
                <div class="grid gap-2 md:max-w-sm w-full mx-auto mt-6">
                    <FormSelect :label="'Select User Email'" :name="'user_id'" v-model="attendanceInfo.user_id"
                        :error="errors.user_id" :optionsArray="users" :optionName="'email'" :optionValue="'id'">
                    </FormSelect>
                    <Button
                        @click.prevent="createRequest({ url: '/calendar/attendance-event', data: attendanceInfo, only: ['flash', 'errors', 'attendances', 'bookingsNoAttendance'] })"
                        :text="'Mark attendance for this User'" :color="'blue'" :fullWidth="true"></Button>
                </div>
            </div>
            <div class="mt-8" v-if="attendances.length || bookingsNoAttendance.length">
                <h3 class="text-center">Attendances marked</h3>
                <div class="mt-4">
                    <div class="flex justify-between bg-blue-900 py-2 px-2 rounded-xl my-2"
                        v-for="booking in bookingsNoAttendance" :key="booking">
                        <p class="text-white pl-4">{{ booking.user.full_name }}</p>
                        <div class="flex gap-2">
                            <p class="px-1 py-1 bg-green-600 text-gray-200 rounded-full text-center text-sm w-36 hover:bg-green-800 cursor-pointer shadow-md shadow-green-500/50 hover:shadow-none"
                                @click.prevent="createRequest({ url: '/calendar/attendance-event', data: { event_id: this.eventId, calendar_id: this.calendarId, user_id: booking.user_id }, only: ['flash', 'errors', 'attendances', 'bookingsNoAttendance','users'] })">
                                Mark Attendence
                            </p>
                            <p class="px-1 py-1 bg-red-600 text-gray-200 rounded-full text-center text-sm w-20 hover:bg-red-800 cursor-pointer shadow-md shadow-red-500/50 hover:shadow-none"
                                @click.prevent="deleteRequest({ url: '/calendar/book-event/', dataId: booking.id, only: ['flash', 'errors', 'attendances', 'bookingsNoAttendance','users'] })">
                                Cancel
                            </p>
                        </div>

                    </div>
                    <div class="flex justify-between bg-blue-900 py-2 px-2 rounded-xl my-2"
                        v-for="attendance in attendances" :key="attendance">
                        <p class="text-white pl-4">{{ attendance.user.full_name }}</p>
                        <p class="px-1 py-1 bg-red-600 text-gray-200 rounded-full text-center text-sm w-20 hover:bg-red-800 cursor-pointer shadow-md shadow-red-500/50 hover:shadow-none"
                            @click.prevent="deleteRequest({ url: '/calendar/attendance-event/', dataId: attendance.id, only: ['flash', 'errors', 'attendances', 'bookingsNoAttendance','users'] })">
                            Cancel
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["eventTitle", "eventable", "eventTime", "eventDate", "errors", "users", "eventId", "calendarId", "attendances", "bookingsNoAttendance"],
    data() {
        return {
            attendanceInfo: { event_id: this.eventId, calendar_id: this.calendarId }
        }
    }
}
</script>
<script setup>
import FormSelect from "../../../Shared/FormElements/FormSelect.vue";
import Button from "../../../Shared/FormElements/Button.vue";
import {
    ArrowLeftCircleIcon
} from "@heroicons/vue/24/solid";
import { createRequest, deleteRequest } from '../../../main.js'
</script>