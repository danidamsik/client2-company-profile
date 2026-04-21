<script setup>
import { computed, onBeforeUnmount, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminModal from '@/Components/Admin/AdminModal.vue';

const props = defineProps({
    certifications: {
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
const selectedCertification = ref(null);
const imagePreviewUrl = ref('');
const objectPreviewUrl = ref('');
const imageInput = ref(null);

const form = useForm({
    title: '',
    description: '',
    image: null,
});

const modalTitle = computed(() => (
    modalMode.value === 'create' ? 'Tambah Sertifikasi' : 'Edit Sertifikasi'
));

const modalDescription = computed(() => (
    modalMode.value === 'create'
        ? 'Unggah gambar JPG/PNG untuk sertifikasi baru.'
        : 'Perbarui data sertifikasi. Gambar dapat dikosongkan jika tidak ingin diganti.'
));

const submitLabel = computed(() => {
    if (form.processing) {
        return modalMode.value === 'create' ? 'Menyimpan...' : 'Memperbarui...';
    }

    return modalMode.value === 'create' ? 'Simpan Sertifikasi' : 'Simpan Update';
});

const visibleLinks = computed(() => (props.certifications.links || []).filter((link) => link.url || link.active));

const normalizedLabel = (label) => label
    .replace('&laquo; Previous', 'Prev')
    .replace('Next &raquo;', 'Next');

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const confirmAction = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:confirm', { detail }));
};

const revokeObjectPreview = () => {
    if (objectPreviewUrl.value) {
        URL.revokeObjectURL(objectPreviewUrl.value);
        objectPreviewUrl.value = '';
    }
};

const resetFileInput = () => {
    if (imageInput.value) {
        imageInput.value.value = '';
    }
};

const openCreateModal = () => {
    modalMode.value = 'create';
    selectedCertification.value = null;
    form.defaults({
        title: '',
        description: '',
        image: null,
    });
    form.reset();
    form.clearErrors();
    revokeObjectPreview();
    imagePreviewUrl.value = '';
    resetFileInput();
    showModal.value = true;
};

const openEditModal = (certification) => {
    modalMode.value = 'update';
    selectedCertification.value = certification;
    form.defaults({
        title: certification.title,
        description: certification.description,
        image: null,
    });
    form.reset();
    form.clearErrors();
    revokeObjectPreview();
    imagePreviewUrl.value = certification.image_url;
    resetFileInput();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleImageChange = (event) => {
    const [file] = event.target.files;

    form.image = file || null;
    revokeObjectPreview();

    if (!file) {
        imagePreviewUrl.value = selectedCertification.value?.image_url || '';
        return;
    }

    objectPreviewUrl.value = URL.createObjectURL(file);
    imagePreviewUrl.value = objectPreviewUrl.value;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada data sertifikasi yang belum valid.',
    });
};

const clearFormAfterSuccess = () => {
    closeModal();
    form.reset();
    revokeObjectPreview();
    imagePreviewUrl.value = '';
    resetFileInput();
};

const submitCertification = () => {
    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: clearFormAfterSuccess,
        onError: handleError,
        onFinish: () => form.transform((data) => data),
    };

    if (modalMode.value === 'create') {
        form.transform((data) => data).post(route('admin.certifications.store'), options);
        return;
    }

    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('admin.certifications.update', selectedCertification.value.id), options);
};

const deleteCertification = (certification) => {
    confirmAction({
        title: 'Hapus sertifikasi?',
        message: `Sertifikasi "${certification.title}" akan dihapus permanen. File upload terkait juga akan dibersihkan bila tersedia.`,
        confirmLabel: 'Hapus Sertifikasi',
        tone: 'danger',
        onConfirm: () => {
            router.delete(route('admin.certifications.destroy', certification.id), {
                preserveScroll: true,
                onError: () => {
                    toast({
                        type: 'error',
                        title: 'Gagal menghapus',
                        message: 'Sertifikasi belum bisa dihapus. Coba ulangi beberapa saat lagi.',
                    });
                },
            });
        },
    });
};

onBeforeUnmount(() => {
    revokeObjectPreview();
});
</script>

<template>
    <Head title="Sertifikasi" />

    <AdminLayout title="Sertifikasi">
        <div class="space-y-6">
            <section class="rounded-lg border border-brand-line bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-brand-line p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">List Sertifikasi</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Data sertifikasi perusahaan</h2>
                        <p class="mt-1 max-w-3xl text-body-sm text-stone-600">
                            Gambar sertifikasi wajib JPG/PNG dan akan dipreview sebelum disimpan.
                        </p>
                        <p class="mt-3 text-body-sm font-semibold text-stone-600">
                            Total sertifikasi: <span class="text-brand-ink">{{ summary.total }}</span>
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
                        Tambah Sertifikasi
                    </button>
                </div>

                <div v-if="certifications.data.length" class="divide-y divide-brand-line">
                    <article
                        v-for="certification in certifications.data"
                        :key="certification.id"
                        class="grid gap-4 p-5 lg:grid-cols-[auto_1fr_auto] lg:items-center"
                    >
                        <img
                            :src="certification.image_url"
                            :alt="certification.title"
                            class="aspect-[4/3] w-full rounded-lg border border-brand-line object-cover sm:w-36"
                            loading="lazy"
                        />

                        <div class="min-w-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                <h3 class="text-lg font-bold text-brand-ink">{{ certification.title }}</h3>
                                <span class="w-fit rounded-md bg-brand-soft px-2.5 py-1 text-xs font-bold uppercase text-brand-accent">
                                    Update {{ certification.updated_at || '-' }}
                                </span>
                            </div>
                            <p class="mt-2 text-body-sm text-stone-600">{{ certification.excerpt }}</p>
                            <p class="mt-2 truncate text-xs font-semibold uppercase text-stone-500">{{ certification.image }}</p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row lg:justify-end">
                            <button
                                type="button"
                                class="focus-ring inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-brand-line bg-white px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                                @click="openEditModal(certification)"
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
                                @click="deleteCertification(certification)"
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
                            <path d="M5 3.5h10v13H5v-13Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M7.5 7h5M7.5 10h5M8 13h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-brand-ink">Belum ada sertifikasi</h3>
                    <p class="mx-auto mt-2 max-w-md text-body-sm text-stone-600">
                        Tambahkan sertifikasi pertama agar bukti legalitas siap tampil pada halaman publik.
                    </p>
                </div>

                <div
                    v-if="visibleLinks.length > 1"
                    class="flex flex-wrap items-center justify-between gap-3 border-t border-brand-line p-5"
                >
                    <p class="text-body-sm text-stone-600">
                        Menampilkan {{ certifications.from || 0 }}-{{ certifications.to || 0 }} dari {{ certifications.total }} sertifikasi
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
            @submit="submitCertification"
        >
            <div class="grid gap-5">
                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Nama sertifikasi</span>
                    <input
                        v-model="form.title"
                        type="text"
                        required
                        maxlength="150"
                        placeholder="Sertifikat BUJP"
                        class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p v-if="form.errors.title" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.title }}</p>
                </label>

                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Gambar sertifikasi</span>
                    <input
                        ref="imageInput"
                        type="file"
                        accept="image/jpeg,image/png"
                        :required="modalMode === 'create'"
                        class="focus-ring mt-2 block w-full rounded-lg border border-brand-line bg-white text-body-sm text-brand-ink file:mr-4 file:border-0 file:bg-brand-primary file:px-4 file:py-3 file:text-body-sm file:font-semibold file:text-brand-ink hover:file:bg-brand-primaryDark"
                        @change="handleImageChange"
                    />
                    <p class="mt-2 text-xs font-medium uppercase text-stone-500">Format JPG/PNG, maksimal 2MB.</p>
                    <p v-if="form.errors.image" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.image }}</p>
                </label>

                <div v-if="imagePreviewUrl" class="rounded-lg border border-brand-line bg-brand-muted p-3">
                    <p class="mb-2 text-body-sm font-semibold text-brand-ink">Preview</p>
                    <img
                        :src="imagePreviewUrl"
                        alt="Preview gambar sertifikasi"
                        class="aspect-[4/3] w-full rounded-lg object-cover"
                    />
                </div>

                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Deskripsi</span>
                    <textarea
                        v-model="form.description"
                        required
                        maxlength="2000"
                        rows="6"
                        placeholder="Jelaskan fungsi dokumen atau sertifikasi ini."
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
