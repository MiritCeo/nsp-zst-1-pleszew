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
    users: Object,
    dateValue: String,
    searchValue: String
})

const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);
let totalHours = 0;
let totalCost = 0;
let totalEarnings = 0;

watch(filterDate, async (nv, ov) => {
    Inertia.get(route("admin.summary.user"), { date: filterDate.value, search: props.searchValue });
});

async function searchUser(event) {
    Inertia.get(route("admin.summary.user"), { search: event.target.value, date: filterDate.value });
}

const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM');
}

props.users.forEach(user => {
    totalHours += user.hours
    if (user.worker_costs !== null && user.holidays.length !== 0) {
        totalCost += user.worker_costs.total_cost
        totalEarnings += calculateEarnings(user)
    }
});

function calculateEarnings(user) {
    let earning = 0;
    if (user.worker_costs !== null && user.holidays.length !== 0) {
        earning = user.hours*user.worker_costs.hourly_wage*((100-user.worker_costs.tax)/100)-user.worker_costs.total_cost-(user.holidays[0].max_holiday*8*(user.worker_costs.total_cost/168))/12;
    }
    return earning;
}

function findUser(user) {
    Inertia.get(route("admin.user.excelCreate", user), { date: filterDate.value });
}

function decideAboutClass(user) {
    let styleArray = [];
    if (calculateEarnings(user)<0) { 
        styleArray.push('text-red-600')
    }
    return styleArray;
}
</script>
<template>
    <AdminLayout>
        <div class="mt-14 flex flex-row" style="background-color: rgb(184 184 184);">
            <a :href="route('admin.summary.user')" class="ml-14 p-3 bg-white w-32 text-center rounded-tl-lg disabled" as="button" method="GET">
                Użytkownicy
            </a>
            <a :href="route('admin.summary.project')" class="p-3 bg-gray-200 w-32 text-center rounded-tr-lg hover:bg-gray-300" as="button" method="GET">
                Projekty
            </a>
        </div>
        <div class="max-w-7xl mx-auto mb-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Podsumowanie firmy
            </h1>
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold text-base text-blue-700">
                    <InputText type="text" autofocus :value="props.searchValue" placeholder="Wyszukaj użytkownika" @change="searchUser"/>
                </h3>
                <Calendar v-model="date"  :date-select="searchDate()" view="month" dateFormat="yy-mm" placeholder="Wybierz Datę"/>
            </div>
            <div class="flex justify-center mb-2 font-bold text-base">
                <div class="mr-10">
                    Ilość godzin razem: {{ totalHours }}
                </div>
                <div class="mr-10">
                    Koszt całkowity razem: {{ totalCost.toFixed(2) }}
                </div>
                <div>
                    Łączny zysk firmy: {{ totalEarnings.toFixed(2) }}
                </div>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Imię i Nazwisko
                                <div class="pi pi-info-circle" v-tooltip.right="{value: 'Po kliknięciu imienia oraz nazwiska użytkownika, przechodzimy na stronę raportu danego użytkownika', class: 'custom-tooltip'}" />
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Ilość Godzin
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Koszt Całkowity
                                <div class="pi pi-info-circle" v-tooltip.right="{value: 'Po kliknięciu Kosztu Całkowitego przy użytkowniku, przechodzimy na stronę Karty Pracownika danego użytkownika', class: 'custom-tooltip'}" />
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left w-56">
                                Zarobek firmy
                                <div class="pi pi-info-circle" v-tooltip.right="{value: 'Po kliknięciu Zarobku Firmy przy użytkowniku, przechodzimy na stronę Karty Pracownika danego użytkownika', class: 'custom-tooltip'}" />
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index">
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <Link @click="findUser(user)" :class="decideAboutClass(user)">
                                    {{ user.name }}
                                </Link>
                            </td>
                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                {{ user.hours }}
                            </td>
                            <td v-if="user.worker_costs !== null && user.holidays.length !== 0" class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a :href="route('admin.user.cost', user)" as="button" method="GET">
                                    {{ user.worker_costs.total_cost }}
                                </a>
                            </td>
                            <td v-else class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a :href="route('admin.user.cost', user)" as="button" method="GET">
                                    Brak Danych
                                </a>
                            </td>
                            <td v-if="user.worker_costs !== null && user.holidays.length !== 0" class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a :href="route('admin.user.cost', user)" as="button" method="GET">
                                    {{ calculateEarnings(user).toFixed(2) }}
                                </a>
                            </td>
                            <td v-else class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                                <a :href="route('admin.user.cost', user)" as="button" method="GET">
                                    <Button label="Warning" class="p-button-warning">
                                        Ustaw Tutaj
                                    </Button>
                                </a>
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