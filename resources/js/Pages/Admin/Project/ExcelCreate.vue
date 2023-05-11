<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref, watch, computed, toRef } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import moment from 'moment';
const props = defineProps({
    timestamps: Object,
    project: Object,
    searchValue: String,
    dateValue: String,
    users: Object
})

const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);

watch(filterDate, async (nv, ov) => {
    Inertia.get(route("admin.project.excelCreate", props.project), { date: filterDate.value, search: props.searchValue });
})

async function searchUser(event) {
    Inertia.get(route("admin.project.excelCreate", props.project), { search: event.target.value, date: filterDate.value });
}

const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM');
}

const exportProject = computed(() => {
    return window.location.pathname+`/export?date=${filterDate.value}`;
})

</script>
<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Raport dla Projektu: {{ project.name }}
            </h1>
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj użytkownika" @change="searchUser"/>
                </h3>
                <Calendar class="mr-14" v-model="date" :date-select="searchDate()" view="month" dateFormat="yy-mm" placeholder="Wybierz Datę"/>
                <a class="text-sm text-gray-700 dark:text-gray-500" :href="exportProject">
                    <Button label="Primary">
                        Pobierz raport
                    </Button>
                </a>
            </div>
            <div class="block w-full overflow-x-auto">
                <div class="flex justify-center mb-2 font-bold text-base" v-for="user in users" :key="user">
                    <a :href="route('admin.user.cost', user)" as="button" method="GET">
                        {{ user.name }} razem: {{ user.hours }}
                        <div class="pi pi-info-circle" v-tooltip.right="{value: 'Po kliknięciu przechodzimy na stronę Karty Pracownika: '+user.name, class: 'custom-tooltip'}" />
                    </a>
                </div>
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Dzień
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Imię i Nazwisko
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Typ Pracy
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Ilość Godzin
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(timestamp, index) in timestamps.data" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ moment(timestamp.day, 'YYYY-MM-DD').format('DD.MM.YYYY') }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ timestamp.user }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ timestamp.work_type }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ timestamp.hour }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="timestamps.links" />
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