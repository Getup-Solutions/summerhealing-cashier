<template>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Create new Subscription Plan'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <!-- <div class="space-y-4"> -->
                    <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="subscriptionPlanInfo.title" @change="nameToSlug()"
                        :error="errors.title">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="subscriptionPlanInfo.slug" @change="createSlug()"
                        :error="errors.slug">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Price(AUD)'" :name="'price'" :type="'number'" v-model="subscriptionPlanInfo.price"
                        :error="errors.price">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Validity(in Days)'" :name="'validity'" :type="'number'" v-model="subscriptionPlanInfo.validity"
                        :error="errors.validity">
                    </FormSimpleInput>
            </div>
            <div>
                <FormTextArea :label="'Description'" :name="'description'" v-model="subscriptionPlanInfo.description"
                    :error="errors.description">
                </FormTextArea>
            </div>
            <div>
                    <FormFileUploadSingle @fileChange="(file) => (subscriptionPlanInfo.thumbnail = file[0])" :label="'Thumbnail'"
                        :oldImageLink="oldThumbnail" :rounded="false" :name="'thumbnail'" :hideInputBox="true"
                        :error="errors.thumbnail"></FormFileUploadSingle>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="subscriptionPlanInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="createRequest({ url: '/admin/dashboard/subscriptionplans', data: subscriptionPlanInfo, only: ['flash', 'errors'] })"
                :text="'Create Subscription'" :color="'blue'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors"],
    data() {
        return {
            subscriptionPlanInfo: {}
        };
    },
    mounted() {
        this.subscriptionPlanInfo.published = true
    },
    methods: {
        nameToSlug(){
            this.subscriptionPlanInfo.slug = changeToSlug(this.subscriptionPlanInfo.title)
        },
        createSlug(){
            this.subscriptionPlanInfo.slug = changeToSlug(this.subscriptionPlanInfo.title,this.subscriptionPlanInfo.slug)
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

import { changeToSlug,createRequest } from '../../../../main.js'

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>
