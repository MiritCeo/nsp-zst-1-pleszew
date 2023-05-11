<script setup>
import Button from 'primevue/button';

function back() {
    window.history.back();
}
</script>
<template>
    <Button @click="back" label="Info" class="p-button-info">
        Back
    </Button>
</template>
