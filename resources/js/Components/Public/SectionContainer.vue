<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    tone: {
        type: String,
        default: 'white',
    },
    compact: {
        type: Boolean,
        default: false,
    },
    tightTop: {
        type: Boolean,
        default: false,
    },
});

const toneClasses = computed(() => ({
    white: 'bg-white',
    muted: 'bg-brand-muted',
    soft: 'bg-brand-soft',
    dark: 'bg-brand-ink text-white',
}[props.tone]));

const spacingClasses = computed(() => {
    if (props.tightTop) {
        return 'pt-4 pb-12 md:pt-6 md:pb-16';
    }

    return props.compact ? 'py-10 md:py-12' : 'py-12 md:py-16';
});
</script>

<template>
    <section :id="id" class="scroll-mt-24" :class="toneClasses">
        <div class="container" :class="spacingClasses">
            <slot />
        </div>
    </section>
</template>
