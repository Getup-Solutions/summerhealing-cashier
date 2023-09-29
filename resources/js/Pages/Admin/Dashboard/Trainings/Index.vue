<template>
    <h2>Trainings</h2>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/trainings/'"
        :text="'Deleting the training will permanently removed from the database. You can\'t recover the training again. Are you sure about deleting?'">
    </DeleteAlert>
    <TableLayout>
        <Filters :searchPlaceHolder="'Search by Training ID, Title, Description ..'" :filters="filters"
            :currentPage="trainings.current_page" :dataName="'trainings'" :sortByFilters="{ dateSort: true }" :enableFilters="{
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
        <TableNew :data="trainings.data" :tableContent="[
            { heading: 'Thumbnail', type: 'image', value: 'thumbnail_url' },
            { heading: 'Title', type: 'text', value: 'title' },
            { heading: 'Excerpt', type: 'text', value: 'excerpt' },
            // { heading: 'Validity', type: 'text', value: 'validity' },
            { heading: 'Status', type: 'bool', value: 'published' },
        ]" :actionLinks="[{ link: 'admin/dashboard/trainings', name: 'Edit' }]" :deleteEnable="true"
            @deleteItem="(id) => deleteId = id"></TableNew>
        <PageNavigation :data="trainings"></PageNavigation>
    </TableLayout>
</template>
<script>
export default {
    props: ["filters", 'trainings'],
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
