<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref, watch, computed, toRef } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import Tooltip from 'primevue/tooltip';
const props = defineProps({
    timestamps: Object,
    user: Object,
    dateValue: String,
    projects: Object
})

const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);
let totalHours = 0;

watch(filterDate, async (nv, ov) => {
    Inertia.get(route("admin.user.excelCreate", props.user), { date: filterDate.value });
})
const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM');
}
const exportProject = computed(() => {
    return window.location.pathname+`/export?date=${filterDate.value}`;
})
props.timestamps.forEach(timestamp => {
    totalHours += timestamp.working_hours
});
</script>
<template>
    <AdminLayout>
        <div class="mt-14 flex flex-row" style="background-color: rgb(184 184 184);">
            <a :href="route('admin.user.excelCreate', user)" class="ml-14 p-3 bg-white w-32 text-center rounded-tl-lg disabled" as="button" method="GET">
                Użytkownik
            </a>
            <a :href="route('admin.user.excelHoliday', user)" class="p-3 bg-gray-200 w-32 text-center rounded-tr-lg hover:bg-gray-300" as="button" method="GET">
                Urlopy
            </a>
        </div>
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Raport Użytkownika: {{ user.name }}
            </h1>
            <div class="flex justify-between mb-2">
                <Calendar v-model="date"  :date-select="searchDate()" view="month" dateFormat="yy-mm" placeholder="Wybierz Datę"/>
                <a class="text-sm text-gray-700 dark:text-gray-500" :href="exportProject">
                    <Button label="Primary">
                        Pobierz raport
                    </Button>
                </a>
            </div>
            <div class="block w-full overflow-x-auto">
                <div class="flex justify-center mb-2 font-bold text-base">
                    Projekty:
                    <div v-for="project in projects" :key="project" class="pl-2 font-normal">{{ project.name }} ({{ project.hours }})</div>
                </div>
                <div class="flex justify-center mb-2 font-bold text-base">
                    Ilość godzin w tym miesiącu: {{ totalHours }}
                </div>
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Dzień
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Typ Pracy
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Projekt(Ilość Godzin)
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left w-56">
                                Opis
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="timestamp in timestamps" :key="timestamp">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ moment(timestamp.day, 'YYYY-MM-DD').format('DD.MM.YYYY') }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ timestamp.name }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{timestamp.project_name}} ({{ timestamp.working_hours }})
                            </td>
                            <td class="border-t-0 align-middle border-l-0 border-r-0 truncate  max-w-xs" v-tooltip.left="{value:timestamp.description, class: 'custom-tooltip'}">
                                {{ timestamp.description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
<style>
.custom-tooltip{
    max-width: 500px;
}
</style>
<style scoped>
.wrap-table {
    table-layout: fixed;
}
.wrap-table td {
    word-wrap: break-word;
}
</style>
