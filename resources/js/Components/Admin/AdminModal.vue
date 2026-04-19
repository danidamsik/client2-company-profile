<script setup>
import Modal from '@/Components/Modal.vue';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    submitLabel: {
        type: String,
        default: 'Simpan',
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close', 'submit']);
</script>

<template>
    <Modal :show="show" max-width="2xl" @close="$emit('close')">
        <form @submit.prevent="$emit('submit')">
            <div class="border-b border-brand-line px-6 py-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-brand-ink">{{ title }}</h2>
                        <p v-if="description" class="mt-1 text-body-sm text-stone-600">{{ description }}</p>
                    </div>

                    <button
                        type="button"
                        class="focus-ring flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-stone-500 transition hover:bg-brand-soft hover:text-brand-ink"
                        aria-label="Tutup modal"
                        @click="$emit('close')"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="m6 6 8 8M14 6l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5">
                <slot />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-brand-line bg-brand-muted px-6 py-4 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line bg-white px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                    @click="$emit('close')"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    :disabled="processing"
                    class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primaryDark hover:bg-brand-primaryDark"
                    :class="processing ? 'cursor-not-allowed opacity-70' : ''"
                >
                    {{ submitLabel }}
                </button>
            </div>
        </form>
    </Modal>
</template>
