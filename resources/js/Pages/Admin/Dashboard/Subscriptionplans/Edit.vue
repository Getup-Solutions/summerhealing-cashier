<template>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/subscriptionplans/'"
        :text="'Deleting the subscription plan will permanently removed from the database. You can\'t recover the subscription plan again. Are you sure about deleting?'">
    </DeleteAlert>
    <div class="grid gap-8">
        <!-- Modal content -->
        <Modal :modalHeadingText="'Edit Membership Plan'" :modalHeadingResetButton="true" :modalWidth="2"
            :showError="hideError">
            <template #body>
                <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                    <!-- <div class="space-y-4"> -->
                    <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="subscriptionPlanInfo.title"
                        @change="nameToSlug()" :error="errors.title">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="subscriptionPlanInfo.slug"
                        @change="createSlug()" :error="errors.slug">
                    </FormSimpleInput>
                </div>
                <div>
                    <FormTextArea :label="'Description'" :name="'description'" v-model="subscriptionPlanInfo.description"
                        :error="errors.description">
                    </FormTextArea>
                </div>
                <div>
                    <FormFileUploadSingle @fileChange="(file) => (subscriptionPlanInfo.thumbnail = file[0])"
                        :label="'Thumbnail'" :oldImageLink="subscriptionplan.thumbnail_url" :rounded="false"
                        :name="'thumbnail'" :hideInputBox="true" :error="errors.thumbnail"></FormFileUploadSingle>
                </div>

                <div class="grid md:grid-cols-2 gap-6 border-t border-gray-600 gap-y-4 py-4">
                    <p class="text-blue-500 col-span-2 font-medium mb-4 text-md">
                        Pricing and Validity
                    </p>
                    <div class="col-span-2 mb-2">
                        <!-- <FormCheckBox :label="'Recurring Payment'" :name="'recurring'" :checked="true"
                            v-model="subscriptionPlanInfo.recurring" :error="errors.recurring">
                        </FormCheckBox> -->
                        <div class="flex gap-4">
                            <div class="flex items-center">
                                <input id="one-time-payment" type="radio" value="one-time" name="one-time-payment"
                                    v-model="subscriptionPlanInfo.payment_mode"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="one-time-payment"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">One-Time
                                    Payment</label>
                            </div>
                            <div class="flex items-center">
                                <input id="recurring-payment" type="radio" value="recurring" name="recurring-payment"
                                    v-model="subscriptionPlanInfo.payment_mode"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="recurring-payment"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recurring
                                    Payment</label>
                            </div>
                        </div>
                        <div v-if="errors.payment_mode" v-text="errors.payment_mode" class="text-red-500 text-xs mt-2">
                        </div>
                    </div>
                    <div class="col-span-2 flex items-start md:gap-4 gap-2">
                        <FormSimpleInput :label="'Price(AUD)'" :name="'price'" :type="'number'"
                            v-model="subscriptionPlanInfo.price" :error="errors.price">
                        </FormSimpleInput>
                        <FormSimpleInput :label="'For'" :name="'payment_interval_count'" :type="'number'"
                            v-model="subscriptionPlanInfo.payment_interval_count" :error="errors.payment_interval_count">
                        </FormSimpleInput>
                        <FormSelect :label="'Interval'" :name="'payment_interval'"
                            v-model="subscriptionPlanInfo.payment_interval" :error="errors.payment_interval"
                            :optionsArray="[{ name: 'Day', value: 'day' }, { name: 'Week', value: 'week' }, { name: 'Month', value: 'month' }, { name: 'Year', value: 'year' }]"
                            :optionName="'name'" :optionValue="'value'">
                        </FormSelect>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-6">
                    <FormCheckBox :label="'Limit to 1 purchase per person'" :name="'limit_purchase'"
                        v-model="subscriptionPlanInfo.limit_purchase" :error="errors.limit_purchase">
                    </FormCheckBox>
                    <FormCheckBox :label="'Published'" :name="'published'" :checked="true"
                        v-model="subscriptionPlanInfo.published" :error="errors.published">
                    </FormCheckBox>

                </div>
            </template>
            <template #footer>
                <Button @click.prevent="editRequest({
                    url: '/admin/dashboard/subscriptionplans/',
                    data: subscriptionPlanInfo,
                    dataId: subscriptionplan.id,
                    only: ['flash', 'errors', 'subscriptionplan'],
                })" :text="'Update Subscription plan'" :color="'blue'"></Button>
                <Button @click.prevent="deleteId = subscriptionplan.id" :text="'Delete Subscription plan'"
                    :color="'red'"></Button>
            </template>
        </Modal>

        <CreditEdit :errors="errors" :showError="!hideError" @creditsEdited="(value) => hideError = value"
            :sessions="sessions" :facilities="facilities" :editURL="'/admin/dashboard/subscriptionplans/'"
            :editData="subscriptionPlanInfo" :editDataId="subscriptionplan.id" :sessionGenCredits="sessionGenCredits ?? []"
            :facilityGenCredits="facilityGenCredits ?? []" :facilityCredits="facilityCredits ?? []"
            :sessionCredits="sessionCredits ?? []"></CreditEdit>
    </div>
</template>
<script>
export default {
    props: ["errors", "sessions", "facilities", "subscriptionplan", "sessionGenCredits", "facilityGenCredits", "facilityCredits", "sessionCredits"],
    data() {
        return {
            subscriptionPlanInfo: this.subscriptionplan,
            deleteId: false,
            hideError: true
        };
    },
    mounted() {
        this.subscriptionPlanInfo.published = true
    },
    methods: {
        nameToSlug() {
            this.subscriptionPlanInfo.slug = changeToSlug(this.subscriptionPlanInfo.title)
        },
        createSlug() {
            this.subscriptionPlanInfo.slug = changeToSlug(this.subscriptionPlanInfo.title, this.subscriptionPlanInfo.slug)
        }
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
import DeleteAlert from "../../../../Shared/DeleteAlert.vue";

import { changeToSlug, editRequest } from '../../../../main.js'

import CreditEdit from "../Credits/Edit.vue";
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>
