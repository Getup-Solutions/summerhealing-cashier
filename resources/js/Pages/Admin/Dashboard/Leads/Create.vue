<template>
    <!-- Modal content -->
    <Modal :modalHeadingText="'Create Lead'" :modalHeadingResetButton="true" :modalWidth="2">
        <template #body>
            <div class="grid md:grid-cols-2 gap-6">

                    <FormSimpleInput :label="'First Name'" :name="'first_name'" :type="'text'" v-model="leadInfo.first_name"
                        :error="errors.first_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Last Name'" :name="'last_name'" :type="'text'" v-model="leadInfo.last_name"
                        :error="errors.last_name">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Email'" :name="'email'" :type="'email'" v-model="leadInfo.email"
                        :error="errors.email">
                    </FormSimpleInput>
                    <FormSimpleInput :label="'Phone Number'" :name="'phone_number'" :placeholder="'+61'" :type="'text'"
                    v-model="leadInfo.phone_number" :error="errors.phone_number">
                </FormSimpleInput>
                <FormSimpleInput :label="'Source'" :name="'source'" :type="'text'" v-model="leadInfo.source"
                        :error="errors.source">
                    </FormSimpleInput>
            </div>
        </template>
        <template #footer>
            <Button
                @click.prevent="createRequest({ url: '/admin/dashboard/leads', data: leadInfo, only: ['flash', 'errors'] })"
                :text="'Create Lead'" :color="'blue'"></Button>
        </template>
    </Modal>
</template>
<script>

export default {
    props: ["errors"],
    data() {
        return {
            leadInfo: { }
        };
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
