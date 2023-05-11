<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref, toRef, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Tooltip from '@/Components/Tooltip.vue';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import moment from 'moment';
import Calendar from 'primevue/calendar';
import UserLayout from '@/Layouts/UserLayout.vue';

const toast = useToast();
const dateValueCopy = toRef(props, 'dateValue');
const date = ref(dateValueCopy.value)
const filterDate = ref(dateValueCopy.value);
let totalHours = 0;

const props = defineProps({
    timestamps: Array,
    dateValue: String,
})

watch(filterDate, (nv, ov) => {
    Inertia.get(route("details", {date: filterDate.value }));
})

const searchDate = () => {
    filterDate.value = moment(date.value).format('YYYY-MM');
}

props.timestamps.forEach(timestamp => {
    totalHours += timestamp.daily_working_hours
});
</script>
<template>
    <UserLayout>
        <Toast />
        
        <Head title="Details" />
        <div class="flex justify-center mb-2 mt-2">
            <span>
                <strong>Miesiąc: </strong>
                <Calendar v-model="date"  :date-select="searchDate()" view="month" dateFormat="yy-mm" placeholder="Wybierz Datę"/>
            </span>
        </div>
        <div class="flex justify-center mb-2 font-bold text-base">
            Ilość godzin w tym miesiącu: {{ totalHours }}
        </div>
        <div class="block w-full overflow-x-auto">
            <table class="items-center bg-transparent w-full border-collapse ">
                <thead>
                    <tr>
                        <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                            Dzień
                            <div class="pi pi-info-circle" v-tooltip.right="{value: 'Po kliknięciu w datę, przejdzie się do edycji danej daty', class: 'custom-tooltip'}" />
                        </th>
                        <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                            Start i Koniec
                        </th>
                        <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                            Czas Pracy
                        </th>
                        <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left w-56">
                            Projekt (tryb pracy) (czas)
                        </th>
                        <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                            Opis
                            <div class="pi pi-info-circle" v-tooltip.left="{value: 'Po najechaniu kursorem na opis, wyświetli się także dymek z tym opisem', class: 'custom-tooltip'}" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="timestamp in timestamps" :key="timestamp">
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                            <Link :href="route('edit.timebox', moment(timestamp.day, 'YYYY-MM-DD').format('DD-MM-YYYY'))">
                                {{ moment(timestamp.day, 'YYYY-MM-DD').format('DD-MM-YYYY') }}
                            </Link>
                        </td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                            {{ moment(timestamp.start_time).format("HH:mm") }}-{{ moment(timestamp.end_time).format("HH:mm") }}
                        </td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                            {{ timestamp.daily_working_hours }}
                        </td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                            <template v-for="(entry, entryIndex) in timestamp.entries" :key="entryIndex">
                                {{ entry.project.name }} ({{ entry.work_type.name }}) ({{ entry.working_hours }})
                                <br />
                            </template>
                        </td>
                        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 whitespace-nowrap p-4">
                            <template v-for="(entry, entryIndex) in timestamp.entries" :key="entryIndex">
                                <div class="border-t-0 align-middle border-l-0 border-r-0 truncate  max-w-xs" v-tooltip.left="{value:entry.description, class: 'custom-tooltip'}">
                                    {{ entry.description }}
                                </div>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </UserLayout>
</template>
<style scoped>
.p-chip.custom-chip {
    background: var(--primary-color);
    color: var(--primary-color-text);
}
.table-responsive {
    overflow-x: auto;
    min-height: 0.01%;
}
.table {
    width: 100%;
    max-width: 100%;
}
.fade-enter-active,
    .fade-leave-active {
        transition: opacity 2s ease-out;
    }

    .fade-enter,
    .fade-leave-active {
        opacity: 0;
    }
</style>
