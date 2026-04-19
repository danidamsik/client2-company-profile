<script setup>
import BaseCard from '@/Components/Public/BaseCard.vue';

defineProps({
    service: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <BaseCard hover class="flex h-full flex-col">
        <div class="flex items-start justify-between gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-primary text-brand-ink">
                <svg
                    v-if="service.icon === 'shield'"
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    aria-hidden="true"
                >
                    <path
                        d="M12 3 19 5.5v5.2c0 4.5-2.8 8.7-7 10.3-4.2-1.6-7-5.8-7-10.3V5.5L12 3Z"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linejoin="round"
                    />
                </svg>
                <svg
                    v-else-if="service.icon === 'radio'"
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    aria-hidden="true"
                >
                    <path
                        d="m8 7 8-4M7 8h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2Z"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                    <path d="M9 13h6M9 16h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                <svg
                    v-else-if="service.icon === 'route'"
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    aria-hidden="true"
                >
                    <path
                        d="M5 18c4 0 4-12 8-12s4 12 8 12M5 18h16"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path d="M5 18a2 2 0 1 0 0 .1M21 18a2 2 0 1 0 0 .1" stroke="currentColor" stroke-width="2" />
                </svg>
                <svg v-else class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path
                        d="M6 20V9l6-5 6 5v11M9 20v-8h6v8"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>

            <span class="rounded-md bg-brand-soft px-3 py-1 text-body-sm font-semibold text-brand-accent">
                {{ service.category }}
            </span>
        </div>

        <h3 class="mt-6 text-xl font-bold text-brand-ink">{{ service.title }}</h3>
        <p class="mt-3 text-body-md text-stone-600">{{ service.description }}</p>

        <ul class="mt-5 grid gap-2 text-body-sm text-stone-700">
            <li v-for="point in service.points" :key="point" class="flex gap-2">
                <span class="mt-1 h-2 w-2 shrink-0 rounded-sm bg-brand-primary" />
                <span>{{ point }}</span>
            </li>
        </ul>
    </BaseCard>
</template>
