<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

const toast = useToast();
const form = useForm({
    name: '',
});
const submit = () => {
    form.post(route('admin.project.store'), {
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
            <h1>Dodaj projekt</h1>
            <form @submit.prevent="submit">
                <div class="flex flex-col mt-4">
                    <label class="mb-2 font-semibold">Nazwa projektu</label>
                    <InputText type="text" v-model="form.name" placeholder="Wpisz nazwę projektu" />
                    <InputError class="mt-2" :message="form.errors.name"/>
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
