<template>
        <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/subscriptions/'"
        :text="'Deleting the subscription will permanently removed from the database. You can\'t recover the subscription again. Are you sure about deleting?'">
    </DeleteAlert>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Edit Subscription Plan'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <!-- <div class="space-y-4"> -->
                    <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="subscriptionInfo.title" @change="nameToSlug()"
                        :error="errors.title">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="subscriptionInfo.slug" @change="createSlug()"
                        :error="errors.slug">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Price(AUD)'" :name="'price'" :type="'number'" v-model="subscriptionInfo.price"
                        :error="errors.price">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Validity(in Days)'" :name="'validity'" :type="'number'" v-model="subscriptionInfo.validity"
                        :error="errors.validity">
                    </FormSimpleInput>
            </div>
            <div>
                <FormTextArea :label="'Description'" :name="'description'" v-model="subscriptionInfo.description"
                    :error="errors.description">
                </FormTextArea>
            </div>
            <div>
                    <FormFileUploadSingle @fileChange="(file) => (subscriptionInfo.thumbnail = file[0])" :label="'Thumbnail'"
                        :oldImageLink="subscription.thumbnail_url" :rounded="false" :name="'thumbnail'" :hideInputBox="true"
                        :error="errors.thumbnail"></FormFileUploadSingle>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="subscriptionInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="editRequest({                     
                    url: '/admin/dashboard/subscriptions/',
                    data: subscriptionInfo,
                    dataId: subscription.id,
                    only: ['flash', 'errors', 'subscription'],})"
                :text="'Update Subscription'" :color="'blue'"></Button>
                <Button @click.prevent="deleteId = subscription.id" :text="'Delete Subscription'" :color="'red'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors","subscription"],
    data() {
        return {
            subscriptionInfo: this.subscription,
            deleteId: false,
        };
    },
    mounted() {
        this.subscriptionInfo.published = true
    },
    methods: {
        nameToSlug(){
            this.subscriptionInfo.slug = changeToSlug(this.subscriptionInfo.title)
        },
        createSlug(){
            this.subscriptionInfo.slug = changeToSlug(this.subscriptionInfo.title,this.subscriptionInfo.slug)
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

import { changeToSlug,editRequest } from '../../../../main.js'

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>
