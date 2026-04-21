<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminModal from '@/Components/Admin/AdminModal.vue';
import BaseButton from '@/Components/Public/BaseButton.vue';
import SectionHeading from '@/Components/Public/SectionHeading.vue';
import ServiceCard from '@/Components/Public/ServiceCard.vue';
import ServiceIcon from '@/Components/Public/ServiceIcon';
import { iconLabel, recommendServiceIcon, serviceIconOptions } from '@/Support/serviceIcons';

const props = defineProps({
    serviceSection: {
        type: Object,
        required: true,
    },
    services: {
        type: Object,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const defaultPoints = 'Kontrol akses area\nPatroli shift kerja\nLaporan aktivitas rutin';

const showModal = ref(false);
const modalMode = ref('create');
const selectedService = ref(null);
const iconTouched = ref(false);
const iconSearch = ref('');

const sectionForm = useForm({
    eyebrow: props.serviceSection.eyebrow || '',
    title: props.serviceSection.title || '',
    subtitle: props.serviceSection.subtitle || '',
    cta_label: props.serviceSection.cta_label || '',
    cta_url: props.serviceSection.cta_url || '',
});

const form = useForm({
    title: '',
    description: '',
    category: 'Korporat',
    icon: 'shield',
    points: defaultPoints,
});

const modalTitle = computed(() => (
    modalMode.value === 'create' ? 'Tambah Layanan' : 'Edit Layanan'
));

const modalDescription = computed(() => (
    modalMode.value === 'create'
        ? 'Tambahkan seluruh konten card layanan yang tampil di halaman publik.'
        : 'Perbarui konten card layanan, termasuk badge, ikon, dan bullet points.'
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

const splitLines = (value) => value
    .split(/\r\n|\r|\n/)
    .map((item) => item.trim())
    .filter(Boolean);

const sectionPreview = computed(() => ({
    eyebrow: sectionForm.eyebrow || 'Layanan',
    title: sectionForm.title || 'Pengamanan untuk kebutuhan operasional',
    subtitle: sectionForm.subtitle || 'Pilih model pengamanan yang sesuai dengan karakter lokasi, jumlah personel, jam operasional, dan tingkat risiko bisnis.',
    cta_label: sectionForm.cta_label || 'Diskusi Kebutuhan',
    cta_url: sectionForm.cta_url || '#',
}));

const servicePreview = computed(() => ({
    id: 'preview',
    title: form.title || 'Security Perusahaan',
    description: form.description || 'Ringkasan manfaat layanan untuk calon klien.',
    category: form.category || 'Korporat',
    icon: form.icon || 'shield',
    points: splitLines(form.points || '').length ? splitLines(form.points || '') : splitLines(defaultPoints),
}));

const recommendedIcon = computed(() => recommendServiceIcon(form.title, form.category));

const filteredIconOptions = computed(() => {
    const keyword = iconSearch.value.trim().toLowerCase();

    if (!keyword) {
        return serviceIconOptions;
    }

    return serviceIconOptions.filter((option) => (
        option.value.includes(keyword)
        || option.label.toLowerCase().includes(keyword)
        || option.hint.toLowerCase().includes(keyword)
    ));
});

const selectIcon = (value) => {
    iconTouched.value = true;
    form.icon = value;
};

const useRecommendedIcon = () => {
    iconTouched.value = false;
    form.icon = recommendedIcon.value;
};

watch(
    () => [form.title, form.category],
    () => {
        if (!iconTouched.value) {
            form.icon = recommendedIcon.value;
        }
    },
);

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const confirmAction = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:confirm', { detail }));
};

const resetSectionToSaved = () => {
    sectionForm.defaults({
        eyebrow: props.serviceSection.eyebrow || '',
        title: props.serviceSection.title || '',
        subtitle: props.serviceSection.subtitle || '',
        cta_label: props.serviceSection.cta_label || '',
        cta_url: props.serviceSection.cta_url || '',
    });
    sectionForm.reset();
    sectionForm.clearErrors();
};

const submitSection = () => {
    sectionForm.put(route('admin.services.section.update'), {
        preserveScroll: true,
        onSuccess: () => {
            sectionForm.defaults({
                eyebrow: sectionForm.eyebrow,
                title: sectionForm.title,
                subtitle: sectionForm.subtitle,
                cta_label: sectionForm.cta_label,
                cta_url: sectionForm.cta_url,
            });
        },
        onError: () => {
            toast({
                type: 'error',
                title: 'Periksa form',
                message: 'Ada konten section layanan yang belum valid.',
            });
        },
    });
};

const openCreateModal = () => {
    modalMode.value = 'create';
    selectedService.value = null;
    form.defaults({
        title: '',
        description: '',
        category: 'Korporat',
        icon: 'shield',
        points: defaultPoints,
    });
    form.reset();
    iconTouched.value = false;
    form.icon = recommendedIcon.value;
    iconSearch.value = '';
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (service) => {
    modalMode.value = 'update';
    selectedService.value = service;
    form.defaults({
        title: service.title,
        description: service.description,
        category: service.category,
        icon: service.icon,
        points: service.points_text || defaultPoints,
    });
    form.reset();
    iconTouched.value = true;
    iconSearch.value = '';
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
            <section class="rounded-lg border border-brand-line bg-white p-5 shadow-sm">
                <div class="border-b border-brand-line pb-5">
                    <p class="text-body-sm font-semibold uppercase text-brand-accent">Section Layanan</p>
                    <h2 class="mt-1 text-xl font-bold text-brand-ink">Konten heading dan tombol</h2>
                </div>

                <form class="mt-5 grid gap-5" @submit.prevent="submitSection">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Eyebrow</span>
                            <input v-model="sectionForm.eyebrow" type="text" maxlength="80" placeholder="Layanan" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                            <p v-if="sectionForm.errors.eyebrow" class="mt-2 text-body-sm font-medium text-red-700">{{ sectionForm.errors.eyebrow }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Label tombol</span>
                            <input v-model="sectionForm.cta_label" type="text" maxlength="60" placeholder="Diskusi Kebutuhan" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                            <p v-if="sectionForm.errors.cta_label" class="mt-2 text-body-sm font-medium text-red-700">{{ sectionForm.errors.cta_label }}</p>
                        </label>
                    </div>

                    <label class="block">
                        <span class="text-body-sm font-semibold text-brand-ink">Judul section</span>
                        <input v-model="sectionForm.title" type="text" required maxlength="180" placeholder="Pengamanan untuk kebutuhan operasional" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                        <p v-if="sectionForm.errors.title" class="mt-2 text-body-sm font-medium text-red-700">{{ sectionForm.errors.title }}</p>
                    </label>

                    <label class="block">
                        <span class="text-body-sm font-semibold text-brand-ink">Deskripsi section</span>
                        <textarea v-model="sectionForm.subtitle" required maxlength="900" rows="4" placeholder="Pilih model pengamanan..." class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                        <p v-if="sectionForm.errors.subtitle" class="mt-2 text-body-sm font-medium text-red-700">{{ sectionForm.errors.subtitle }}</p>
                    </label>

                    <label class="block">
                        <span class="text-body-sm font-semibold text-brand-ink">URL tombol</span>
                        <input v-model="sectionForm.cta_url" type="text" maxlength="255" placeholder="Kosongkan untuk memakai WhatsApp company" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                        <p v-if="sectionForm.errors.cta_url" class="mt-2 text-body-sm font-medium text-red-700">{{ sectionForm.errors.cta_url }}</p>
                    </label>

                    <div class="rounded-lg border border-dashed border-brand-primary bg-brand-soft p-5">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <SectionHeading
                                :eyebrow="sectionPreview.eyebrow"
                                :title="sectionPreview.title"
                                :subtitle="sectionPreview.subtitle"
                            />

                            <BaseButton :href="sectionPreview.cta_url" variant="outline" class="pointer-events-none w-full sm:w-fit">
                                {{ sectionPreview.cta_label }}
                            </BaseButton>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-brand-line pt-5 sm:flex-row sm:justify-end">
                        <button type="button" class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line bg-white px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft" @click="resetSectionToSaved">
                            Reset
                        </button>
                        <button type="submit" class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primaryDark hover:bg-brand-primaryDark disabled:cursor-not-allowed disabled:opacity-60" :disabled="sectionForm.processing">
                            {{ sectionForm.processing ? 'Menyimpan...' : 'Simpan Section' }}
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-lg border border-brand-line bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-brand-line p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">List Layanan</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Data card layanan perusahaan</h2>
                        <p class="mt-1 max-w-3xl text-body-sm text-stone-600">
                            Setiap card bisa mengatur judul, deskripsi, kategori, ikon, dan bullet points.
                        </p>
                        <p class="mt-3 text-body-sm font-semibold text-stone-600">
                            Total layanan: <span class="text-brand-ink">{{ summary.total }}</span>
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
                                    {{ service.category }}
                                </span>
                                <span class="inline-flex w-fit items-center gap-1.5 rounded-md bg-stone-100 px-2.5 py-1 text-xs font-bold uppercase text-stone-600">
                                    <ServiceIcon :name="service.icon" class="h-4 w-4" />
                                    {{ iconLabel(service.icon) }}
                                </span>
                            </div>
                            <p class="mt-2 text-body-sm text-stone-600">{{ service.excerpt }}</p>
                            <p class="mt-2 text-xs font-semibold uppercase text-stone-500">Update {{ service.updated_at || '-' }}</p>
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
            max-width="4xl"
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
                        rows="4"
                        placeholder="Ringkasan manfaat layanan untuk calon klien."
                        class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p v-if="form.errors.description" class="mt-2 text-body-sm font-medium text-red-700">
                        {{ form.errors.description }}
                    </p>
                </label>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-body-sm font-semibold text-brand-ink">Kategori badge</span>
                        <input v-model="form.category" type="text" required maxlength="60" placeholder="Korporat" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                        <p v-if="form.errors.category" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.category }}</p>
                    </label>

                    <div class="block md:col-span-2">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-body-sm font-semibold text-brand-ink">Ikon</span>
                            <button
                                type="button"
                                class="focus-ring inline-flex h-9 items-center justify-center rounded-lg border border-brand-line bg-white px-3 text-xs font-bold uppercase text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                                @click="useRecommendedIcon"
                            >
                                Rekomendasi: {{ iconLabel(recommendedIcon) }}
                            </button>
                        </div>

                        <input
                            v-model="iconSearch"
                            type="search"
                            placeholder="Cari ikon: cctv, patroli, gedung, event..."
                            class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                        />

                        <div class="mt-3 grid max-h-80 gap-2 overflow-y-auto rounded-lg border border-brand-line bg-white p-3 sm:grid-cols-2">
                            <button
                                v-for="option in filteredIconOptions"
                                :key="option.value"
                                type="button"
                                class="focus-ring flex min-h-16 items-center gap-3 rounded-lg border p-3 text-left transition"
                                :class="form.icon === option.value ? 'border-brand-primary bg-brand-soft ring-2 ring-brand-primary/40' : 'border-brand-line bg-white hover:border-brand-primary hover:bg-brand-soft'"
                                @click="selectIcon(option.value)"
                            >
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-brand-primary text-brand-ink">
                                    <ServiceIcon :name="option.value" class="h-6 w-6" />
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-body-sm font-bold text-brand-ink">{{ option.label }}</span>
                                    <span class="mt-1 block text-xs text-stone-600">{{ option.hint }}</span>
                                    <span v-if="recommendedIcon === option.value" class="mt-2 inline-flex rounded-md bg-white px-2 py-1 text-[11px] font-bold uppercase text-brand-accent">
                                        Rekomendasi
                                    </span>
                                </span>
                            </button>

                            <p v-if="!filteredIconOptions.length" class="rounded-lg border border-dashed border-brand-line p-4 text-body-sm text-stone-600 sm:col-span-2">
                                Ikon tidak ditemukan. Coba kata lain seperti cctv, patroli, gedung, atau event.
                            </p>
                        </div>

                        <p class="mt-2 text-xs font-medium uppercase text-stone-500">
                            Rekomendasi berubah otomatis dari judul dan kategori selama ikon belum dipilih manual.
                        </p>
                        <p v-if="form.errors.icon" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.icon }}</p>
                    </div>
                </div>

                <label class="block">
                    <span class="text-body-sm font-semibold text-brand-ink">Bullet points</span>
                    <textarea
                        v-model="form.points"
                        required
                        maxlength="1200"
                        rows="5"
                        placeholder="Tulis satu point per baris."
                        class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary"
                    />
                    <p class="mt-2 text-xs font-medium uppercase text-stone-500">Satu baris akan menjadi satu bullet di card layanan.</p>
                    <p v-if="form.errors.points" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.points }}</p>
                </label>

                <div class="rounded-lg border border-dashed border-brand-primary bg-brand-muted p-4">
                    <p class="mb-3 text-body-sm font-semibold uppercase text-brand-accent">Preview Card</p>
                    <div class="max-w-xl">
                        <ServiceCard :service="servicePreview" />
                    </div>
                </div>
            </div>
        </AdminModal>
    </AdminLayout>
</template>
