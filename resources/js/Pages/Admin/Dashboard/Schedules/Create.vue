<template>
    <!-- Modal content -->
    <Modal :showError="showError" :modalHeadingText="type ? `Schedule ${type}` : 'Create new Schedule'" :modalHeadingResetButton="true"
        :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <div class="col-span-2 grid md:grid-cols-2 gap-6 gap-y-4">
                    <FormSimpleInput :label="'Start Date'" :name="'start_date'" :type="'date'"
                        v-model="scheduleInfo.start_date" :error="errors['scheduleInfo.start_date']">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'End Date'" :name="'end_date'" :type="'date'" :min="scheduleInfo.start_date"
                        v-model="scheduleInfo.end_date" :error="errors['scheduleInfo.end_date']">
                    </FormSimpleInput>
                    <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Days</label>
                    <div class="col-span-2 grid grid-cols-7 gap-4">
                        <div class="" v-for="day in getDays" :key="day.id">
                            <input type="checkbox" :id="`react-option-${day.day_name}`" class="hidden peer/trainer"
                                :value="day.id" v-model="daysSelected" required="">
                            <label :for="`react-option-${day.day_name}`"
                                class="inline-flex items-center p-3 justify-between w-full text-gray-900 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-white dark:border-gray-700 peer-checked/trainer:border-blue-600 peer-checked/trainer:shadow-xl peer-checked/trainer:shadow-blue-800 hover:text-gray-800 dark:peer-checked/trainer:text-white peer-checked/trainer:text-gray-900 hover:bg-gray-50 dark:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                                <div class="flex items-center space-x-4">
                                    <div class="font-medium dark:text-white">
                                        <div>{{ day.day_name }}</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div v-if="errors['scheduleInfo.days']" v-text="errors['scheduleInfo.days']"
                        class="text-red-500 col-span-2 text-xs"></div>
                </div>
            </div>
            <!-- Events for days  -->
            <div v-for="(dayEvent, index) in daysEvent" :key="index">
                <div v-if="getDaysSelected.includes(index + 1)" class="dark:bg-blue-500/10 p-4 rounded-lg ">
                    <label for="" class="block mb-2 text-base font-medium text-gray-900 dark:text-white">Schedule
                        for {{ dayNames[index] }} </label>
                    <div class="dark:bg-blue-800/30 p-4 rounded-lg mt-4" v-for="(event, index) in dayEvent" :key="event">
                        <div class="grid grid-cols-3 gap-10" v-if="dayEvent.length > 0">
                            <div>
                                <FormSimpleInput :label="'Start Time'" :name="'start_time'" :type="'time'" :min="'00:00'"
                                    v-model="event.start_time" :required="true"></FormSimpleInput>
                            </div>
                            <div>
                                <FormSimpleInput :label="'End Time'" :name="'end_time'" :type="'time'"
                                    v-model="event.end_time"></FormSimpleInput>
                            </div>
                            <div>
                                <FormSimpleInput :label="'Size'" :name="'size'" :type="'number'" :min="0"
                                    v-model="event.size"></FormSimpleInput>
                            </div>
                        </div>
                        <div class="mt-4" v-if="dayEvent.length > 0">
                            <Button @click.prevent="dayEvent.splice(index, 1)" :text="'Remove Event'" :color="'red'"
                                :fullWidth="true"></Button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <Button @click.prevent="daysEvent[index].push({ event_title:'',eventable_type:scheduleableType, start_time: '00:00', end_time: '00:00', size: 5 })"
                            :text="'Create new Event'" :color="'blue'" :fullWidth="true"></Button>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <Button
                @click.prevent="createSchedule"
                :text="'Create Schedule'" :color="'blue'"></Button>
        </template>
    </Modal>
</template>
<script>
export default {
    props: ["errors", "days", "type", "scheduleInfoData","createURL","createData","scheduleableType","showError"],
    emits:['scheduleCreated'],
    data() {
        return {
            scheduleInfo: this.scheduleInfoData ?? {scheduleable_type:this.scheduleableType,scheduleable_id:null},
            daysSelected: [],
            dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            sunEvents: [], monEvents: [], tueEvents: [], wedEvents: [], thuEvents: [], friEvents: [], satEvents: [],
            daysEvent: []
        };
    },
    mounted() {
        this.sunEvents = []
        this.monEvents = []
        this.tueEvents = []
        this.wedEvents = []
        this.thuEvents = []
        this.friEvents = []
        this.satEvents = []
        this.daysEvent = [this.sunEvents, this.monEvents, this.tueEvents, this.wedEvents, this.thuEvents, this.friEvents, this.satEvents]
    },
    computed: {
        getDaysDifference() {
            if ((typeof this.scheduleInfo.end_date === 'undefined') || (typeof this.scheduleInfo.start_date === 'undefined')) {
                return 8;
            } else {
                return ((new Date(this.scheduleInfo.end_date)) - (new Date(this.scheduleInfo.start_date))) / (1000 * 60 * 60 * 24)
            }
        },
        getDays() {
            if (this.getDaysDifference > 7) {
                return this.days;
            }
            else if (7 >= this.getDaysDifference > 0) {
                var i = 0;
                var newDays = []
                var d = ((new Date(this.scheduleInfo.start_date)).getDay())
                while (i < this.getDaysDifference + 1) {
                    newDays.push(this.days[(d + i) % 7])
                    if (i < 6) {
                        i++
                    } else {
                        break
                    }
                }
                return newDays
            } else if (this.getDaysDifference === 0) {
                return this.days[((new Date(this.scheduleInfo.start_date)).getDay())];
            }
        },
        getDaysSelected() {
            return this.daysSelected.filter((element) => {
                return this.getDays.map((element) => element.id).includes(element)
            });
        }
    },
    methods:{
        createSchedule(){
            this.$emit('scheduleCreated',false)
            createRequest({ url: this.createURL, data: { ...this.createData, scheduleInfo: { ...this.scheduleInfo, days: this.getDaysSelected, daysEvent: this.daysEvent } }, only: ['flash', 'errors'] })
        }
    }
};
</script>
<script setup>
import Button from "../../../../Shared/FormElements/Button.vue";
import FormSimpleInput from "../../../../Shared/FormElements/FormSimpleInput.vue";
import FormFileUploadSingle from "../../../../Shared/FormElements/FormFileUploadSingle.vue";
import Modal from "../../../../Shared/Modal/Modal.vue";
import FormCheckBox from "../../../../Shared/FormElements/FormCheckBox.vue";
import FormTextArea from "../../../../Shared/FormElements/FormTextArea.vue";
import FormSelect from "../../../../Shared/FormElements/FormSelect.vue";

import { createRequest } from '../../../../main.js'

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>