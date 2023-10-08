<template>
    <div class="rounded-lg h-fit p-4 md:p-2 md:px-4 dark:border-2 dark:border-gray-500 dark:bg-white/20">
        <div class="px-2 md:px-2 pt-4 pb-1">
            <p class="mb-3 sh-para text-left">Price : {{ item.price }} AUD</p>
            <p class="mb-3 sh-para text-left" v-if="item.validity">Validity : {{ item.validity }} days
            </p>
        </div>
        <Link :href="`/subscription-plan/checkout/${item.id}`">
            <button class="sh-button rounded-lg flex justify-center items-center w-full gap-2">Pay Now
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </button>
        </Link>


    </div>
</template>
<script>
export default {
    props: ["item","type","url"]
}
</script>
<script setup>
import { payNow } from '../main';
</script>