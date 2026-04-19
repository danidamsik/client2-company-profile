<script setup>
defineProps({
    tone: {
        type: String,
        default: 'primary',
    },
});
</script>

<template>
    <span
        class="inline-flex items-center rounded-md border px-3 py-1 text-body-sm font-semibold"
        :class="{
            'border-brand-primary bg-brand-primary text-brand-ink': tone === 'primary',
            'border-brand-line bg-white text-brand-charcoal': tone === 'neutral',
            'border-white/20 bg-white/10 text-white': tone === 'dark',
        }"
    >
        <slot />
    </span>
</template>
