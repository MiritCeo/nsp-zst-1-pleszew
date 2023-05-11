<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="flex justify-center">
            <a :href="route('google.auth')" class="text-sm text-gray-600 hover:text-gray-900">
                <PrimaryButton class="inline-flex">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-6 h-6" alt="">
                    <span class="ml-2">Zaloguj się</span>
                </PrimaryButton>
            </a>
        </div>
    </GuestLayout>
</template>
