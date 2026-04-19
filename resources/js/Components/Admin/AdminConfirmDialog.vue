<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const show = ref(false);

const confirmation = reactive({
    title: 'Konfirmasi tindakan',
    message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    confirmLabel: 'Ya, lanjutkan',
    cancelLabel: 'Batal',
    tone: 'danger',
    onConfirm: null,
});

const confirmClasses = computed(() => ({
    danger: 'border-red-600 bg-red-600 text-white hover:border-red-700 hover:bg-red-700',
    primary: 'border-brand-primary bg-brand-primary text-brand-ink hover:border-brand-primaryDark hover:bg-brand-primaryDark',
}[confirmation.tone] || 'border-red-600 bg-red-600 text-white hover:border-red-700 hover:bg-red-700'));

const iconClasses = computed(() => ({
    danger: 'bg-red-50 text-red-700',
    primary: 'bg-brand-soft text-brand-accent',
}[confirmation.tone] || 'bg-red-50 text-red-700'));

const close = () => {
    show.value = false;
    confirmation.onConfirm = null;
};

const handleOpen = (event) => {
    const detail = event.detail || {};

    confirmation.title = detail.title || 'Konfirmasi tindakan';
    confirmation.message = detail.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
    confirmation.confirmLabel = detail.confirmLabel || 'Ya, lanjutkan';
    confirmation.cancelLabel = detail.cancelLabel || 'Batal';
    confirmation.tone = detail.tone || 'danger';
    confirmation.onConfirm = typeof detail.onConfirm === 'function' ? detail.onConfirm : null;
    show.value = true;
};

const confirm = () => {
    const action = confirmation.onConfirm;

    close();
    action?.();
};

onMounted(() => {
    window.addEventListener('admin:confirm', handleOpen);
});

onBeforeUnmount(() => {
    window.removeEventListener('admin:confirm', handleOpen);
});
</script>

<template>
    <Modal :show="show" max-width="md" @close="close">
        <div class="p-6">
            <div class="flex gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg" :class="iconClasses">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M10 6.5v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M10 14h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
                        <path
                            d="M10 2.8 17.2 16H2.8L10 2.8Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>

                <div class="min-w-0 flex-1">
                    <h2 class="text-xl font-bold text-brand-ink">{{ confirmation.title }}</h2>
                    <p class="mt-2 text-body-sm text-stone-600">{{ confirmation.message }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line bg-white px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                    @click="close"
                >
                    {{ confirmation.cancelLabel }}
                </button>

                <button
                    type="button"
                    class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border px-5 text-body-sm font-semibold transition"
                    :class="confirmClasses"
                    @click="confirm"
                >
                    {{ confirmation.confirmLabel }}
                </button>
            </div>
        </div>
    </Modal>
</template>
