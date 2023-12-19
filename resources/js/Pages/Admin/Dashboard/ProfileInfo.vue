<template>
    <div class="w-full md:w-full lg:w-3/4 xl:w-1/2">
        <h2 class="my-4">Profile Settings</h2>
        <div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <FormSimpleInput :label="'First Name'" :name="'first_name'" @change="nameToSlug()" :type="'text'"
                        v-model="userInfo.first_name" :error="errors.first_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Last Name'" :name="'last_name'" @change="nameToSlug()" :type="'text'"
                        v-model="userInfo.last_name" :error="errors.last_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'user Email'" :name="'email'" :type="'email'" v-model="userInfo.email"
                        :error="errors.email">
                    </FormSimpleInput>
                </div>
                <div>
                    <FormFileUploadSingle @fileChange="(file) => (userInfo.avatar = file[0])" :label="'Profile Image'"
                        :oldImageLink="user.avatar_url" :rounded="true" :name="'avatar'" :hideInputBox="true"
                        :error="errors.avatar"></FormFileUploadSingle>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- <FormSimpleInput :label="'Profile Link'" :name="'slug'" :type="'text'" :preText="'@'" @change="createSlug()"
                    v-model="userInfo.slug" :error="errors.slug">
                </FormSimpleInput>
                <FormSimpleInput :label="'Designation'" :name="'designation'" :type="'text'"
                    v-model="userInfo.designation" :error="errors.designation">
                </FormSimpleInput> -->
                <FormSimpleInput :label="'Date of birth'" :name="'dob'" :type="'date'" v-model="userInfo.dob"
                    :error="errors.dob">
                </FormSimpleInput>
                <FormSimpleInput :label="'Phone Number'" :name="'phone_number'" :placeholder="'+91'" :type="'text'"
                    v-model="userInfo.phone_number" :error="errors.phone_number">
                </FormSimpleInput>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <FormSimpleInput :label="'Password'" :name="'password'" :type="'password'" v-model="userInfo.password"
                    :error="errors.password">
                </FormSimpleInput>
                <FormSimpleInput :label="'Confirm Password'" :name="'confirm_password'" :type="'password'"
                    v-model="userInfo.password_confirmation" :error="errors.password">
                </FormSimpleInput>
            </div>
            <Button @click.prevent="
                editRequest({
                    url: '/admin/dashboard/users/',
                    data: userInfo,
                    dataId: userInfo.id,
                    only: ['flash', 'errors', 'user'],
                })
                " :text="'Update Info'" :color="'blue'" class="my-4"></Button>
        </div>
    </div>
</template>
<script>
import { router } from "@inertiajs/vue3";

export default {
    props: ["user", "errors"],
    data() {
        return {
            userInfo: this.user,
        };
    },
    computed: {
        fullName() {
            return (this.userInfo.first_name ?? '') + ' ' + (this.userInfo.last_name ?? '')
        }
    },
    methods: {
        nameToSlug() {
            this.userInfo.slug = changeToSlug(this.fullName);
        },
        createSlug() {
            this.userInfo.slug = changeToSlug(this.fullName);
        },
    },
};
</script>
<script setup>
import Button from "../../../Shared/FormElements/Button.vue";
import FormSimpleInput from "../../../Shared/FormElements/FormSimpleInput.vue";
import FormFileUploadSingle from "../../../Shared/FormElements/FormFileUploadSingle.vue";

import { changeToSlug, editRequest } from "../../../main";
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>