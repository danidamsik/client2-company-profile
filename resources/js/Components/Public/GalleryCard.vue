<script setup>
import BaseCard from '@/Components/Public/BaseCard.vue';

defineProps({
    item: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <BaseCard hover :padded="false" class="group h-full overflow-hidden">
        <figure class="flex h-full flex-col">
            <div class="overflow-hidden bg-brand-muted">
                <img
                    :src="item.image"
                    :alt="item.alt"
                    class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                    loading="lazy"
                    decoding="async"
                />
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
