<template>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/facilities/'"
        :text="'Deleting the facility will permanently removed from the database. You can\'t recover the facility again. Are you sure about deleting?'">
    </DeleteAlert>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Edit Facility'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <div class="col-span-2 grid md:grid-cols-2 gap-6 gap-y-4">

                    <FormSimpleInput :label="'Title'" :name="'title'" :type="'text'" v-model="facilityInfo.title"
                        @change="nameToSlug()" :error="errors.title">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Slug'" :name="'slug'" :type="'text'" v-model="facilityInfo.slug"
                        @change="createSlug()" :error="errors.slug">

                    </FormSimpleInput>
                    <!-- <FormSelect :label="'Age group'" :name="'agegroup'" v-model="facilityInfo.agegroup_id"
                        :error="errors.agegroup_id" :optionsArray="agegroups" :optionName="'title'" :optionValue="'id'">
                    </FormSelect> -->
                    <!-- <FormSelect :label="'Level'" :name="'level'" v-model="facilityInfo.level" :error="errors.level"
                        :optionsArray="levels" :optionName="'name'" :optionValue="'value'">
                    </FormSelect> -->
                    <!-- <FormSimpleInput :label="'Video embeded URL'" :name="'video_url'" :type="'text'"
                        v-model="facilityInfo.video_url" :error="errors.video_url">
                    </FormSimpleInput> -->
                    <div class="col-span-2">
                        <FormTextArea :label="'Excerpt'" :name="'excerpt'" v-model="facilityInfo.excerpt"
                            :error="errors.description">
                        </FormTextArea>
                    </div>
                    <div class="col-span-2">
                        <FormTextArea :label="'Description'" :name="'description'" v-model="facilityInfo.description"
                            :error="errors.description">
                        </FormTextArea>
                    </div>

                    <div class=" col-span-2">
                        <FormFileUploadSingle @fileChange="(file) => (facilityInfo.thumbnail = file[0])"
                            :label="'Thumbnail'" :oldImageLink="facility.thumbnail_url" :rounded="false" :name="'thumbnail'"
                            :hideInputBox="true" :error="errors.thumbnail"></FormFileUploadSingle>
                    </div>


                </div>

            </div>

            <div class="grid w-full gap-6 md:grid-cols-2">
                <div class=" col-span-2">
                    <FormSimpleInput :label="'Price (No subscriptionplan)'" :name="'price'" :type="'number'"
                        @change="setSubscriptionplanPrice()" v-model="facilityInfo.price" :error="errors.price">
                    </FormSimpleInput>
                </div>
                <div class=" col-span-2" v-if="subscriptionplansPrices.length > 0">
                    <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pricing
                        for Subscriptionplan plans:</label>
                </div>


                <div v-for="subscriptionplan, index in  subscriptionplansPricesModified" :key="subscriptionplan.id">
                    <FormSimpleInput :label="'Price for - ' + subscriptionplan.title + ' subscriptionplan'"
                        :name="subscriptionplan.title" :type="'number'" :placeholder="facilityInfo.price"
                        v-model="subscriptionplansPricesModified[index].price"></FormSimpleInput>
                </div>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="facilityInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="editRequest({ url: '/admin/dashboard/facilities/', data: { ...facilityInfo, subscriptionplansPrices: subscriptionplansPricesModified }, dataId: facility.id, only: ['flash', 'errors'] })"
                :text="'Update Facility'" :color="'blue'"></Button>
                <Button @click.prevent="deleteId = facility.id" :text="'Delete Facility'" :color="'red'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors", "subscriptionplansPrices","facility"],
    data() {
        return {
            deleteId: false,
            facilityInfo: this.facility,
        };
    },
    computed: {
        subscriptionplansPricesModified() {
            var subscriptionplansPricesNew = this.subscriptionplansPrices.filter((item) => {
                return item;
            });
            // var newArr = selectedSubscriptionplan.map(v => ({ ...v, price: this.courseInfo.price }))
            return subscriptionplansPricesNew
        }

    },
    methods: {
        nameToSlug() {
            this.courseInfo.slug = changeToSlug(this.courseInfo.title)
        },
        createSlug() {
            this.courseInfo.slug = changeToSlug(this.courseInfo.title, this.courseInfo.slug)
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

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})


</script>
