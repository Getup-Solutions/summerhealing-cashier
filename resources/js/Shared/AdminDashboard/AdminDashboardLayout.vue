<template>
    <Notification></Notification>
    <NavSidebar :siteLogo="$page.props.siteLogo" :siteName="$page.props.siteName"
        :userAvatar="$page.props.logged_user.avatar" :userName="$page.props.logged_user.full_name"
        :userEmail="$page.props.logged_user.email" :navMenu="navMenu" :sidebarMenu="sidebarMenu">
        <div class="md:px-4 py-4 sm:ml-64 dark:bg-gray-900">
            <div class="px-2 md:px-4  pt-4 pb-32 mt-14 min-h-screen">
                <slot></slot>
            </div>
            <Footer></Footer>
        </div>
    </NavSidebar>
</template>
<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import NavSidebar from "./components/NavSidebar.vue";
import Footer from "./components/Footer.vue";

// import Sidebar from "./components/Sidebar.vue";
import Notification from "./components/Notification.vue";
import {
    ChartPieIcon,
    CogIcon,
    ArrowRightOnRectangleIcon,
    UserIcon,
    DocumentTextIcon,
    UserCircleIcon,
    BookOpenIcon,
    BookmarkIcon,
    UserGroupIcon,
    Cog6ToothIcon,
    IdentificationIcon,
    DocumentPlusIcon,
    CubeIcon,
    SparklesIcon
} from "@heroicons/vue/24/solid";
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
</script>
<script>
export default {
    data() {
        return {
            navMenu: [
                {
                    name: "Dashboard",
                    link: "/admin/dashboard",
                    method: "get",
                },
                {
                    name: "Profile Info",
                    link: "/admin/dashboard/profile-info",
                    method: "get",
                },
                {
                    name: "Sign out",
                    link: "/logout",
                    method: "post",
                    as: "button", type: "button",

                },
            ],
            sidebarMenu: [
                {
                    name: "Dashboard",
                    link: "/admin/dashboard",
                    method: "get",
                    icon: ChartPieIcon,

                    urlStartsWith:''
                },
                {
                    name: "Leads",
                    link: "/admin/dashboard/leads",
                    method: "get",
                    icon: DocumentPlusIcon,
                    urlStartsWith:'/admin/dashboard/leads',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/leads/create",
                            url:'/admin/dashboard/leads/create',
                        },
                        {
                            name: "All leads",
                            link: '/admin/dashboard/leads',
                            url:'/admin/dashboard/leads',
                        },
                    ],
                },
                {
                    name: "Subscription Plans",
                    link: "/admin/dashboard/subscriptionplans",
                    method: "get",
                    icon: IdentificationIcon,
                    urlStartsWith:'/admin/dashboard/subscriptionplans',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/subscriptionplans/create",
                            url:'/admin/dashboard/subscriptionplans/create',
                        },
                        {
                            name: "All subscriptionplans plans",
                            link: '/admin/dashboard/subscriptionplans',
                            url:'/admin/dashboard/subscriptionplans',
                        },
                    ],
                },
                {
                    name: "Courses",
                    link: "/admin/dashboard/courses",
                    method: "get",
                    icon: BookOpenIcon,
                    urlStartsWith:'/admin/dashboard/courses',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/courses/create",
                            url:'/admin/dashboard/courses/create',
                        },
                        {
                            name: "All courses",
                            link: '/admin/dashboard/courses',
                            url:'/admin/dashboard/courses',
                        },
                    ],
                },
                {
                    name: "Facilities",
                    link: "/admin/dashboard/facilities",
                    method: "get",
                    icon: CubeIcon,
                    urlStartsWith:'/admin/dashboard/facilities',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/facilities/create",
                            url:'/admin/dashboard/facilities/create',
                        },
                        {
                            name: "All facilities",
                            link: '/admin/dashboard/facilities',
                            url:'/admin/dashboard/facilities',
                        },
                    ],
                },
                {
                    name: "Trainers",
                    link: "/admin/dashboard/trainers",
                    method: "get",
                    icon: UserIcon,
                    urlStartsWith:'/admin/dashboard/trainers',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/trainers/create",
                            url:'/admin/dashboard/trainers/create',
                        },
                        {
                            name: "All trainers",
                            link: '/admin/dashboard/trainers',
                            url:'/admin/dashboard/trainers',
                        },
                    ],
                },
                {
                    name: "Trainings",
                    link: "/admin/dashboard/trainings",
                    method: "get",
                    icon: SparklesIcon,
                    urlStartsWith:'/admin/dashboard/trainings',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/trainings/create",
                            url:'/admin/dashboard/trainings/create',
                        },
                        {
                            name: "All trainings",
                            link: '/admin/dashboard/trainings',
                            url:'/admin/dashboard/trainings',
                        },
                    ],
                },
                {
                    name: "Users",
                    link: "/admin/dashboard/users",
                    method: "get",
                    icon: UserGroupIcon,
                    urlStartsWith:'/admin/dashboard/users',
                    subMenu: [
                        {
                            name: "Create",
                            link: "/admin/dashboard/users/create",
                            url:'/admin/dashboard/users/create',
                        },
                        {
                            name: "All users",
                            link: "/admin/dashboard/users",
                            url:'/admin/dashboard/users',
                        },
                    ],
                },
                {
                    name: "Personal Info",
                    link: "/admin/dashboard/profile-info",
                    method: "get",
                    icon: Cog6ToothIcon,
                    urlStartsWith:'/admin/dashboard/profile-info',
                },
                {
                    name: "Sign out",
                    link: "/admin/logout",
                    method: "post",
                    as: "button", type: "button",
                    icon: ArrowRightOnRectangleIcon,
                },
            ],
        };
    },
};
</script>
