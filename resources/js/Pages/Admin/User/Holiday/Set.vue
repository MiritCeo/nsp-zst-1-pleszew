<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

const toast = useToast();
const props = defineProps({
    user: Object,
    holiday: Object,
    previous: String
})

const form = useForm({
    year: '',
    days: '',
    hidden: null
});

const submit = () => {
    form.hidden = props.previous
    form.put(route('admin.user.holiday.set', props.user), {
        onStart: () => {
            form.clearErrors();
        },
        onSuccess: () => {
            toast.add({severity:'success', summary: 'Powodzenie', detail:'Formularz zapisany', life: 3000});
            form.reset();
        },
        onError: () => toast.add({severity:'error', summary: 'Błąd', detail:'Wystąpił błąd podczas zapisu formularza', life: 3000})
    });
};
</script>
<template>
    <div class="max-w-7xl mx-auto my-14 bg-white p-8 rounded-xl shadow shadow-slate-300">
        <Toast />
        <div>
            <h1>Ustaw lub edytuj ilość dostępnych urlopów na dany rok dla {{ user.name }}</h1>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 mt-4">
                    <div class="flex flex-col mr-1">
                        <label class="mb-2 font-semibold">Ilość</label>
                        <InputText v-model="form.days" type="number" inputId="integeronly" placeholder="Wpisz ilość dni" :min="0" :max="50"/>
                        <InputError class="mt-2" :message="form.errors.days"/>
                    </div>
                    <div class="flex flex-col ml-1">
                        <label class="mb-2 font-semibold">Rok</label>
                        <Calendar v-model="form.year" view="year" dateFormat="yy" placeholder="Wybierz Rok"/>
                        <InputError class="mt-2" :message="form.errors.year"/>
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <BackButton />
                    <Button label="Primary" type="submit">
                        Zapisz formularz
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
