<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
</script>
<template>
    <header class="header sticky top-0 bg-white shadow-md flex items-center justify-between px-4 py-2 z-10">
        <Link href="/">
            <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
        </Link>

    <!-- navigation -->
        <nav class="nav font-semibold text-lg">
            <ul class="flex items-center">
                <li class="mr-4">
                    <Link :href="route('admin.index')">
                        Zalogowane godziny
                    </Link>
                </li>
                <li class="mr-4">
                    <Link :href="route('admin.project.index')">
                        Projekty
                    </Link>
                </li>
                <li class="mr-4">
                    <Link :href="route('admin.work_type.index')">
                        Tryby pracy
                    </Link>
                </li>
                <li class="mr-4">
                    <Link :href="route('admin.user.index')">
                        Użytkownicy
                    </Link>
                </li>
              
            </ul>
        </nav>

        <!-- buttons --->
        <div class="flex justify-end">
            <Link class="text-sm mr-2 text-gray-700 dark:text-gray-500" :href="route('logout')" method="post" as="button">
                <PrimaryButton>
                    Wyloguj się
                </PrimaryButton>
            </Link>
        </div>
    </header>
    <body class="bg-white">
        <slot />
    </body>

</template>
