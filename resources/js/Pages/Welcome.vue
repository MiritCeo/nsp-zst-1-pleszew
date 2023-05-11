<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Tooltip from '@/Components/Tooltip.vue';
import { useToast } from "primevue/usetoast";
import InputNumber from 'primevue/inputnumber';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Divider from 'primevue/divider';
import Chip from 'primevue/chip';
import Toast from 'primevue/toast';
import moment from 'moment';
import Checkbox from '@/Components/Checkbox.vue';

let day = new Date();
let start = new Date();
start.setHours(8, 0);
let end = new Date();
end.setHours(16, 0);
const toast = useToast();
let description = null;
let workType = null;
let workingHours = 8;
let project = null;
const workingHoursError = ref(false);
const maxTwoMonths = new Date();
let remember = 0;
maxTwoMonths.setDate(maxTwoMonths.getDate()+60);
moment.updateLocale('pl', {
    months : ['Styczeń',  'Luty',  'Marzec',  'Kwiecień',  'Maj',  'Czerwiec', 'Lipiec',  'Sierpień',  'Wrzesień',  'Październik',  'Listopad',  'Grudzień']
});

function getDaysOfMonth(daysInMonth, removeMonth) {
    let arrDays = [];
    while(daysInMonth) {
        let current = null;
        if (removeMonth == 1) {
            current = moment().add(-1, "M").date(daysInMonth);
        } else if(removeMonth == 2) {
            current = moment().add(+1, "M").date(daysInMonth);
        } else {
            current = moment().date(daysInMonth);
        }
        arrDays.push(current.format('MM-DD-YYYY'));
        daysInMonth--;
    }
    return arrDays.reverse();
}

if (props.timestamp_draft !== null) {
    day = new Date(props.timestamp_draft.day);
    start = new Date(props.timestamp_draft.start_time);
    start.getHours();
    end = new Date(props.timestamp_draft.end_time);
    end.getHours();
    workingHours = props.timestamp_draft.entries_draft[0].working_hours;
    description = props.timestamp_draft.entries_draft[0].description;
    workType = props.timestamp_draft.entries_draft[0].work_type.id;
    project = props.timestamp_draft.entries_draft[0].project.id;
    remember = props.remember_last_project;
} else if (props.remember_last_project == 1) {
    start = new Date(props.last_loged.start_time);
    start.getHours();
    end = new Date(props.last_loged.end_time);
    end.getHours();
    workingHours = props.last_loged.entries[0].working_hours;
    description = props.last_loged.entries[0].description;
    workType = props.last_loged.entries[0].work_type.id;
    project = props.last_loged.entries[0].project.id;
    remember = props.remember_last_project;
};

const form = useForm({
    day: day,
    start_time: start,
    end_time: end,
    remember_last_project: remember,
    fields: [
        {
            work_type: workType,
            project: project,
            working_hours: workingHours,
            description: description,
        },
    ]
});

function decideAboutClass(dayOfMonth) {
    let styleArray = [];
    let freeDays = [6,0];
    const isFilled = props.timestamps[moment(dayOfMonth, "MM/DD/YYYY").format('YYYY-MM-DD')];
    const isFreeDay = freeDays.includes(moment(dayOfMonth, "MM/DD/YYYY").weekday());
    if (isFreeDay) { // Jeżeli dzień wolny
        if (isFilled === undefined) { // I nie jest uzupełnione
            styleArray.push('bg-gray-400')
        } else { // I jest uzupełnione
            styleArray.push('bg-green-600')
        }
    } else { // Nie jest dniem wolnym
        if (isFilled === undefined) { // Nie jest uzupełnione
            styleArray.push('bg-red-600')
        } else { // Jest uzupełnione
            styleArray.push('bg-green-600')
        }
    }
    return styleArray
}

function getProjectNumber(indexNumber) {
    return indexNumber + 1;
}

const props = defineProps({
    work_types: Array,
    projects: Array,
    timestamps: Array,
    last_loged: Object,
    remember_last_project: Boolean,
    holiday_left: Number,
    timestamp_draft: Array
});

if(props.timestamp_draft !== null) {
    const activities = props.timestamp_draft.entries_draft
    handleMultipleProjects(activities);
} else if(props.remember_last_project === 1) {
    const activities = props.last_loged.entries
    handleMultipleProjects(activities);
}

function handleMultipleProjects(activities) {
    activities.forEach((value, index) => {
        if(index != 0) {
            form.fields.push({
            work_type: value.work_type.id,
            project: value.project.id,
            working_hours: value.working_hours,
            description: value.description,
            });
        }
    });
}

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
    form.post(route('save.timebox', moment(form.day, 'DD-MM-YYYY').format('DD-MM-YYYY')), {
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

const submitDraft = () => {
    form.post(route('save.timebox_draft', moment(form.day, 'DD-MM-YYYY').format('DD-MM-YYYY')), {
        onStart: () => {
            form.clearErrors();
        },
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Powodzenie',
                detail: 'Szkic zapisany',
                life: 3000
            });
        },
        onError: (errors) => {
            form.setError('day', errors.day);
            toast.add({
                severity: 'error',
                summary: 'Błąd',
                detail: 'Wystąpił błąd podczas zapisu',
                life: 3000
            })
        }
    });
};

function chooseDateFromCalendar(setThisDate) {
    form.day = moment(setThisDate, 'MM-DD-YYYY').format('DD-MM-YYYY');
};

function editTimebox(Date) {
    Inertia.get(route("edit.timebox", moment(Date, 'MM-DD-YYYY').format('DD-MM-YYYY')));
};
</script>
<template>
    <UserLayout>
        <Toast />

        <Head title="Welcome" />
        <form @submit.prevent="submit">
           
            <InputError :message="$page.props.flash.message" class="mb-2"/>
            <div class="mb-4 grid grid-cols-1 gap-3 flex-wrap md:grid-cols-3">
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold">Dzień</label>
                    <Calendar v-model="form.day" :showTime="false" :maxDate="maxTwoMonths" dateFormat="dd-mm-yy" />
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
              
                <Divider class="mb-4 grid col-span-3" align="center" type="solid">
                    <Chip :label="'Projekt '+ getProjectNumber(index)" class="custom-chip" />
                </Divider>
            </div>
            <div class="flex justify-center ml-4 mb-3 items-center md:justify-end">
                <label for="remember">Ustaw jako domyślne</label>
                <Checkbox v-if="$props.remember_last_project == true" v-model="form.remember_last_project" :true-value="1" :false-value="0" name="remember" id="remember" :checked="1"/>
                <Checkbox v-else v-model="form.remember_last_project" :true-value="1" :false-value="0" name="remember" id="remember"/>
            </div>
            <div class="flex justify-center flex-wrap md:justify-between">
                <div class="mb-2 w-full md:w-auto">
                    <PrimaryButton class="w-full" type="button" @click="addField()">
                        Dodaj czynność
                    </PrimaryButton>
                </div>
                <div class="mb-2 w-full md:w-auto">
                    <PrimaryButton type="button" class="w-full bg-green-500 hover:bg-green-400 active:bg-green-500 focus:border-green-400" @click="submitDraft" :disabled="form.processing">
                        Zapisz szkic
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
