<template>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/subscriptionplans/'"
        :text="'Deleting the subscription plan will permanently removed from the database. You can\'t recover the subscription plan again. Are you sure about deleting?'">
    </DeleteAlert>
    <div class="grid gap-8">
    <!-- Modal content -->
    <Modal :modalHeadingText="'Edit Subscriptionplan Plan'" :modalHeadingResetButton="true" :modalWidth="2"
        :showError="hideError">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <!-- <div class="space-y-4"> -->
                <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="subscriptionplanInfo.title"
                    @change="nameToSlug()" :error="errors.title">
                </FormSimpleInput>
                <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="subscriptionplanInfo.slug"
                    @change="createSlug()" :error="errors.slug">
                </FormSimpleInput>
                <FormSimpleInput :label="'Price(AUD)'" :name="'price'" :type="'number'" v-model="subscriptionplanInfo.price"
                    :error="errors.price">
                </FormSimpleInput>
                <FormSimpleInput :label="'Validity(in Days)'" :name="'validity'" :type="'number'"
                    v-model="subscriptionplanInfo.validity" :error="errors.validity">
                </FormSimpleInput>
            </div>
            <div>
                <FormTextArea :label="'Description'" :name="'description'" v-model="subscriptionplanInfo.description"
                    :error="errors.description">
                </FormTextArea>
            </div>
            <div>
                <FormFileUploadSingle @fileChange="(file) => (subscriptionplanInfo.thumbnail = file[0])"
                    :label="'Thumbnail'" :oldImageLink="subscriptionplan.thumbnail_url" :rounded="false" :name="'thumbnail'"
                    :hideInputBox="true" :error="errors.thumbnail"></FormFileUploadSingle>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="subscriptionplanInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button @click.prevent="editRequest({
                url: '/admin/dashboard/subscriptionplans/',
                data: subscriptionplanInfo,
                dataId: subscriptionplan.id,
                only: ['flash', 'errors', 'subscriptionplan'],
            })" :text="'Update Subscription plan'"
                :color="'blue'"></Button>
            <Button @click.prevent="deleteId = subscriptionplan.id" :text="'Delete Subscription plan'"
                :color="'red'"></Button>
        </template>
    </Modal>

    <CreditEdit :errors="errors" :showError="!hideError" @creditsEdited="(value) => hideError = value" :sessions="sessions"
        :facilities="facilities" :editURL="'/admin/dashboard/subscriptionplans/'" :editData="subscriptionplanInfo" :editDataId="subscriptionplan.id"
        :sessionGenCredits="sessionGenCredits" :facilityGenCredits="facilityGenCredits" :facilityCredits="facilityCredits" :sessionCredits="sessionCredits"></CreditEdit>
    </div>

</template>
<script>
export default {
    props: ["errors","sessions","facilities", "subscriptionplan", "sessionGenCredits","facilityGenCredits","facilityCredits","sessionCredits"],
    data() {
        return {
            subscriptionplanInfo: this.subscriptionplan,
            deleteId: false,
            hideError:true
        };
    },
    mounted() {
        this.subscriptionplanInfo.published = true
    },
    methods: {
        nameToSlug() {
            this.subscriptionplanInfo.slug = changeToSlug(this.subscriptionplanInfo.title)
        },
        createSlug() {
            this.subscriptionplanInfo.slug = changeToSlug(this.subscriptionplanInfo.title, this.subscriptionplanInfo.slug)
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
