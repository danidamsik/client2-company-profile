<script setup>
import ServiceIcon from '@/Components/Public/ServiceIcon';

defineProps({
    service: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="flex h-full flex-col rounded-lg border border-brand-line bg-white p-6 shadow-card transition duration-300 hover:-translate-y-1 hover:border-brand-primary hover:shadow-lift">
        <div class="flex items-start justify-between gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-primary text-brand-ink">
                <ServiceIcon :name="service.icon" class="h-7 w-7" />
            </div>

            <span class="rounded-md bg-brand-soft px-3 py-1 text-body-sm font-semibold text-brand-accent">
                {{ service.category }}
            </span>
        </div>

        <h3 class="mt-6 text-xl font-bold text-brand-ink">{{ service.title }}</h3>
        <p class="mt-3 text-body-md text-stone-600">{{ service.description }}</p>

        <ul class="mt-5 grid gap-2 text-body-sm text-stone-700">
            <li v-for="point in service.points" :key="point" class="flex gap-2">
                <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-brand-primary" />
                <span>{{ point }}</span>
            </li>
        </ul>
    </article>
</template>
