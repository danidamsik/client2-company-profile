<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
    flash: {
        type: Object,
        default: () => ({}),
    },
});

const toasts = ref([]);
const timers = new Map();

const palette = {
    success: {
        wrapper: 'border-green-200 bg-green-50 text-green-900',
        icon: 'bg-green-600 text-white',
    },
    error: {
        wrapper: 'border-red-200 bg-red-50 text-red-900',
        icon: 'bg-red-600 text-white',
    },
    info: {
        wrapper: 'border-brand-line bg-white text-brand-ink',
        icon: 'bg-brand-primary text-brand-ink',
    },
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter((toast) => toast.id !== id);

    if (timers.has(id)) {
        clearTimeout(timers.get(id));
        timers.delete(id);
    }
};

const pushToast = ({ type = 'info', title = '', message = '' }) => {
    if (!title && !message) {
        return;
    }

    const id = `${Date.now()}-${Math.random()}`;

    toasts.value = [
        ...toasts.value,
        {
            id,
            type,
            title,
            message,
        },
    ].slice(-3);

    timers.set(id, setTimeout(() => removeToast(id), 5200));
};

const handleToast = (event) => {
    pushToast(event.detail || {});
};

const pushFlash = () => {
    if (props.flash?.success) {
        pushToast({
            type: 'success',
            title: 'Berhasil',
            message: props.flash.success,
        });
    }

    if (props.flash?.error) {
        pushToast({
            type: 'error',
            title: 'Gagal',
            message: props.flash.error,
        });
    }
};

const toastClasses = (toast) => palette[toast.type]?.wrapper || palette.info.wrapper;
const iconClasses = (toast) => palette[toast.type]?.icon || palette.info.icon;

onMounted(() => {
    window.addEventListener('admin:toast', handleToast);
    pushFlash();
});

onBeforeUnmount(() => {
    window.removeEventListener('admin:toast', handleToast);
    timers.forEach((timer) => clearTimeout(timer));
    timers.clear();
});

watch(
    () => [props.flash?.success, props.flash?.error],
    ([success, error], [previousSuccess, previousError]) => {
        if (success && success !== previousSuccess) {
            pushToast({ type: 'success', title: 'Berhasil', message: success });
        }

        if (error && error !== previousError) {
            pushToast({ type: 'error', title: 'Gagal', message: error });
        }
    },
);
</script>

<template>
    <div
        class="pointer-events-none fixed right-4 top-4 z-[70] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 sm:right-6 sm:top-6"
        aria-live="polite"
        aria-atomic="true"
    >
        <TransitionGroup
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto rounded-lg border p-4 shadow-lift"
                :class="toastClasses(toast)"
            >
                <div class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md" :class="iconClasses(toast)">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path
                                v-if="toast.type === 'error'"
                                d="m6 6 8 8M14 6l-8 8"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            />
                            <path
                                v-else
                                d="m5 10.5 3 3L15.5 6"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>

                    <div class="min-w-0 flex-1">
                        <p v-if="toast.title" class="text-body-sm font-bold">{{ toast.title }}</p>
                        <p v-if="toast.message" class="mt-1 text-body-sm opacity-80">{{ toast.message }}</p>
                    </div>

                    <button
                        type="button"
                        class="focus-ring flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-current opacity-70 transition hover:opacity-100"
                        aria-label="Tutup notifikasi"
                        @click="removeToast(toast.id)"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="m6 6 8 8M14 6l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>
