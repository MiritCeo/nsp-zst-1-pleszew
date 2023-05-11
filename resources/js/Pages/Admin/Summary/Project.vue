<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref, watch, toRef } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import Tooltip from 'primevue/tooltip';
const props = defineProps({
    projects: Object,
    dateValue: String,
    searchValue: String
})

const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);
let totalHours = 0;
let totalEarnings = 0;

watch(filterDate, async (nv, ov) => {
    Inertia.get(route("admin.summary.project"), { date: filterDate.value, search: props.searchValue });
});

async function searchProject(event) {
    Inertia.get(route("admin.summary.project"), { search: event.target.value, date: filterDate.value });
}

async function findProject(project) {
    Inertia.get(route("admin.project.excelCreate", project), { date: filterDate.value });
}

const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM');
}

props.projects.forEach(project => {
    totalHours += project.hours
    totalEarnings += project.earnings
});

</script>
<template>
    <AdminLayout>
        <div class="mt-14 flex flex-row" style="background-color: rgb(184 184 184);">
            <a :href="route('admin.summary.user')" class="ml-14 p-3 bg-gray-200 w-32 text-center rounded-tl-lg hover:bg-gray-300" as="button" method="GET">
                Użytkownicy
            </a>
            <a :href="route('admin.summary.project')" class="p-3 bg-white w-32 text-center rounded-tr-lg" as="button" method="GET">
                Projekty
            </a>
        </div>
        <div class="max-w-7xl mx-auto mb-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Podsumowanie projektów
            </h1>
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj projekt" @change="searchProject"/>
                </h3>
                <Calendar v-model="date"  :date-select="searchDate()" view="month" dateFormat="yy-mm" placeholder="Wybierz Datę"/>
            </div>
            <div class="flex justify-center mb-2 font-bold text-base">
                <div class="mr-10">
                    Ilość godzin razem: {{ totalHours }}
                </div>
                <div class="ml-10">
                    Zarobki Firmy: {{ totalEarnings }}
                </div>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Imię i Nazwisko
                                <div class="pi pi-info-circle" v-tooltip.right="{value: 'Jeśli nazwa projektu jest na czerwono, to trzeba sprawdzić, czy wszyscy użytkownicy projektu mają uzupełnioną kartę pracownika, ponieważ zarobki z projektu mogą się nie zgadzać!', class: 'custom-tooltip'}" />
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Zarobki z projektu
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Ilość Godzin
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(project, index) in projects" :key="index">
                            <td v-if="project.hours === project.checkHours" class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <Link @click="findProject(project)">
                                    {{ project.name }}
                                </Link>
                            </td>
                            <td v-else class="border-t-0 px-6 text-red-600 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <Link @click="findProject(project)">
                                    {{ project.name }}
                                </Link>
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ project.earnings }}
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ project.hours }}
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