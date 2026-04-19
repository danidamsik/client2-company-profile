<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-body-sm font-medium text-red-700">
            {{ message }}
        </p>
    </div>
</template>
