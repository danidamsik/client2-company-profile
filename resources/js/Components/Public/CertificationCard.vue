<script setup>
import BaseCard from '@/Components/Public/BaseCard.vue';

defineProps({
    certification: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <BaseCard hover :padded="false" class="flex h-full flex-col overflow-hidden">
        <div class="bg-brand-muted p-3">
            <img
                :src="certification.image"
                :alt="certification.alt"
                class="aspect-[4/3] w-full rounded-lg border border-brand-line bg-white object-cover"
                loading="lazy"
                decoding="async"
            />
        </div>

        <div class="flex flex-1 flex-col p-6">
            <p class="text-body-sm font-bold uppercase text-brand-accent">{{ certification.issuer }}</p>
            <h3 class="mt-3 text-xl font-bold text-brand-ink">{{ certification.title }}</h3>
            <p class="mt-3 text-body-md text-stone-600">{{ certification.description }}</p>

            <div class="mt-5 flex flex-wrap gap-2">
                <span
                    v-for="tag in certification.tags"
                    :key="tag"
                    class="rounded-md border border-brand-line bg-white px-3 py-1 text-body-sm font-semibold text-brand-charcoal"
                >
                    {{ tag }}
                </span>
            </div>
        </div>
    </BaseCard>
</template>
