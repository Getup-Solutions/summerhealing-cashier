<template>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/trainings/'"
    :text="'Deleting the training will permanently removed from the database. You can\'t recover the training again. Are you sure about deleting?'">
</DeleteAlert>
<!-- Modal content -->
<Modal :modalHeadingText="'Edit Training'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <!-- <div class="space-y-4"> -->
                    <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="trainingInfo.title" @change="nameToSlug()"
                        :error="errors.title">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="trainingInfo.slug" @change="createSlug()"
                        :error="errors.slug">
                    </FormSimpleInput>
                    <!-- <FormSimpleInput :label="'Price(AUD)'" :name="'price'" :type="'number'" v-model="trainingInfo.price"
                        :error="errors.price">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Validity(in Days)'" :name="'validity'" :type="'number'" v-model="trainingInfo.validity"
                        :error="errors.validity">
                    </FormSimpleInput> -->
            </div>
            <div>
                <FormTextArea :label="'Excerpt'" :name="'excerpt'" v-model="trainingInfo.excerpt"
                    :error="errors.excerpt">
                </FormTextArea>
            </div>
            <div>
                <FormTextArea :label="'Description'" :name="'description'" v-model="trainingInfo.description"
                    :error="errors.description">
                </FormTextArea>
            </div>
            <div>
                    <FormFileUploadSingle @fileChange="(file) => (trainingInfo.thumbnail = file[0])" :label="'Thumbnail'"
                        :oldImageLink="training.thumbnail_url" :rounded="false" :name="'thumbnail'" :hideInputBox="true"
                        :error="errors.thumbnail"></FormFileUploadSingle>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="trainingInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="editRequest({ url: '/admin/dashboard/trainings/', data: trainingInfo, only: ['flash', 'errors'], dataId:training.id })"
                :text="'Update Training'" :color="'blue'"></Button>
                <Button @click.prevent="deleteId = training.id" :text="'Delete Training'" :color="'red'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
props: ["errors","training"],
data() {
    return {
        trainingInfo: this.training,
        deleteId: false,
    };
},
mounted() {
    this.trainingInfo.published = true
},
methods: {
    nameToSlug(){
        this.trainingInfo.slug = changeToSlug(this.trainingInfo.title)
    },
    createSlug(){
        this.trainingInfo.slug = changeToSlug(this.trainingInfo.title,this.trainingInfo.slug)
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
