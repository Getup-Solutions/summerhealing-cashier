<template>
                <div class="bg-cover bg-center" style="background-image: url('/assets/static/img/home.jpeg');">
                <div class="py-10 md:py-20 grid dark:bg-sh_dark_blue/95">
                    <h1 class="sh-head-1 mb-10 md:mb-16">JOIN OUR TELEGRAM GROUP</h1>
                    <p class="sh-para text-center mb-10 md:mb-16">
                        wonderful way to meet other members of the society
                    </p>
                    <div class="flex items-center gap-2 md:gap-10 mb-20 justify-center">

                        <img class="w-12 h-12 md:w-36 md:h-36 p-1 rounded-full ring-2 ring-gray-300 dark:ring-sh_yellow"
                            v-for="i in 5" :key="i" :src="`/assets/static/img/member-${i}.webp`" alt="Bordered avatar">

                        <div
                            class="p-3 md:p-14 rounded-full ring-2 ring-gray-300 dark:ring-sh_yellow dark:hover:bg-sh_yellow group cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor"
                                class="w-6 h-6 md:w-8 md:h-8 text-sh_yellow dark:group-hover:text-sh_dark_blue">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                        </div>

                    </div>

                    <button class="sh-button mx-auto w-44 text-base dark:hover:shadow-xl">Join Now</button>
                </div>
            </div>
</template>
<script></script>