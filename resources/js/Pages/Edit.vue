<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { useToast } from "primevue/usetoast";
import InputNumber from 'primevue/inputnumber';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Divider from 'primevue/divider';
import Chip from 'primevue/chip';
import Toast from 'primevue/toast';
import moment from 'moment';
import UserLayout from '@/Layouts/UserLayout.vue';

const start = new Date(props.timestamp.start_time);
start.getHours();
const end = new Date(props.timestamp.end_time);
end.getHours();
const toast = useToast();
const workingHoursError = ref(false);
const maxTwoMonths = new Date();
maxTwoMonths.setDate(maxTwoMonths.getDate()+60);

const form = useForm({
    day: new Date(props.timestamp.day),
    start_time: start,
    end_time: end,
    fields: [
        {
            work_type: props.timestamp.entries[0].work_type_id,
            project: props.timestamp.entries[0].project_id,
            working_hours: props.timestamp.entries[0].working_hours,
            description: props.timestamp.entries[0].description,
        },
    ]
});

function getProjectNumber(indexNumber) {
    return indexNumber + 1;
}

const props = defineProps({
    work_types: Array,
    projects: Array,
    timestamp: Array,
});

const activities = props.timestamp.entries
activities.forEach((value, index) => {
    if(index != 0){
        form.fields.push({
        work_type: value.work_type.id,
        project: value.project.id,
        working_hours: value.working_hours,
        description: value.description,
    })
    }
});

function addField() {
    form.fields.push({
        work_type: null,
        project: null,
        working_hours: null,
        description: null,
    })
};

function removeField(index) {
    if (form.fields.length > 1) {
        form.fields.splice(index, 1);
    }
};

const submit = () => {
    form.put(route('update.timebox', moment(props.timestamp.day).format('DD-MM-YYYY')), {
        onStart: () => {
            form.clearErrors();
        },
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Powodzenie',
                detail: 'Formularz zapisany',
                life: 3000
            });
        },
        onError: (errors) => {
            form.setError('day', errors.day);
            toast.add({
                severity: 'error',
                summary: 'Błąd',
                detail: 'Wystąpił błąd podczas zapisu formularza',
                life: 3000
            })
        }
    });
};
</script>
<template>
    <UserLayout>
        <Toast />

        <Head title="Edit" />
        <form @submit.prevent="submit">
            <div class="flex justify-between">
                <Link class="text-sm" :href="route('home')" method="get" as="button">
                    <PrimaryButton class="mb-3 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-500 focus:border-indigo-600">
                        <div class="pi pi-home" />
                        Powrót
                    </PrimaryButton>
                </Link>
                <Link class="text-sm" :href="route('delete.timebox', timestamp)" as="button" method="DELETE">
                    <PrimaryButton class="mb-3 bg-red-500 hover:bg-red-600 active:bg-red-500 focus:border-red-600">
                        <div class="pi pi-trash" />
                        Usuń
                    </PrimaryButton>
                </Link>
            </div>
            <div class="mb-4 grid grid-cols-1 gap-3 flex-wrap md:grid-cols-3">
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold">Dzień</label>
                    <Calendar v-model="form.day" :showTime="false" :maxDate="maxTwoMonths" dateFormat="dd-mm-yy" disabled/>
                    <InputError class="mt-2" :message="form.errors.day" />
                </div>
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold">Początek</label>
                    <Calendar v-model="form.start_time" :stepMinute="15" :showTime="true" :timeOnly="true" :maxDate="form.end_time" />
                    <InputError class="mt-2" :message="form.errors.start_time" />
                </div>
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold">Koniec</label>
                    <Calendar v-model="form.end_time" :stepMinute="15" :showTime="true" :timeOnly="true" :minDate="form.start_time" />
                    <InputError class="mt-2" :message="form.errors.end_time" />
                </div>
            </div>
            <div v-for="(field, index) in form.fields" :key="index">
                <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Projekt</label>
                        <Dropdown v-model="field.project" :options="projects" optionValue="id" optionLabel="name" placeholder="Wybierz projekt" :filter="true" filterPlaceholder="Szukaj projektu" :showClear="true" />
                        <InputError class="mt-2" :message="form.errors[`fields.${index}.project`]" v-if="form.errors" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold">Rodzaj pracy</label>
                        <Dropdown v-model="field.work_type" :options="work_types" optionValue="id" optionLabel="name" placeholder="Wybierz tryb pracy" :filter="true" filterPlaceholder="Szukaj trybu pracy" :showClear="true" />
                        <InputError class="mt-2" :message="form.errors[`fields.${index}.work_type`]" v-if="form.errors" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-2 font-semibold w-min-0">Ilość godzin</label>
                        <InputNumber v-model="field.working_hours" :maxFractionDigits="2" showButtons :step="0.25" placeholder="Wpisz ilość godzin w projekcie" />
                        <InputError class="mt-2" :message="form.errors[`fields.${index}.working_hours`]" v-if="form.errors" />
                        <InputError class="mt-2" :message="'Wystąpił błąd, przelicz ponownie godziny'" v-if="workingHoursError" />
                    </div>
                </div>
                <div class="grid col-span-3">
                    <label for="end" class="mb-2 font-semibold">Opis</label>
                    <Textarea v-model="field.description" :autoResize="true" maxlength="255" rows="1" placeholder="Dodaj opcjonalny opis czynności w projekcie" />
                    <InputError class="mt-2" :message="form.errors[`fields.${index}.description`]" v-if="form.errors" />
                    <i
                        class="pi pi-trash cursor-pointer mt-4 mb-0 mx-auto"
                        style="font-size: 2rem; color: #e60000"
                        @click="removeField(index)"
                        v-if="index != 0"
                    />
                </div>
                <Divider class="mb-4 grid col-span-3" align="center" type="solid">
                    <Chip :label="'Projekt '+ getProjectNumber(index)" class="custom-chip" />
                </Divider>
            </div>
            <div class="flex justify-center flex-wrap md:justify-between">
                <div class="mb-2 w-full md:w-auto">
                    <PrimaryButton class="w-full" type="button" @click="addField()">
                        Dodaj czynność
                    </PrimaryButton>
                </div>
                <div class="w-full md:w-auto">
                    <PrimaryButton class="w-full" :disabled="form.processing">
                        Zapisz formularz
                    </PrimaryButton>
                </div>
            </div>
        </form>
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
</style>
