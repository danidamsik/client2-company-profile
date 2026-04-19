<script setup>
import { ref } from 'vue';
import BaseCard from '@/Components/Public/BaseCard.vue';

defineProps({
    certification: {
        type: Object,
        required: true,
    },
});

const imageFailed = ref(false);
</script>

<template>
    <BaseCard hover :padded="false" class="flex h-full flex-col overflow-hidden">
        <div class="bg-brand-muted p-3">
            <img
                v-if="certification.image && !imageFailed"
                :src="certification.image"
                :alt="certification.alt"
                class="aspect-[4/3] w-full rounded-lg border border-brand-line bg-white object-cover"
                loading="lazy"
                decoding="async"
                @error="imageFailed = true"
            />

            <div v-else class="image-fallback">
                <div class="w-4/5 rounded-lg border border-brand-line bg-white p-5 shadow-card">
                    <div class="h-3 w-28 rounded-sm bg-brand-primary" />
                    <div class="mt-4 h-2 w-full rounded-sm bg-stone-200" />
                    <div class="mt-2 h-2 w-4/5 rounded-sm bg-stone-200" />
                    <div class="mt-6 flex items-end justify-between gap-4">
                        <span class="h-12 w-12 rounded-lg border-4 border-brand-primary bg-brand-soft" />
                        <span class="h-2 w-24 rounded-sm bg-brand-accent/40" />
                    </div>
                </div>
            </div>
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
