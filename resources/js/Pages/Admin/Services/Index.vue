<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminModal from '@/Components/Admin/AdminModal.vue';

const props = defineProps({
    services: {
        type: Object,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const showModal = ref(false);
const modalMode = ref('create');
const selectedService = ref(null);

const form = useForm({
    title: '',
    description: '',
});

const modalTitle = computed(() => (
    modalMode.value === 'create' ? 'Tambah Layanan' : 'Edit Layanan'
));

const modalDescription = computed(() => (
    modalMode.value === 'create'
        ? 'Tambahkan layanan baru yang akan dipakai pada konten publik.'
        : 'Perbarui judul dan deskripsi layanan yang sudah tersimpan.'
));

const submitLabel = computed(() => {
    if (form.processing) {
        return modalMode.value === 'create' ? 'Menyimpan...' : 'Memperbarui...';
    }

    return modalMode.value === 'create' ? 'Simpan Layanan' : 'Simpan Update';
});

const visibleLinks = computed(() => (props.services.links || []).filter((link) => link.url || link.active));

const normalizedLabel = (label) => label
    .replace('&laquo; Previous', 'Prev')
    .replace('Next &raquo;', 'Next');

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const confirmAction = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:confirm', { detail }));
};

const openCreateModal = () => {
    modalMode.value = 'create';
    selectedService.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (service) => {
    modalMode.value = 'update';
    selectedService.value = service;
    form.defaults({
        title: service.title,
        description: service.description,
    });
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada data layanan yang belum valid.',
    });
};

const submitService = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            form.reset();
        },
        onError: handleError,
    };

    if (modalMode.value === 'create') {
        form.post(route('admin.services.store'), options);
        return;
    }

    form.put(route('admin.services.update', selectedService.value.id), options);
};

const deleteService = (service) => {
    confirmAction({
        title: 'Hapus layanan?',
        message: `Layanan "${service.title}" akan dihapus permanen dari database.`,
        confirmLabel: 'Hapus Layanan',
        tone: 'danger',
        onConfirm: () => {
            router.delete(route('admin.services.destroy', service.id), {
                preserveScroll: true,
                onError: () => {
                    toast({
                        type: 'error',
                        title: 'Gagal menghapus',
                        message: 'Layanan belum bisa dihapus. Coba ulangi beberapa saat lagi.',
                    });
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Layanan" />

    <AdminLayout title="Layanan">
        <div class="space-y-6">
            <section class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-brand-line bg-white p-5 shadow-sm">
                    <p class="text-body-sm font-semibold uppercase text-brand-accent">Total Layanan</p>
                    <p class="mt-3 text-3xl font-bold text-brand-ink">{{ summary.total }}</p>
                    <p class="mt-1 text-body-sm text-stone-600">Data tersimpan di tabel services.</p>
                </div>

                <div class="rounded-lg border border-brand-line bg-white p-5 shadow-sm">
                    <p class="text-body-sm font-semibold uppercase text-brand-accent">Update Terakhir</p>
                    <p class="mt-3 text-xl font-bold text-brand-ink">{{ summary.latestUpdated || 'Belum ada' }}</p>
                    <p class="mt-1 text-body-sm text-stone-600">Dipakai untuk review konten layanan.</p>
                </div>
            </section>

            <section class="rounded-lg border border-brand-line bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-brand-line p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">List Layanan</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Data layanan perusahaan</h2>
                        <p class="mt-1 max-w-3xl text-body-sm text-stone-600">
                            Judul dan deskripsi divalidasi sebelum tersimpan ke database.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="focus-ring inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primaryDark hover:bg-brand-primaryDark"
                        @click="openCreateModal"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Tambah Layanan
                    </button>
                </div>

                <div v-if="services.data.length" class="divide-y divide-brand-line">
                    <article
                        v-for="service in services.data"
                        :key="service.id"
                        class="grid gap-4 p-5 lg:grid-cols-[1fr_auto] lg:items-center"
                    >
                        <div class="min-w-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                <h3 class="text-lg font-bold text-brand-ink">{{ service.title }}</h3>
                                <span class="w-fit rounded-md bg-brand-soft px-2.5 py-1 text-xs font-bold uppercase text-brand-accent">
                                    Update {{ service.updated_at || '-' }}
                                </span>
                            </div>
                            <p class="mt-2 text-body-sm text-stone-600">{{ service.excerpt }}</p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row lg:justify-end">
                            <button
                                type="button"
                                class="focus-ring inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-brand-line bg-white px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                                @click="openEditModal(service)"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M5 14.5h2.5L15 7l-2.5-2.5L5 12v2.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                    <path d="M11.5 5.5 14 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                                Edit
                            </button>

                            <button
                                type="button"
                                class="focus-ring inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 text-body-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                @click="deleteService(service)"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M6 7h8M8 7V5h4v2M8 9v5M12 9v5M6.5 7l.5 9h6l.5-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </article>
                </div>

                <div v-else class="p-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-brand-soft text-brand-accent">
                        <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 3.5 15.5 6v4.5c0 3.55-2.2 5.8-5.5 7-3.3-1.2-5.5-3.45-5.5-7V6L10 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-brand-ink">Belum ada layanan</h3>
                    <p class="mx-auto mt-2 max-w-md text-body-sm text-stone-600">
                        Tambahkan layanan pertama agar data siap digunakan pada halaman publik dinamis.
                    </p>
                </div>

                <div
                    v-if="visibleLinks.length > 1"
                    class="flex flex-wrap items-center justify-between gap-3 border-t border-brand-line p-5"
                >
                    <p class="text-body-sm text-stone-600">
                        Menampilkan {{ services.from || 0 }}-{{ services.to || 0 }} dari {{ services.total }} layanan
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="link in visibleLinks"
                            :key="`${link.label}-${link.url}`"
                            :href="link.url || undefined"
                            preserve-scroll
                            class="focus-ring inline-flex h-10 min-w-10 items-center justify-center rounded-lg border px-3 text-body-sm font-semibold transition"
                            :class="[
                                link.active
                                    ? 'border-brand-primary bg-brand-primary text-brand-ink'
                                    : 'border-brand-line bg-white text-brand-ink hover:border-brand-primary hover:bg-brand-soft',
                                !link.url && !link.active ? 'pointer-events-none opacity-45' : '',
                            ]"
                        >
                            {{ normalizedLabel(link.label) }}
                        </component>
                    </div>
                </div>
            </section>
        </div>

        <AdminModal
            :show="showModal"
            :title="modalTitle"
            :description="modalDescription"
            :submit-label="submitLabel"
            :processing="form.processing"
            @close="closeModal"
            @submit="submitService"
        >
            <div class="grid gap-5">
                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Judul layanan</span>
                    <input
                        v-model="form.title"
                        type="text"
                        required
                        maxlength="120"
                        placeholder="Security Perusahaan"
                        class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p v-if="form.errors.title" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.title }}</p>
                </label>

                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Deskripsi</span>
                    <textarea
                        v-model="form.description"
                        required
                        maxlength="2000"
                        rows="6"
                        placeholder="Ringkasan manfaat layanan untuk calon klien."
                        class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p v-if="form.errors.description" class="mt-2 text-body-sm font-medium text-red-700">
                        {{ form.errors.description }}
                    </p>
                </label>
            </div>
        </AdminModal>
    </AdminLayout>
</template>
