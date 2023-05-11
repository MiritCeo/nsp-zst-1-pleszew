<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

const toast = useToast();
const props = defineProps({
    work_types: Array,
    searchValue: String
})
async function searchWorkType(event) {
    Inertia.get(route("admin.work_type.index", { search: event.target.value }));
}

function deleteToast() {
    toast.add({severity:'warn', summary: 'Ostrzeżenie', detail:'Tryb pracy został usunięty', life: 3000});
}
</script>
<template>
    <AdminLayout>
        <Toast />
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj trybu pracy" @change="searchWorkType"/>
                </h3>
                <Link class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.work_type.create')" as="button">
                    <Button label="Primary">
                        Dodaj tryb pracy
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
                        <tr v-for="(work_type, index) in work_types.data" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{
                                    (work_types.current_page - 1) * work_types.per_page +
                                    index +
                                    1
                                }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 w-1/2">
                                {{ work_type.name }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <Link class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.work_type.edit', work_type)" as="button">
                                    <Button label="Warning" class="p-button-warning">
                                        Edytuj
                                    </Button>
                                </Link>
                                <Link class="text-sm text-gray-700 dark:text-gray-500" @click="deleteToast()" :href="route('admin.work_type.delete', work_type)" as="button" method="DELETE">
                                    <Button label="Danger" class="p-button-danger">
                                        Usuń
                                    </Button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="work_types.links" />
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
