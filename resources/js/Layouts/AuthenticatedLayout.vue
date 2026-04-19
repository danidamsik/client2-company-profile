<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <AdminLayout :title="title" :description="description">
        <template v-if="$slots.header" #header>
            <slot name="header" />
        </template>

        <slot />
    </AdminLayout>
</template>
