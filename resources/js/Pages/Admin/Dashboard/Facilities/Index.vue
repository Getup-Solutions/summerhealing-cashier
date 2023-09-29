<template>
    <h2>Facilities</h2>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/facilities/'"
        :text="'Deleting the facility will permanently removed from the database. You can\'t recover the facility again. Are you sure about deleting?'">
    </DeleteAlert>
    <TableLayout>
        <Filters :searchPlaceHolder="'Search by Facility ID, Title, Description ..'" :filters="filters"
            :currentPage="facilities.current_page" :dataName="'facilities'" :sortByFilters="{ dateSort: true }" :enableFilters="{
                search: true,
                dateRange: true,
                sortBy: true,
                filterByFiltersEnabled: [
                    {
                        name: 'Status',
                        slug: 'published',
                        valueKey: 'value',
                        nameKey: 'name',
                        options: [{name:'Published', value:true},{name:'Draft', value:false}],
                    },
                ],
            }"></Filters>
        <TableNew :data="facilities.data" :tableContent="[
            { heading: 'Thumbnail', type: 'image', value: 'thumbnail_url' },
            { heading: 'Title', type: 'text', value: 'title' },
            { heading: 'Excerpt', type: 'text', value: 'excerpt' },
            { heading: 'Price', type: 'text', value: 'price' },
            { heading: 'Status', type: 'bool', value: 'published' },
        ]" :actionLinks="[{ link: 'admin/dashboard/facilities', name: 'Edit' }]" :deleteEnable="true"
            @deleteItem="(id) => deleteId = id"></TableNew>
        <PageNavigation :data="facilities"></PageNavigation>
    </TableLayout>
</template>
<script>
export default {
    props: ["filters", 'facilities'],
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
