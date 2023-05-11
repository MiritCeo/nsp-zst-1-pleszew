<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
const props = defineProps({
    projects: Array,
    searchValue: String
})
async function searchProject(event) {
    Inertia.get(route("admin.project.index", { search: event.target.value }));
}
</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj projekt" @change="searchProject"/>
                </h3>
                <Link class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.project.create')" as="button">
                    <Button label="Primary">
                        Dodaj projekt
                    </Button>
                </Link>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Id
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Nazwa
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Akcje
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(project, index) in projects.data" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{
                                    (projects.current_page - 1) * projects.per_page +
                                    index +
                                    1
                                }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 w-1/2">
                                {{ project.name }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.project.excelCreate', project)" as="button" method="GET" target="_blank">
                                    <Button label="Accept" class="p-button-success">
                                        Stwórz raport
                                    </Button>
                                </a>
                                <Link class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.project.edit', project)" as="button">
                                    <Button label="Warning" class="p-button-warning">
                                        Edytuj
                                    </Button>
                                </Link>
                                <Link class="text-sm text-gray-700 dark:text-gray-500" :href="route('admin.project.delete', project)" as="button" method="DELETE">
                                    <Button label="Danger" class="p-button-danger">
                                        Usuń
                                    </Button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="projects.links" />
            </div>
        </div>
    </AdminLayout>
</template>
<style scoped>
.wrap-table {
    table-layout: fixed;
}
.wrap-table td {
    word-wrap:break-word
}
</style>
