<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
</script>

<template>
    <div class="fixed top-0 right-0 px-6 py-4 sm:block z-10">
        <template v-if="$page.props.auth.user">
            <Link class="text-sm text-gray-700 dark:text-gray-500 mr-1" :href="route('admin.index')" v-if="$page.props.auth.user.is_admin">
                <PrimaryButton>
                    Panel admina
                </PrimaryButton>
            </Link>
            <Link class="text-sm text-gray-700 dark:text-gray-500" :href="route('logout')" method="post" as="button">
                <PrimaryButton>
                    Wyloguj się
                </PrimaryButton>
            </Link>
        </template>
    </div>
    <div class="max-w-7xl mx-auto mb-14">
        <div class="mt-14 flex flex-row">
            <nav v-if="$page.component !== 'Edit'">
                <Link :href="route('home')" :class="{ 'active': $page.component === 'Welcome' }" class="ml-12 md:ml-14 bg-gray-200 p-3 w-32 text-center rounded-tl-lg hover:bg-gray-300" method="get" as="button" type="button">
                    Timebox
                </Link>
                
            </nav>
        </div>
        <div class="bg-white p-8 rounded-xl shadow shadow-slate-300">
            <slot />
        </div>
    </div>
</template>
<style scoped>
    .active {
        background-color: white;
    }
</style>