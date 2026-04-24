<script setup>
import { computed } from 'vue';

const props = defineProps({
    inverse: {
        type: Boolean,
        default: false,
    },
    logoUrl: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        default: 'PT Secure Guard',
    },
    subtitle: {
        type: String,
        default: '',
    },
});

const displayName = computed(() => props.name || 'PT Secure Guard');
const displaySubtitle = computed(() => props.subtitle || '');
</script>

<template>
    <div class="flex items-center gap-3">
        <span v-if="logoUrl" class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-brand-line bg-white">
            <img :src="logoUrl" :alt="displayName" class="h-full w-full object-contain p-1" />
        </span>
        <span v-else class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-brand-primary text-brand-ink">
            <svg class="h-7 w-7" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <path
                    d="M16 3.5 26 7v7.25c0 6.35-4.05 11.85-10 14.25-5.95-2.4-10-7.9-10-14.25V7l10-3.5Z"
                    fill="currentColor"
                    opacity="0.22"
                />
                <path
                    d="M16 3.5 26 7v7.25c0 6.35-4.05 11.85-10 14.25-5.95-2.4-10-7.9-10-14.25V7l10-3.5Z"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linejoin="round"
                />
                <path d="M12 16.2 14.9 19 21 12.6" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>

        <span class="min-w-0">
            <span class="block max-w-[12rem] truncate text-body-md font-bold leading-5" :class="inverse ? 'text-white' : 'text-brand-ink'">
                {{ displayName }}
            </span>
            <span v-if="displaySubtitle" class="block max-w-[12rem] truncate text-body-sm leading-5" :class="inverse ? 'text-white/70' : 'text-stone-600'">
                {{ displaySubtitle }}
            </span>
        </span>
    </div>
</template>
