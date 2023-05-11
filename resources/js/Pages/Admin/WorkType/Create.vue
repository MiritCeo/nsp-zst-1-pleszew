<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import Checkbox from '@/Components/Checkbox.vue';

const toast = useToast();
const form = useForm({
    name: '',
    is_holiday: '',
    is_workday: ''
});

const submit = () => {
    form.post(route('admin.work_type.store'), {
        onStart: () => {
            form.clearErrors();
            if (form.is_holiday === 1 && form.is_workday === 1) {
                toast.add({severity:'error', summary: 'Błąd', detail:'Zaznacz czy tryb pracy jest urlopem, czy trybem roboczym', life: 3000})
                form.cancel();
            }
        },
        onSuccess: () => {
            toast.add({severity:'success', summary: 'Powodzenie', detail:'Tryb pracy został dodany', life: 3000});
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
            <h1>Dodaj tryb pracy</h1>
            <form @submit.prevent="submit">
                <div class="flex flex-col mt-4">
                    <label class="mb-2 font-semibold">Nazwa trybu pracy</label>
                    <InputText type="text" v-model="form.name" placeholder="Wpisz nazwę trybu pracy" />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>
                <div class="flex justify-start items-center mt-1">
                    <label for="is_holiday">Dzień wolny płatny (Urlop)</label>
                    <Checkbox v-model="form.is_holiday" :true-value="1" :false-value="0" name="is_holiday" id="is_holiday"/>
                </div>
                <div class="flex justify-start items-center mt-1">
                    <label for="is_workday">Tryb pracy roboczy</label>
                    <Checkbox v-model="form.is_workday" :true-value="1" :false-value="0" name="is_workday" id="is_workday"/>
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
