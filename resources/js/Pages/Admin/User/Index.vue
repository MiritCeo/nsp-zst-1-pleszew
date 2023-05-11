<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import Pagination from '@/Components/Pagination.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
const props = defineProps({
    users: Array,
    searchValue: String
})
async function searchUser(event) {
    Inertia.get(route("admin.user.index", { search: event.target.value }));
}
</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <div class="flex justify-start mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj użytkownika" @change="searchUser"/>
                </h3>
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
                        <tr v-for="(user, index) in users.data" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{
                                    (users.current_page - 1) * users.per_page +
                                    index +
                                    1
                                }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 w-1/2">
                                {{ user.name }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.user.excelCreate', user)" as="button" method="GET" target="_blank">
                                    <Button label="Accept" class="p-button-success">
                                        Stwórz raport
                                    </Button>
                                </a>
                                <a class="text-sm text-gray-700 dark:text-gray-500 mr-2" :href="route('admin.user.cost', user)" as="button" method="GET" target="_blank">
                                    <Button label="Help" class="p-button-help">
                                        Karta Pracownika
                                    </Button>
                                </a>
                                <Link class="text-sm text-gray-700 dark:text-gray-500" :href="route('admin.user.delete', user)" as="button" method="DELETE">
                                    <Button label="Danger" class="p-button-danger">
                                        Usuń
                                    </Button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="users.links" />
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
