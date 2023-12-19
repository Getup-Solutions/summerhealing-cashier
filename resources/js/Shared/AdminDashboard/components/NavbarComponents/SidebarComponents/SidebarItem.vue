<template>
    <div v-if="!sidebarItem.hideIf">
        <li v-if="sidebarItem.subMenu">
            <button type="button"
                class="flex items-center w-full p-2 text-gray-900 transition cursor-pointer duration-75 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                
                :class="menuToggle ? (this.$page.url.startsWith(sidebarItem.urlStartsWith) ? 'dark:bg-blue-800 bg-blue-200' : ''): (this.$page.url.startsWith(sidebarItem.urlStartsWith) ? 'dark:bg-blue-800 bg-blue-200' : '')"
                :aria-controls="`dropdown-example-${sidebarItem.name}`"
                :data-collapse-toggle="`dropdown-example-${sidebarItem.name}`">
                <component :is="sidebarItem.icon"
                    :class="{ 'dark:text-white': menuToggle || this.$page.url.startsWith(sidebarItem.urlStartsWith) }"
                    class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                </component>
                <span class="flex-1 ml-3 mr-1 text-left whitespace-nowrap" sidebar-toggle-item>{{ sidebarItem.name }}</span>
                <!-- <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg> -->
                <!-- <svg v-if="menuToggle" class="w-3 h-3 text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1" />
                </svg>
                <svg v-else
                    class="w-3 h-3 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 8 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 13 5.7-5.326a.909.909 0 0 0 0-1.348L1 1" />
                </svg> -->



            </button>
            <ul :id="`dropdown-example-${sidebarItem.name}`" class="p-2 space-y-2  rounded-lg mt-2"
                :class="this.$page.url.startsWith(sidebarItem.urlStartsWith) ? 'bg-gray-300 dark:bg-blue-600/20 block':'hidden bg-gray-50 dark:bg-black/10'">
                <li v-for="item in sidebarItem.subMenu" :key="item">
                    <Link :href="item.link" v-if="!item.hideIf"
                        class="flex items-center w-full p-2 text-gray-900 cursor-pointer transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        :class="{'dark:bg-blue-800 bg-gray-100':$page.url === item.url }">
                    {{ item.name }}</Link>
                </li>
            </ul>
        </li>
        <li v-else>
            <Link :href="sidebarItem.link" :as="sidebarItem.as" :type="sidebarItem.type" :method="sidebarItem.method"
                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                :class="{ 'w-full': sidebarItem.type === 'button' }">
            <component :is="sidebarItem.icon"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
            </component>
            <span class="flex-1 ml-3 whitespace-nowrap text-left">{{ sidebarItem.name }}</span>
            <span
                class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300"
                v-if="sidebarItem.notification">{{ sidebarItem.notification }}</span>
            </Link>
        </li>
    </div>
</template>

<script>
export default {
    props: ["sidebarItem"],
    data() {
        return {
            menuToggle: null
        }
    },
    mounted() {
        this.menuToggle = false
        console.log(this.sidebarItem.name);
        console.log(this.$page.url);
        console.log(this.$page.url.startsWith(this.sidebarItem.urlStartsWith));
    },    
};
</script>
