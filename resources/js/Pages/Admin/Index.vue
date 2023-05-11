<script setup>
import { ref, watch, toRef } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import moment from 'moment';
import Calendar from 'primevue/calendar';
const props = defineProps({
    timestamps: Array,
    searchValue: String,
    dayValue: String,
})

const dayValueCopy = toRef(props, 'dayValue');
const date = ref(dayValueCopy.value)
const filterDate = ref(dayValueCopy.value);

watch(filterDate, (nv, ov) => {
    Inertia.get(route("admin.index", {day: filterDate.value, search: props.searchValue }));
})
async function searchTimestamp(event) {
    Inertia.get(route("admin.index", { search: event.target.value, day: filterDate.value }));
}
function addDay() {
    filterDate.value = moment(filterDate.value).add(1, "d").format('YYYY-MM-DD');
}
function substractDay() {
    filterDate.value = moment(filterDate.value).add(-1, "d").format('YYYY-MM-DD');
}

const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM-DD');
}
</script>
<template>
    <AdminLayout>
        <Head title="Admin" />
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <div class="flex justify-between mb-2 mt-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj użytkownika" @change="searchTimestamp"/>
                </h3>
                <button @click="filterDate = moment().format('YYYY-MM-DD')" class="bg-blue-500 text-white active:bg-blue-800 font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                    Dzisiaj
                </button>
            </div>
            <div class="flex justify-between mb-2 mt-2">
                <button @click="substractDay()" class="bg-indigo-500 text-white active:bg-indigo-600 font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                    Poprzedni
                </button>
                <span>
                    <strong>Dzień: </strong>
                    <Calendar v-model="date" :date-select="searchDate()" :showTime="false" dateFormat="yy-mm-dd" />
                </span>
                <button @click="addDay()" class="bg-indigo-500 text-white active:bg-indigo-600 font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                    Następny
                </button>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Id
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Użytkownik
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Start i koniec
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Czas pracy
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Projekt (czas)
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Projekt (tryb pracy)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(timestamp, index) in timestamps.data" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{
                                    (timestamps.current_page - 1) * timestamps.per_page +
                                    index +
                                    1
                                }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4 ">
                                {{ timestamp.user.name }}
                            </td>
                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ moment(timestamp.start_time).format("HH:mm") }}-{{ moment(timestamp.end_time).format("HH:mm") }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ timestamp.daily_working_hours }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <template v-for="(entry, entryIndex) in timestamp.entries" :key="entryIndex">
                                    {{ entry.project.name }} ({{ entry.working_hours }})
                                    <br />
                                </template>
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <template v-for="(entry, entryIndex) in timestamp.entries" :key="entryIndex">
                                    {{ entry.project.name }} ({{ entry.work_type.name }})
                                    <br />
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="timestamps.links" />
            </div>
        </div>
    </AdminLayout>
</template>
