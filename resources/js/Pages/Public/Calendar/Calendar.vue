<template>
    <div class=" min-h-screen bg-white dark:bg-gray-50">
        <CalendarHeader :calendar="calendar" v-model="selectedDate"></CalendarHeader>
        <div class="md:px-10 md:py-8">
            <div class="grid gap-2">
                <div class=" max-w-2xl" v-for="sessionEvent in sessionEvents" :key="sessionEvent">
                    <div class=" border-b border-b-gray-400 pb-4 grid grid-cols-6">
                        <div class="col-span-4 grid gap-2">
                            <p class="text-xl font-medium text-gray-800">{{ sessionEvent["event_title"] }}</p>
                            <p class="text-sm font-medium text-gray-800">Trainer: {{ sessionEvent["trainer_names"] }}</p>
                            <div class="bg-gray-400 rounded-full w-40 text-xs font-semibold py-1 flex gap-1 justify-center">
                                <ClockIcon class="text-gray-900 w-4"></ClockIcon> {{ tConvert(sessionEvent.start_time) }} -
                                <ClockIcon class="text-gray-900 w-4">
                                </ClockIcon>{{ tConvert(sessionEvent.end_time) }}
                            </div>
                            <div
                                class="bg-green-700 rounded-full w-24 text-white text-xs font-semibold py-1 flex gap-1 justify-center">
                                {{ sessionEvent["size"] }} Available
                            </div>
                            <div class="rounded-full text-sm cursor-pointer py-1.5 px-2 w-36 text-center text-white" :class="eventStatus(sessionEvent).buttonLink ? 'hover:bg-blue-800 bg-sh_dark_blue':'bg-gray-700'"
                                @click="eventAction(sessionEvent, eventStatus(sessionEvent).buttonLink)">{{
                                    eventStatus(sessionEvent).buttonText }}</div>
                            <!-- <h3>{{ calendar[selectedDate]["events"] }}</h3> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
export default {
    props: ["calendar"],
    mounted() {
        console.log(this.calendar.findIndex((element) => element['is_today'] === true))
        this.selectedDate = this.calendar.findIndex((element) => element['is_today'] === true);
    },
    data() {
        return {
            selectedDate: 0,
            circumference: 30 * 2 * Math.PI,

        }
    },
    computed: {
        sessionEvents() {
            if (this.calendar[this.selectedDate]["events"]) {
                return this.calendar[this.selectedDate]["events"].filter((event) => event["eventable_type"] == 'App\\Models\\Session')
            }
        },

    },
    methods: {
        eventStatus(event) {
            console.log(this.calendar[this.selectedDate]["is_today"]);
            if (this.calendar[this.selectedDate]["is_today"]) {
                console.log(new Date() - new Date(this.calendar[this.selectedDate]['formated_date']) > 0);
                var timeDifference = event.start_time.slice(0, 2) - new Date().getHours()
                // console.log(new Date().getHours());
                if (timeDifference < 0) {
                    return { buttonLink: false, buttonText: 'Event Passed' }
                } else if (timeDifference > 2) {
                    return { buttonLink: 'calendar/book-event', buttonText: 'Book Now' }
                } else {
                    return { buttonLink: 'calendar/attendance-event', buttonText: 'Mark Attendence' }
                }
            } else if (new Date() - new Date(this.calendar[this.selectedDate]['formated_date']) > 0) {
                return { buttonLink: false, buttonText: 'Event Passed' }
            } else {
                return { buttonLink: 'calendar/book-event', buttonText: 'Book Now' }
            }
            // console.log(this.calendar[this.selectedDate]["is_today"]);
        },
        eventAction(event, url) {
            // console.log(event.id);
            var data = { event_id: event.id, calendar_id: this.calendar[this.selectedDate]['id'], date: this.calendar[this.selectedDate]['formated_date'], time: `${this.tConvert(event.start_time)} - ${this.tConvert(event.end_time)}` }
            console.log(data);
            if (url) {
                router.get(url, data)
            }
        },
        canAttendNow(event) {
            console.log(event.start_time.slice(0, 2))
            console.log(this.tConvert(event.start_time));
            if ((event.start_time.slice(0, 2) - new Date().getHours()) < 0) {
                return
            } event.start_time.slice(0, 2) - new Date().getHours()
            return event.start_time.slice(0, 2) - new Date().getHours()
        },
        markAttendence(time) {
            time = time.slice(0, -3);
            console.log(time);
            console.log(this.calendar[this.selectedDate]["formated_date"]);
            var diffMs = new Date(this.calendar[this.selectedDate]["formated_date"] + ' ' + time) - new Date();
            return Math.floor(diffMs / 86400000);
        },
        tConvert(time) {
            time = time.slice(0, -3);
            // Check correct time format and split into components
            time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice(1);  // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join(''); // return adjusted time or original string
        }
    }
}
</script>
<script setup>
import {
    ClockIcon
} from "@heroicons/vue/24/solid";
import CalendarHeader from "../../../Shared/Calendar/CalendarHeader.vue";
import { router } from "@inertiajs/vue3";
</script>