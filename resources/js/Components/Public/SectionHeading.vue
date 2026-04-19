<script setup>
import { computed } from 'vue';

const props = defineProps({
    eyebrow: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    align: {
        type: String,
        default: 'left',
    },
    inverse: {
        type: Boolean,
        default: false,
    },
});

const alignClasses = computed(() => ({
    left: 'text-left',
    center: 'mx-auto text-center',
}[props.align]));
</script>

<template>
    <div class="w-full max-w-3xl px-2 sm:px-0" :class="alignClasses">
        <p v-if="eyebrow" class="mb-3 text-body-sm font-bold uppercase text-brand-accent">
            {{ eyebrow }}
        </p>

        <h2
            class="break-words text-[1.85rem] font-bold leading-10 md:text-display-md"
            :class="inverse ? 'text-white' : 'text-brand-ink'"
        >
            {{ title }}
        </h2>

        <p
            v-if="subtitle"
            class="mt-4 max-w-2xl text-body-lg"
            :class="inverse ? 'text-white/80' : 'text-stone-600'"
        >
            {{ subtitle }}
        </p>
    </div>
</template>
