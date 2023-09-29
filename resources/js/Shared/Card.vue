<template>
    <div
        class="min-h-full bg-white border border-gray-200 rounded-3xl shadow dark:bg-sh_darker_blue dark:border-gray-700  dark:hover:border-gray-400 dark:hover:shadow-xl dark:hover:shadow-sh_yellow/40 dark:hover:bg-black/5"
        :class="fullWidth ? 'max-w-5xl w-full': 'max-w-md'">
        <a href="#" class="">
            <div class="rounded-t-3xl h-80 w-full bg-cover bg-center opacity-70"
                :style="`background-image: url('${image}');`"></div>
        </a>
        <div class="p-5 flex flex-col h-80 min-h-max justify-between">
            <a href="#">
                <h5 class="mb-6 tracking-tight line-clamp-2 text-left" :class="`sh-head-${titleSize ?? '3'}`">{{ title }}</h5>
            </a>
            <div>
                <p class="mb-3 sh-para text-left line-clamp-3 text-sm">{{
                    text }}</p>
                <p class="mb-6 sh-para text-left text-sm font-medium" v-if="validity">Validity : {{
                    validity
                }}</p>
                <p class="mb-6 sh-para text-left text-sm font-medium" v-if="price">Price (AUD) : {{
                    price
                }}</p>
            </div>
            <Link
            :href="href">
            <button class="sh-button flex justify-center items-center md:w-40 gap-1">{{ buttonText }}
                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg></button>
            </Link>


        </div>
    </div>
</template>

<script>
export default {
    props: ["image","href", "title","titleSize", "text", "price", "validity", "buttonText","fullWidth"]
}
</script>