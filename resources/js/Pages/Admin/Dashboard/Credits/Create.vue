<template>
    <!-- Modal content -->
    <Modal
        :modalHeadingText="'Subscription plan Benefits'"
        :modalHeadingResetButton="true"
        :modalWidth="2"
        :showError="showError"
    >
        <template #body>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <FormSimpleInput
                    :label="'Credits for Any Classes'"
                    :name="'session_credits'"
                    :type="'number'"
                    :min="'0'"
                    v-model="sessionGenCredits.credits"
                >
                </FormSimpleInput>
                <FormSimpleInput
                    :label="'Credits for Any Facilities'"
                    :name="'session_facilities'"
                    :type="'number'"
                    :min="0"
                    v-model="facilityGenCredits.credits"
                >
                </FormSimpleInput>
            </div>
            <div class="grid md:grid-cols-2 gap-6 gap-y-4">
                <div>
                    <div
                        v-for="credit in sessionCredits"
                        :key="credit"
                        class="grid md:grid-cols-2 gap-6 gap-y-4 bg-blue-500/20 pt-4 pb-2 px-2 rounded-lg mb-3"
                    >
                        <FormSimpleInput
                            :label="'Creditable Type'"
                            :name="'creditable_type'"
                            :type="'text'"
                            :hidden="true"
                            :readOnly="'App\\Models\\Session'"
                            v-model="credit.creditable_type"
                        >
                        </FormSimpleInput>
                        <FormSelect
                            :label="'Select Class'"
                            :name="'session_id'"
                            v-model="credit.creditable_id"
                            :optionsArray="
                                selectableSessions(credit.creditable_id)
                            "
                            :optionName="'title'"
                            :optionValue="'id'"
                        ></FormSelect>
                        <FormSimpleInput
                            :label="'Credits'"
                            :name="'credits'"
                            :type="'number'"
                            :min="0"
                            v-model="credit.credits"
                        >
                        </FormSimpleInput>
                        <div
                            class="col-span-2"
                            v-if="sessionCredits.length > 0"
                        >
                            <Button
                                @click.prevent="sessionCredits.splice(index, 1)"
                                :text="'Remove Credit'"
                                :color="'red'"
                                :fullWidth="true"
                            ></Button>
                        </div>
                    </div>
                    <div
                        class="mt-4"
                        v-if="
                            selectableSessions().length >= 1 &&
                            sessions.length > sessionCredits.length
                        "
                    >
                        <Button
                            @click.prevent="
                                sessionCredits.push({
                                    creditable_type: 'App\\Models\\Session',
                                    creditable_id: null,
                                    credits: 0,
                                })
                            "
                            :text="'Add new Class Credit'"
                            :color="'blue'"
                            :fullWidth="true"
                        ></Button>
                    </div>
                </div>
                <div>
                    <div
                        v-for="credit in facilityCredits"
                        :key="credit"
                        class="grid md:grid-cols-2 gap-6 gap-y-4 bg-blue-500/20 pt-4 pb-2 px-2 rounded-lg mb-3"
                    >
                        <FormSimpleInput
                            :label="'Creditable Type'"
                            :name="'creditable_type'"
                            :type="'text'"
                            :hidden="true"
                            :readOnly="'App\\Models\\Facility'"
                            v-model="credit.creditable_type"
                        >
                        </FormSimpleInput>
                        <FormSelect
                            :label="'Select Class'"
                            :name="'facility_id'"
                            v-model="credit.creditable_id"
                            :optionsArray="
                                selectableFacilities(credit.creditable_id)
                            "
                            :optionName="'title'"
                            :optionValue="'id'"
                        ></FormSelect>
                        <FormSimpleInput
                            :label="'Credits'"
                            :name="'credits'"
                            :type="'number'"
                            :min="0"
                            v-model="credit.credits"
                        >
                        </FormSimpleInput>
                        <div
                            class="col-span-2"
                            v-if="facilityCredits.length > 0"
                        >
                            <Button
                                @click.prevent="
                                    facilityCredits.splice(index, 1)
                                "
                                :text="'Remove Credit'"
                                :color="'red'"
                                :fullWidth="true"
                            ></Button>
                        </div>
                    </div>
                    <div
                        class="mt-4"
                        v-if="
                            selectableFacilities().length >= 1 &&
                            facilities.length > facilityCredits.length
                        "
                    >
                        <Button
                            @click.prevent="
                                facilityCredits.push({
                                    creditable_type: 'App\\Models\\Facility',
                                    creditable_id: null,
                                    credits: 0,
                                })
                            "
                            :text="'Add new Facility Credit'"
                            :color="'blue'"
                            :fullWidth="true"
                        ></Button>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <Button
                @click.prevent="createCredits()"
                :text="'Create Credits'"
                :color="'blue'"
            ></Button>
        </template>
    </Modal>
</template>
<script>
export default {
    props: ["errors", "sessions", "facilities","createData","createURL","showError"],
    emits: ["creditsCreated"],
    data() {
        return {
            sessionCredits: [],
            facilityCredits: [],
            sessionGenCredits: {
                creditable_id: 0,
                creditable_type: "App\\Models\\Session",
            },
            facilityGenCredits: {
                creditable_id: 0,
                creditable_type: "App\\Models\\Facility",
            },
        };
    },
    methods: {
        selectableSessions(selectedSession = null) {
            var selectedSessionIds = this.sessionCredits.map((item) => {
                if (item.creditable_type === "App\\Models\\Session") {
                    return item.creditable_id;
                }
            });
            // console.log(selectedSessionIds);
            var filteredSessions = this.sessions.filter((item) => {
                return !selectedSessionIds.includes(String(item["id"]));
            });
            if (selectedSession) {
                // console.log(selectedSession);
                filteredSessions.push(
                    this.sessions.find((item) => item["id"] == selectedSession)
                );
            }
            return filteredSessions;
        },
        selectableFacilities(selectedFacility = null) {
            var selectedFacilityIds = this.facilityCredits.map((item) => {
                if (item.creditable_type === "App\\Models\\Facility") {
                    return item.creditable_id;
                }
            });
            // console.log(selectedFacilityIds);
            var filteredFacilities = this.facilities.filter((item) => {
                return !selectedFacilityIds.includes(String(item["id"]));
            });
            if (selectedFacility) {
                // console.log(selectedFacility);
                filteredFacilities.push(
                    this.facilities.find(
                        (item) => item["id"] == selectedFacility
                    )
                );
            }
            return filteredFacilities;
        },
        createCredits() {
            this.$emit("creditsCreated", false);
            console.log({
                sessionGenCredits: this.sessionGenCredits,
                facilityGenCredits: this.facilityGenCredits,
                sessionCredits: this.sessionCredits,
                facilityCredits: this.facilityCredits,
            });
            var creditsData = {
                sessionGenCredits: this.sessionGenCredits,
                facilityGenCredits: this.facilityGenCredits,
                sessionCredits: this.sessionCredits,
                facilityCredits: this.facilityCredits,
            };
            createRequest({
                url: this.createURL,
                data: { ...this.createData, creditsInfo: creditsData},
                only: ["flash", "errors"],
            });
        },
    },
};
</script>
<script setup>
import FormSimpleInput from "../../../../Shared/FormElements/FormSimpleInput.vue";
import FormSelect from "../../../../Shared/FormElements/FormSelect.vue";
import Modal from "../../../../Shared/Modal/Modal.vue";
import Button from "../../../../Shared/FormElements/Button.vue";
import { createRequest } from "../../../../main.js";

import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
});
</script>
