<template>
    <h2>Subscription Plans</h2>
    <DeleteAlert v-if="deleteId" :id="deleteId" @close="deleteId = false" :url="'/admin/dashboard/subscriptions/'"
        :text="'Deleting the subscription will permanently removed from the database. You can\'t recover the subscription again. Are you sure about deleting?'">
    </DeleteAlert>
    <TableLayout>
        <Filters :searchPlaceHolder="'Search by Subscription ID, Title, Description ..'" :filters="filters"
            :currentPage="subscriptions.current_page" :dataName="'subscriptions'" :sortByFilters="{ dateSort: true }" :enableFilters="{
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
        <TableNew :data="subscriptions.data" :tableContent="[
            { heading: 'Thumbnail', type: 'image', value: 'thumbnail_url' },
            { heading: 'Title', type: 'text', value: 'title' },
            { heading: 'Price', type: 'text', value: 'price' },
            { heading: 'Validity', type: 'text', value: 'validity' },
            { heading: 'Status', type: 'bool', value: 'published' },
        ]" :actionLinks="[{ link: 'admin/dashboard/subscriptions', name: 'Edit' }]" :deleteEnable="true"
            @deleteItem="(id) => deleteId = id"></TableNew>
        <PageNavigation :data="subscriptions"></PageNavigation>
    </TableLayout>
</template>
<script>
export default {
    props: ["filters", 'subscriptions'],
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
