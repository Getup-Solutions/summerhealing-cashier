<template>
    <div class=" min-h-screen bg-white dark:bg-gray-50">
        <CalenderHeader :calender="calender" v-model="selectedDate"></CalenderHeader>
        <div class="p-10">
            <div class="grid gap-2">
                <div class=" max-w-2xl" v-for="sessionEvent in sessionEvents" :key="sessionEvent">
                    <div class=" border-b border-b-gray-400 p-4 grid grid-cols-6">
                        <div>


                        </div>
                        <div class="col-span-4 grid gap-2">
                            <p class="text-xl font-medium text-gray-800">{{ sessionEvent["event_title"] }}</p>
                            <p class="text-sm font-medium text-gray-800">Trainer: {{ sessionEvent["trainer_names"] }}</p>
                            <div class="bg-gray-400 rounded-full w-40 text-xs font-semibold py-1 flex gap-1 justify-center">
                                <ClockIcon class="text-gray-900 w-4"></ClockIcon> {{ tConvert(sessionEvent.start_time) }} -
                                <ClockIcon class="text-gray-900 w-4">
                                </ClockIcon>{{ tConvert(sessionEvent.end_time) }}
                            </div>
                            <div class="bg-green-700 rounded-full w-24 text-white text-xs font-semibold py-1 flex gap-1 justify-center">
                                {{ sessionEvent["size"]}} Available
                            </div>
                            <div class="rounded-full text-sm cursor-pointer hover:bg-blue-800 bg-sh_dark_blue py-2 px-2 w-40 text-center text-white">Mark Attendence {{ markAttendence(sessionEvent.start_time) }}</div>
                            <!-- <h3>{{ calender[selectedDate]["events"] }}</h3> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
export default {
    props: ["calender"],
    data() {
        return {
            selectedDate: 0,
            circumference: 30 * 2 * Math.PI,

        }
    },
    computed: {
        sessionEvents() {
            if (this.calender[this.selectedDate]["events"]) {
                return this.calender[this.selectedDate]["events"].filter((event) => event["eventable_type"] == 'App\\Models\\Session')
            }
        },

    },
    methods: {        
        markAttendence(time){
            time = time.slice(0, -3);
            console.log(time);
            console.log(this.calender[this.selectedDate]["formated_date"]);
            var diffMs = new Date(this.calender[this.selectedDate]["formated_date"]+' '+time) - new Date();
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
import CalenderHeader from "../../../Shared/Calender/CalenderHeader.vue";
</script>