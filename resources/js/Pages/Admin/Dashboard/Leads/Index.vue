<template>
    <h2>Leads</h2>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/leads/'"
        :text="'Deleting the lead will permanently removed from the database. You can\'t recover the lead again. Are you sure about deleting?'">
    </DeleteAlert>
    <TableLayout>
        <Filters :searchPlaceHolder="'Search by Name, Email, Phone Number, Source'" :filters="filters"
            :currentPage="leads.current_page" :dataName="'leads'" :sortByFilters="{ dateSort: true }" :enableFilters="{
                search: true,
                dateRange: true,
                sortBy: true,
                filterByFiltersEnabled: [
                    {
                        name: 'Privilage',
                        slug: 'privilage',
                        valueKey: 'value',
                        nameKey: 'name',
                        options: [{name:'User',value:'user'},{name:'Subscriber',value:'subscriber'}],
                    },
                ],
            }"></Filters>
        <TableNew :data="leads.data" :tableContent="[
            // { heading: 'Avatar', type: 'image', value: 'avatar_url' },
            { heading: 'Name', type: 'text', value: 'full_name' },
            { heading: 'Email', type: 'text', value: 'email' },
            { heading: 'Phone', type: 'text', value: 'phone_number' },
            { heading: 'Source', type: 'text', value: 'source' },
            { heading: 'User ID', type: 'text', value: 'user_id' },
            // { heading: 'Role', type: 'relation', relationType: 'many', values: ['roles', 'name'] },
        ]" :actionLinks="[{ link: 'admin/dashboard/leads', name: 'Edit' },{ link: 'admin/dashboard/users/', name: 'Add to User' }]" :deleteEnable="true"
            @deleteItem="(id) => deleteId = id"></TableNew>
        <PageNavigation :data="leads"></PageNavigation>
    </TableLayout>
</template>
<script>
export default {
    props: ["roles", "filters", 'leads'],
    data() {
        return {
            deleteId: false,
            userIcon: UserCircleIcon
        };
    }
};
</script>
<script setup>
import PageNavigation from "../../../../Shared/Table/PageNavigation.vue";
import Filters from "../../../../Shared/Filters/Filters.vue";
import DeleteAlert from "../../../../Shared/DeleteAlert.vue";
import TableNew from "../../../../Shared/Table/Table.vue";
import TableLayout from "../../../../Shared/Table/TableLayout.vue";
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

import {
    UserCircleIcon
} from "@heroicons/vue/24/solid";

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>
