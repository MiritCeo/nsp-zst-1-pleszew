<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import InputError from '@/Components/InputError.vue';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
const holidayError = ref(false);
const toast = useToast();

const props = defineProps({
    user: Object,
    holiday: Number,
    workerCost: Object
});

const form = useForm({
hours: props.workerCost?.hours || 0,
wage: props.workerCost?.hourly_wage || 0,
tax: props.workerCost?.tax || 0,
cost: props.workerCost?.total_cost || 0,
});

const yearlyHolidayCost = computed(() => {
    return (props.holiday*8*(form.cost/168)).toFixed(2)
});

const monthlyHolidayCost = computed(() => {
    return (yearlyHolidayCost.value/12).toFixed(2)
});

const companyEarning = computed(() => {
    return (form.hours*form.wage*((100-form.tax)/100)-form.cost).toFixed(2)
});

const companyEarningWithHoliday = computed(() => {
    return (companyEarning.value-monthlyHolidayCost.value).toFixed(2)
});

const submit = () => {
    form.post(route('admin.user.cost.save', props.user), {
        onStart: () => {
            holidayError.value = false;
            form.clearErrors()
            if (props.holiday === 0 ) {
                toast.add({
                    severity: 'error',
                    summary: 'Błąd',
                    detail: 'Uzupełnij ilość urlopów, aby otrzymac wyniki',
                    life: 3000
                });
                form.cancel();
                holidayError.value = true;
            }
        },
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Powodzenie',
                detail: 'Wartości zapisane w bazie',
                life: 3000
            });
        },
        onError: (errors) => {
            toast.add({
                severity: 'error',
                summary: 'Błąd',
                detail: 'Wystąpił błąd',
                life: 3000
            });
        }
    }
)};
</script>
<template>
    <AdminLayout>
        <Toast />
        <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl">
            <h1 class="flex justify-center mb-2 font-bold text-lg">
                Karta pracownika: {{ user.name }}
            </h1>
            <div class="flex justify-between mb-2">
                <p v-if="holiday === 0" class="font-semibold">
                    Ilość dni urlopu: Nie podano
                    <InputError class="mt-2" :message="'Wystąpił błąd, ustaw ilość dni urlopu'" v-if="holidayError" />
                </p>
                <p v-else class="font-semibold">
                    Ilość dni urlopu: {{ holiday }}
                </p>
                <p>
                    <a class="text-sm text-gray-700 dark:text-gray-500" :href="route('admin.user.holiday.amount', user)" as="button" method="GET">
                        <Button label="Help" class="p-button-help">
                            Urlopy
                        </Button>
                    </a>
                </p>
            </div>
            <form @submit.prevent="submit">
                <div class="mb-4 grid gap-3" style="grid-template-columns: repeat(4, minmax(0, 1fr));">
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Ilość godzin</label>
                        <InputNumber mode="decimal" :maxFractionDigits="2" v-model="form.hours" placeholder="Wpisz ilość godzin" />
                        <InputError class="mt-2" :message="form.errors.hours" v-if="form.errors" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Stawka godzinowa</label>
                        <InputNumber mode="decimal" :maxFractionDigits="2" v-model="form.wage" placeholder="Wpisz stawkę godzinową" />
                        <InputError class="mt-2" :message="form.errors.wage" v-if="form.errors" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Podatek</label>
                        <InputNumber mode="decimal" :maxFractionDigits="2" v-model="form.tax" placeholder="Wpisz ilość podatku" />
                        <InputError class="mt-2" :message="form.errors.tax" v-if="form.errors" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Koszt całkowity</label>
                        <InputNumber mode="decimal" :maxFractionDigits="2" v-model="form.cost" placeholder="Wpisz koszt całkowity" />
                        <InputError class="mt-2" :message="form.errors.cost" v-if="form.errors" />
                    </div>
                </div>
                <div class="justify-end">
                    <PrimaryButton :disabled="form.processing">
                        Zapisz
                    </PrimaryButton>
                </div>
            </form>
            <div class="block w-full overflow-x-auto mt-4">
                <table class="items-center bg-transparent w-full border-collapse ">
                    <thead>
                        <tr>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                Zarobek firmy
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                Zarobek firmy z uwzględnieniem urlopu
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                Roczny koszt urlopu
                            </th>
                            <th class="px-6 bg-blue-50 text-blue-500 align-middle border border-solid border-blue-100 py-3 border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                Miesięczny koszt urlopu
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="form.hours == 0 || form.cost == 0 || holiday === 0">
                            <td class="border-t-0 px-6 border-l-0 border-r-0 whitespace-nowrap p-4 text-center text-lg font-semibold" :colspan="4">
                                Brakuje niektórych danych
                            </td>
                        </tr>
                        <tr v-else>
                            <td class="border-t-0 px-6 border-l-0 border-r-0 whitespace-nowrap p-4 text-center">
                                {{ companyEarning }}
                            </td>
                            <td class="border-t-0 px-6 border-l-0 border-r-0 whitespace-nowrap p-4 text-center">
                                {{ companyEarningWithHoliday }}
                            </td>
                            <td class="border-t-0 px-6 border-l-0 border-r-0 whitespace-nowrap p-4 text-center">
                                {{ yearlyHolidayCost }}
                            </td>
                            <td class="border-t-0 px-6 border-l-0 border-r-0 whitespace-nowrap p-4 text-center">
                                {{ monthlyHolidayCost }}
                            </td>
                        </tr>
                    </tbody>
                </table>
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
