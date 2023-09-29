<template>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Create new Facility'" :modalHeadingResetButton="true" :modalWidth="2">
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
                            :label="'Thumbnail'" :oldImageLink="''" :rounded="false" :name="'thumbnail'"
                            :hideInputBox="true" :error="errors.thumbnail"></FormFileUploadSingle>
                    </div>


                </div>

            </div>

            <div class="grid w-full gap-6 md:grid-cols-2">
                <div class=" col-span-2">
                    <FormSimpleInput :label="'Price (No subscription)'" :name="'price'" :type="'number'" @change="setSubscriptionPrice()"
                        v-model="facilityInfo.price" :error="errors.price"></FormSimpleInput>
                </div>
                <div class=" col-span-2" v-if="subscriptionsPrices.length > 0">
                    <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pricing
                        for Subscription plans:</label>
                </div>


                <div v-for="subscription, index in  subscriptionsPricesModified" :key="subscription.id">
                    <FormSimpleInput :label="'Price for - ' + subscription.title + ' subscription'"
                        :name="subscription.title" :type="'number'" :placeholder="facilityInfo.price"
                        v-model="subscriptionsPricesModified[index].price"></FormSimpleInput>
                </div>
            </div>

            <FormCheckBox :label="'Published'" :name="'published'" :checked="true" v-model="facilityInfo.published"
                :error="errors.published">
            </FormCheckBox>
        </template>
        <template #footer>
            <Button
                @click.prevent="createRequest({ url: '/admin/dashboard/facilities', data: { ...facilityInfo, subscriptionsPrices: subscriptionsPricesModified }, only: ['flash', 'errors'] })"
                :text="'Create Facility'" :color="'blue'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors", "subscriptionsPrices"],
    data() {
        return {
            facilityInfo: {price:0},
        };
    },
    computed: {
        subscriptionsPricesModified() {
            var subscriptionsPricesNew = this.subscriptionsPrices.filter((item) => {
                    item.price = this.facilityInfo.price ?? 0;
                    return item;
            });
            // var newArr = selectedSubscription.map(v => ({ ...v, price: this.courseInfo.price }))
            return subscriptionsPricesNew
        }

    },
    mounted() {
        this.facilityInfo.published = true
    },
    methods: {
        nameToSlug() {
            this.facilityInfo.slug = changeToSlug(this.facilityInfo.title)
        },
        createSlug() {
            this.facilityInfo.slug = changeToSlug(this.facilityInfo.title, this.facilityInfo.slug)
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

import { changeToSlug, createRequest } from '../../../../main.js'

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

</script>
