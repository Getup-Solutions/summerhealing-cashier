<template>
    <h2>Users</h2>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/users/'"
        :text="'Deleting the user will permanently removed from the database. You can\'t recover the user again. Are you sure about deleting?'">
    </DeleteAlert>
    <TableLayout>
        <Filters :searchPlaceHolder="'Search by User ID, Name, Email ..'" :filters="filters"
            :currentPage="users.current_page" :dataName="'users'" :sortByFilters="{ dateSort: true }" :enableFilters="{
                search: true,
                dateRange: true,
                sortBy: true,
                filterByFiltersEnabled: [
                    {
                        name: 'Roles',
                        slug: 'roles',
                        valueKey: 'value',
                        nameKey: 'name',
                        options: roles,
                    },
                ],
            }"></Filters>
        <TableNew :data="users.data" :tableContent="[
            { heading: 'Avatar', type: 'image', value: 'avatar_url' },
            { heading: 'Name', type: 'text', value: 'full_name' },
            { heading: 'Email', type: 'text', value: 'email' },
            { heading: 'Role', type: 'relation', relationType: 'many', values: ['roles', 'name'] },
        ]" :actionLinks="[{ link: 'admin/dashboard/users', name: 'Edit' }]" :deleteEnable="true"
            @deleteItem="(id) => deleteId = id"></TableNew>
        <PageNavigation :data="users"></PageNavigation>
    </TableLayout>
</template>
<script>
export default {
    props: ["roles", "filters", 'users'],
    data() {
        return {
            deleteId: false,
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

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>
