<script setup>
import { computed, onBeforeUnmount, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminModal from '@/Components/Admin/AdminModal.vue';

const props = defineProps({
    galleries: {
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
const selectedGallery = ref(null);
const imagePreviewUrl = ref('');
const objectPreviewUrl = ref('');
const imageInput = ref(null);

const form = useForm({
    caption: '',
    image: null,
});

const modalTitle = computed(() => (
    modalMode.value === 'create' ? 'Tambah Galeri' : 'Edit Galeri'
));

const modalDescription = computed(() => (
    modalMode.value === 'create'
        ? 'Unggah dokumentasi kegiatan dalam format JPG/PNG.'
        : 'Perbarui caption atau ganti gambar dokumentasi bila diperlukan.'
));

const submitLabel = computed(() => {
    if (form.processing) {
        return modalMode.value === 'create' ? 'Menyimpan...' : 'Memperbarui...';
    }

    return modalMode.value === 'create' ? 'Simpan Galeri' : 'Simpan Update';
});

const visibleLinks = computed(() => (props.galleries.links || []).filter((link) => link.url || link.active));

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
    selectedGallery.value = null;
    form.defaults({
        caption: '',
        image: null,
    });
    form.reset();
    form.clearErrors();
    revokeObjectPreview();
    imagePreviewUrl.value = '';
    resetFileInput();
    showModal.value = true;
};

const openEditModal = (gallery) => {
    modalMode.value = 'update';
    selectedGallery.value = gallery;
    form.defaults({
        caption: gallery.caption,
        image: null,
    });
    form.reset();
    form.clearErrors();
    revokeObjectPreview();
    imagePreviewUrl.value = gallery.image_url;
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
        imagePreviewUrl.value = selectedGallery.value?.image_url || '';
        return;
    }

    objectPreviewUrl.value = URL.createObjectURL(file);
    imagePreviewUrl.value = objectPreviewUrl.value;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada data galeri yang belum valid.',
    });
};

const clearFormAfterSuccess = () => {
    closeModal();
    form.reset();
    revokeObjectPreview();
    imagePreviewUrl.value = '';
    resetFileInput();
};

const submitGallery = () => {
    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: clearFormAfterSuccess,
        onError: handleError,
        onFinish: () => form.transform((data) => data),
    };

    if (modalMode.value === 'create') {
        form.transform((data) => data).post(route('admin.galleries.store'), options);
        return;
    }

    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('admin.galleries.update', selectedGallery.value.id), options);
};

const deleteGallery = (gallery) => {
    confirmAction({
        title: 'Hapus galeri?',
        message: `Dokumentasi "${gallery.caption}" akan dihapus permanen. File upload terkait juga akan dibersihkan bila tersedia.`,
        confirmLabel: 'Hapus Galeri',
        tone: 'danger',
        onConfirm: () => {
            router.delete(route('admin.galleries.destroy', gallery.id), {
                preserveScroll: true,
                onError: () => {
                    toast({
                        type: 'error',
                        title: 'Gagal menghapus',
                        message: 'Galeri belum bisa dihapus. Coba ulangi beberapa saat lagi.',
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
    <Head title="Galeri" />

    <AdminLayout title="Galeri">
        <div class="space-y-6">
            <section class="rounded-lg border border-brand-line bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-brand-line p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">List Galeri</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Dokumentasi kegiatan</h2>
                        <p class="mt-1 max-w-3xl text-body-sm text-stone-600">
                            Caption tersimpan bersama gambar dan dipakai sebagai teks pendamping di website publik.
                        </p>
                        <p class="mt-3 text-body-sm font-semibold text-stone-600">
                            Total galeri: <span class="text-brand-ink">{{ summary.total }}</span>
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
                        Tambah Galeri
                    </button>
                </div>

                <div v-if="galleries.data.length" class="grid gap-0 divide-y divide-brand-line lg:grid-cols-2 lg:divide-x lg:divide-y-0">
                    <article
                        v-for="gallery in galleries.data"
                        :key="gallery.id"
                        class="grid gap-4 p-5 sm:grid-cols-[10rem_1fr]"
                    >
                        <img
                            :src="gallery.image_url"
                            :alt="gallery.caption"
                            class="aspect-[4/3] w-full rounded-lg border border-brand-line object-cover"
                            loading="lazy"
                        />

                        <div class="flex min-w-0 flex-col justify-between gap-4">
                            <div>
                                <span class="w-fit rounded-md bg-brand-soft px-2.5 py-1 text-xs font-bold uppercase text-brand-accent">
                                    Update {{ gallery.updated_at || '-' }}
                                </span>
                                <h3 class="mt-3 text-lg font-bold text-brand-ink">{{ gallery.caption }}</h3>
                                <p class="mt-2 truncate text-xs font-semibold uppercase text-stone-500">{{ gallery.image }}</p>
                            </div>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <button
                                    type="button"
                                    class="focus-ring inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-brand-line bg-white px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                                    @click="openEditModal(gallery)"
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
                                    @click="deleteGallery(gallery)"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path d="M6 7h8M8 7V5h4v2M8 9v5M12 9v5M6.5 7l.5 9h6l.5-9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <div v-else class="p-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-brand-soft text-brand-accent">
                        <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 5h12v10H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="m5.5 13 3.25-3.25 2.1 2.1 1.4-1.4L15 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-brand-ink">Belum ada dokumentasi</h3>
                    <p class="mx-auto mt-2 max-w-md text-body-sm text-stone-600">
                        Tambahkan galeri pertama agar dokumentasi kegiatan siap tampil pada halaman publik.
                    </p>
                </div>

                <div
                    v-if="visibleLinks.length > 1"
                    class="flex flex-wrap items-center justify-between gap-3 border-t border-brand-line p-5"
                >
                    <p class="text-body-sm text-stone-600">
                        Menampilkan {{ galleries.from || 0 }}-{{ galleries.to || 0 }} dari {{ galleries.total }} galeri
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
            @submit="submitGallery"
        >
            <div class="grid gap-5">
                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Gambar galeri</span>
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
                        alt="Preview gambar galeri"
                        class="aspect-[4/3] w-full rounded-lg object-cover"
                    />
                </div>

                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Caption</span>
                    <textarea
                        v-model="form.caption"
                        required
                        maxlength="255"
                        rows="5"
                        placeholder="Briefing personel sebelum shift dimulai."
                        class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p v-if="form.errors.caption" class="mt-2 text-body-sm font-medium text-red-700">
                        {{ form.errors.caption }}
                    </p>
                </label>
            </div>
        </AdminModal>
    </AdminLayout>
</template>
