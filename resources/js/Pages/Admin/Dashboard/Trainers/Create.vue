<template>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Create new Trainer'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <FormSimpleInput :label="'First Name'" :name="'first_name'" :type="'text'" v-model="userInfo.first_name"
                        :error="errors.first_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Last Name'" :name="'last_name'" :type="'text'" v-model="userInfo.last_name"
                        :error="errors.last_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Email'" :name="'email'" :type="'email'" v-model="userInfo.email"
                        :error="errors.email">
                    </FormSimpleInput>

                    <!-- <FormSelect :label="'Assign User Role'" :name="'course_id'" :selected="courseId"
                        v-model="userInfo.course_id" :error="errors.course_id" :optionsArray="courses" :optionName="'name'"
                        :optionValue="'id'">
                    </FormSelect> -->
                </div>
                <div>
                    <FormFileUploadSingle @fileChange="(file) => (userInfo.avatar = file[0])" :label="'Profile Image'"
                        :oldImageLink="oldAvatar" :rounded="true" :name="'avatar'" :hideInputBox="true"
                        :error="errors.avatar"></FormFileUploadSingle>
                </div>
            </div>
            <div>
                <FormTextArea :label="'About'" :name="'about'" v-model="userInfo.about"
                        :error="errors.about">
                    </FormTextArea>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
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
            <div class="flex flex-col md:flex-row gap-6">
                <p class="block text-sm font-medium text-gray-900 dark:text-white">Assign Roles:</p>
                <div class="flex items-center" v-for="role in roles" :key="role">
                    <input type="checkbox" :id="role.name" :value="role.id" v-model="userInfo.roles" v-if="role.id !== 2"
                        class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                    <label :for="role.name" v-if="role.id !== 2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{
                        role.name }}</label>
                </div>
                
            </div>


            <FormCheckBox :label="'Agree to Terms and Conditions'" :name="'tac'" :checked="true" v-model="userInfo.tac"
                :error="errors.tac">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="createRequest({ url: '/admin/dashboard/users?from_trainer=true', data: userInfo, only: ['flash', 'errors'] })"
                :text="'Create Trainer'" :color="'blue'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors", "roles"],
    data() {
        return {
            userInfo: { roles: [2] }
        };
    },
    mounted() {
        this.userInfo.tac = true
    },
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
