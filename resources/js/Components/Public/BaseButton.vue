<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['click']);

const tag = computed(() => (props.href ? 'a' : 'button'));

const baseClasses =
    'focus-ring inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border font-semibold shadow-sm transition duration-200 active:translate-y-px';

const variantClasses = computed(() => ({
    primary: 'border-brand-primary bg-brand-primary text-brand-ink hover:border-brand-primaryDark hover:bg-brand-primaryDark hover:shadow-card',
    secondary: 'border-brand-ink bg-brand-ink text-white hover:border-brand-charcoal hover:bg-brand-charcoal hover:shadow-card',
    outline: 'border-brand-line bg-white text-brand-ink hover:border-brand-primary hover:bg-brand-soft',
    ghost: 'border-transparent bg-transparent text-brand-ink hover:bg-brand-soft',
}[props.variant]));

const sizeClasses = computed(() => ({
    sm: 'h-10 px-4 text-body-sm',
    md: 'h-11 px-5 text-body-sm',
    lg: 'h-12 px-6 text-body-md',
}[props.size]));

const disabledClasses = computed(() => (props.disabled ? 'pointer-events-none opacity-50' : ''));
</script>

<template>
    <component
        :is="tag"
        :href="href || undefined"
        :type="href ? undefined : type"
        :disabled="href ? undefined : disabled"
        :aria-disabled="disabled"
        :class="[baseClasses, variantClasses, sizeClasses, disabledClasses]"
        @click="$emit('click', $event)"
    >
        <slot name="icon" />
        <span><slot /></span>
    </component>
</template>
