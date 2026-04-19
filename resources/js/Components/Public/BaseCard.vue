<script setup>
import { computed } from 'vue';

const props = defineProps({
    as: {
        type: String,
        default: 'div',
    },
    hover: {
        type: Boolean,
        default: false,
    },
    tone: {
        type: String,
        default: 'white',
    },
    padded: {
        type: Boolean,
        default: true,
    },
});

const toneClasses = computed(() => ({
    white: 'border-brand-line bg-white',
    soft: 'border-amber-100 bg-brand-soft',
    dark: 'border-brand-charcoal bg-brand-ink text-white',
}[props.tone]));
</script>

<template>
    <component
        :is="as"
        class="rounded-lg border shadow-card transition duration-300"
        :class="[toneClasses, padded ? 'p-6' : '', hover ? 'hover:-translate-y-1 hover:border-brand-primary hover:shadow-lift' : '']"
    >
        <slot />
    </component>
</template>
