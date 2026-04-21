<script setup>
import { ref } from 'vue';
import BaseCard from '@/Components/Public/BaseCard.vue';

defineProps({
    item: {
        type: Object,
        required: true,
    },
    printMode: {
        type: Boolean,
        default: false,
    },
});

const imageFailed = ref(false);
</script>

<template>
    <BaseCard hover :padded="false" class="group h-full overflow-hidden">
        <figure class="flex h-full flex-col">
            <div class="overflow-hidden bg-brand-muted">
                <img
                    v-if="item.image && !imageFailed"
                :src="item.image"
                :alt="item.alt"
                class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                :loading="printMode ? 'eager' : 'lazy'"
                decoding="async"
                @error="imageFailed = true"
            />

                <div v-else class="image-fallback rounded-none">
                    <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-white/80 text-brand-accent shadow-card">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />
                            <path
                                d="m5 16 4.2-4.2a1.2 1.2 0 0 1 1.7 0L13 14l1.8-1.8a1.2 1.2 0 0 1 1.7 0L20 15.7"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M15.5 8.2h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            <figcaption class="flex flex-1 flex-col p-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <span class="rounded-md bg-brand-soft px-3 py-1 text-body-sm font-semibold text-brand-accent">
                        {{ item.category }}
                    </span>
                    <span class="text-body-sm text-stone-500">{{ item.year }}</span>
                </div>

                <h3 class="text-lg font-bold text-brand-ink">{{ item.title }}</h3>
                <p class="mt-2 text-body-sm text-stone-600">{{ item.caption }}</p>
            </figcaption>
        </figure>
    </BaseCard>
</template>
