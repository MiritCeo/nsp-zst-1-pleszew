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
    user: Object,
    dateValue: String,
    holidayMax: Number,
    holidayLeft: Number
})

const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);
let totalHours = 0;
const today = moment().format('YYYY-MM-DD');

watch(filterDate, async (nv, ov) => {
    Inertia.get(route("admin.user.excelHoliday", props.user), { date: filterDate.value });
})
const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY');
}
const exportReport = computed(() => {
    return window.location.pathname+`/export?date=${filterDate.value}`;
})
props.timestamps.data.forEach(timestamp => {
    totalHours += timestamp.hours
});
</script>
<template>
    <AdminLayout>
        <div class="mt-14 flex flex-row" style="background-color: rgb(184 184 184);">
            <a :href="route('admin.user.excelCreate', user)" class="ml-14 p-3 bg-gray-200 w-32 text-center rounded-tl-lg hover:bg-gray-300" as="button" method="GET">
                Użytkownicy
            </a>
            <a :href="route('admin.user.excelHoliday', user)" class="p-3 bg-white w-32 text-center rounded-tr-lg" as="button" method="GET">
                Projekty
            </a>
        </div>
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Raport Urlopu dla: {{ user.name }}
            </h1>
            <div class="flex justify-between mb-2">
                <Calendar v-model="date"  :date-select="searchDate()" view="year" dateFormat="yy" placeholder="Wybierz Rok"/>
                <a class="text-sm text-gray-700 dark:text-gray-500 justify-center flex" :href="exportReport">
                    <Button label="Primary">
                        Pobierz raport
                    </Button>
                </a>
            </div>
            <div class="flex justify-between items-center mb-2 text-base">
                <div class="">
                    Ilość dni: {{ props.holidayMax }}
                </div>
                <div>
                    Zostało: {{ props.holidayLeft }}
                </div>
                <a class="text-sm text-gray-700 dark:text-gray-500" :href="route('admin.user.holiday.amount', user)" as="button" method="GET">
                    <Button label="Warning" class="p-button-warning">
                        Ustaw ilość dni
                    </Button>
                </a>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Dzień
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
                        <template v-for="(timestamp, index) in timestamps.data" :key="index">
                            <template v-if="timestamp.day >= today">
                                <tr class="bg-green-100">
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ moment(timestamp.day, 'YYYY-MM-DD').format('DD.MM.YYYY') }}
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ timestamp.work_type }}
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ timestamp.hours }}
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr class="bg-red-100">
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ moment(timestamp.day, 'YYYY-MM-DD').format('DD.MM.YYYY') }}
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ timestamp.work_type }}
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                        {{ timestamp.hours }}
                                    </td>
                                </tr>
                            </template>
                        </template>
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