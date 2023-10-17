<template>
    <div class="dark:bg-[#010731] py-4 bg-zinc-950">
        <h1 class="text-lg font-poppins dark:text-white text-white text-center font-semibold">Events</h1>
    </div>
    <div class="dark:bg-[#010731] bg-zinc-950">
        <div class="p-2 flex gap-2 overflow-x-scroll no-scrollbar">
            <div class="rounded-xl bg-white/10 hover:bg-white/20 text-white cursor-pointer" v-for="(day,index) in calender"
                :key="day" :class="{ ' bg-blue-700/50 text-gray-900': day.is_today == 1,'border-2 bg-blue-600/90 hover:bg-white/50 text-gray-900 border-white': day === selectedDate }">
                <div class=" w-14 h-14 grid place-content-center" @click="selectDate(day,index)">
                    <p class="text-base  text-center font-semibold">{{ day.day }}</p>
                    <p class="text-xs  text-center">{{ day.day_name.substring(0, 3) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["calender", "modelValue"],
    emits: ["update:modelValue"],
    data(){
        return {
            selectedDate:{}
        }
    },
    methods:{
        selectDate(day,index){
            this.selectedDate = day;
            this.$emit('update:modelValue', index);
        }
    },
    mounted(){
        this.selectedDate = this.calender[0]
        this.$emit('update:modelValue', 0);
    }
}
</script>