<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    heroSection: {
        type: Object,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const fallbackHero = {
    eyebrow: 'Security Profesional',
    badge: 'Siap Operasional',
    title: 'PT Secure Guard Indonesia',
    subtitle: 'Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan, dan event yang membutuhkan personel sigap, terlatih, dan mudah dikoordinasikan.',
    primary_cta_label: 'Hubungi Kami',
    primary_cta_url: '6281234567890',
    primary_cta_message: 'Halo PT Secure Guard Indonesia, saya ingin konsultasi kebutuhan keamanan.',
    image: '/images/security-guard-entrance.jpg',
    note: 'Respon awal untuk kebutuhan kantor, gudang, pabrik, kawasan, dan kegiatan perusahaan.',
};

const trustItems = [
    { value: 'Legal', label: 'BUJP dan dokumen pendukung' },
    { value: 'Terlatih', label: 'Personel dengan SOP lapangan' },
    { value: 'Responsif', label: 'Konsultasi cepat via WhatsApp' },
];

const imageInput = ref(null);
const imagePreviewUrl = ref(props.heroSection.image_url || '');
const objectPreviewUrl = ref('');
const activePreview = ref('');

const form = useForm({
    eyebrow: props.heroSection.eyebrow || '',
    badge: props.heroSection.badge || '',
    title: props.heroSection.title || '',
    subtitle: props.heroSection.subtitle || '',
    primary_cta_label: props.heroSection.primary_cta_label || '',
    primary_cta_url: props.heroSection.primary_cta_url || '',
    primary_cta_message: props.heroSection.primary_cta_message || '',
    note: props.heroSection.note || '',
    image: null,
});

const submitLabel = computed(() => (form.processing ? 'Menyimpan...' : 'Simpan Update'));

const preview = computed(() => ({
    eyebrow: form.eyebrow || fallbackHero.eyebrow,
    badge: form.badge || fallbackHero.badge,
    title: form.title || fallbackHero.title,
    subtitle: form.subtitle || fallbackHero.subtitle,
    primary_cta_label: form.primary_cta_label || fallbackHero.primary_cta_label,
    primary_cta_url: form.primary_cta_url || fallbackHero.primary_cta_url,
    primary_cta_message: form.primary_cta_message || fallbackHero.primary_cta_message,
    image: imagePreviewUrl.value || fallbackHero.image,
    note: form.note || fallbackHero.note,
}));

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const setActivePreview = (key) => {
    activePreview.value = key;
};

const clearActivePreview = () => {
    activePreview.value = '';
};

const previewBoxClass = (key) => (
    activePreview.value === key
        ? 'border-brand-primary bg-brand-ink/45 ring-2 ring-brand-primary/70 shadow-lift'
        : 'border-white/25 bg-brand-ink/25'
);

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

const setSavedDefaults = () => {
    form.defaults({
        eyebrow: props.heroSection.eyebrow || '',
        badge: props.heroSection.badge || '',
        title: props.heroSection.title || '',
        subtitle: props.heroSection.subtitle || '',
        primary_cta_label: props.heroSection.primary_cta_label || '',
        primary_cta_url: props.heroSection.primary_cta_url || '',
        primary_cta_message: props.heroSection.primary_cta_message || '',
        note: props.heroSection.note || '',
        image: null,
    });
};

const resetToSaved = () => {
    setSavedDefaults();
    form.reset();
    form.clearErrors();
    form.image = null;
    revokeObjectPreview();
    imagePreviewUrl.value = props.heroSection.image_url || '';
    resetFileInput();
};

const handleImageChange = (event) => {
    const [file] = event.target.files;

    setActivePreview('image');
    form.image = file || null;
    revokeObjectPreview();

    if (!file) {
        imagePreviewUrl.value = props.heroSection.image_url || '';
        return;
    }

    objectPreviewUrl.value = URL.createObjectURL(file);
    imagePreviewUrl.value = objectPreviewUrl.value;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada data hero section yang belum valid.',
    });
};

const handleSuccess = () => {
    form.image = null;
    revokeObjectPreview();
    resetFileInput();
    imagePreviewUrl.value = props.heroSection.image_url || '';
    setSavedDefaults();
};

const submitHero = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('admin.hero-sections.update'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: handleSuccess,
            onError: handleError,
            onFinish: () => form.transform((data) => data),
        });
};

watch(
    () => props.heroSection.image_url,
    (value) => {
        if (!objectPreviewUrl.value) {
            imagePreviewUrl.value = value || '';
        }
    },
);

onBeforeUnmount(() => {
    revokeObjectPreview();
});
</script>

<template>
    <Head title="Hero Section" />

    <AdminLayout title="Hero Section">
        <div class="space-y-6">
            <div class="space-y-6">
                <form class="rounded-lg border border-brand-line bg-white p-5 shadow-sm" @submit.prevent="submitHero">
                    <div class="border-b border-brand-line pb-5">
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Form Edit</p>
                        <h3 class="mt-1 text-lg font-bold text-brand-ink">Hero Section</h3>
                    </div>

                    <div class="mt-5 grid gap-5">
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Eyebrow</span>
                                <input v-model="form.eyebrow" type="text" maxlength="80" placeholder="Security Profesional" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('badges')" @blur="clearActivePreview" />
                                <p v-if="form.errors.eyebrow" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.eyebrow }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Badge kedua</span>
                                <input v-model="form.badge" type="text" maxlength="80" placeholder="Siap Operasional" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('badges')" @blur="clearActivePreview" />
                                <p v-if="form.errors.badge" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.badge }}</p>
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Judul hero</span>
                            <input v-model="form.title" type="text" required maxlength="160" placeholder="PT Secure Guard Indonesia" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('title')" @blur="clearActivePreview" />
                            <p v-if="form.errors.title" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.title }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Subjudul</span>
                            <textarea v-model="form.subtitle" required maxlength="700" rows="5" placeholder="Solusi keamanan profesional..." class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('subtitle')" @blur="clearActivePreview" />
                            <p v-if="form.errors.subtitle" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.subtitle }}</p>
                        </label>

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">CTA utama</span>
                                <input v-model="form.primary_cta_label" type="text" maxlength="60" placeholder="Hubungi Kami" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('cta')" @blur="clearActivePreview" />
                                <p v-if="form.errors.primary_cta_label" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.primary_cta_label }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Nomor / URL CTA utama</span>
                                <input v-model="form.primary_cta_url" type="text" maxlength="255" placeholder="6281234567890 atau https://wa.me/6281234567890" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('cta')" @blur="clearActivePreview" />
                                <p v-if="form.errors.primary_cta_url" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.primary_cta_url }}</p>
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Pesan default WhatsApp</span>
                            <textarea v-model="form.primary_cta_message" rows="4" maxlength="500" placeholder="Halo PT Secure Guard Indonesia, saya ingin konsultasi kebutuhan keamanan." class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('cta')" @blur="clearActivePreview" />
                            <p class="mt-2 text-xs font-medium uppercase text-stone-500">Jika CTA utama mengarah ke WhatsApp, pesan ini akan otomatis ditambahkan ke link.</p>
                            <p v-if="form.errors.primary_cta_message" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.primary_cta_message }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Microcopy</span>
                            <input v-model="form.note" type="text" maxlength="180" placeholder="Respon awal untuk kebutuhan kantor..." class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('note')" @blur="clearActivePreview" />
                            <p v-if="form.errors.note" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.note }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Gambar hero</span>
                            <input ref="imageInput" type="file" accept="image/jpeg,image/png" class="focus-ring mt-2 block w-full rounded-lg border border-brand-line bg-white text-body-sm text-brand-ink file:mr-4 file:border-0 file:bg-brand-primary file:px-4 file:py-3 file:text-body-sm file:font-semibold file:text-brand-ink hover:file:bg-brand-primaryDark" @focus="setActivePreview('image')" @blur="clearActivePreview" @change="handleImageChange" />
                            <p class="mt-2 text-xs font-medium uppercase text-stone-500">Format JPG/PNG, maksimal 2MB.</p>
                            <p v-if="form.errors.image" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.image }}</p>
                        </label>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-brand-line pt-5 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line bg-white px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                            @click="resetToSaved"
                        >
                            Reset
                        </button>
                        <button
                            type="submit"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primaryDark hover:bg-brand-primaryDark disabled:cursor-not-allowed disabled:opacity-60"
                            :disabled="form.processing"
                        >
                            {{ submitLabel }}
                        </button>
                    </div>
                </form>

                <section>
                    <div class="rounded-lg border border-brand-line bg-white p-5 shadow-sm">
                        <div class="mb-4">
                            <p class="text-body-sm font-semibold uppercase text-brand-accent">Preview Realtime</p>
                            <h3 class="mt-1 text-lg font-bold text-brand-ink">Tampilan Hero</h3>
                            <p class="mt-1 text-body-sm text-stone-600">Outline akan aktif mengikuti input yang sedang difokuskan.</p>
                        </div>

                        <div class="overflow-hidden rounded-lg border border-brand-line bg-brand-ink text-white">
                            <div class="relative min-h-[560px]">
                                <img :src="preview.image" :alt="preview.title" class="absolute inset-0 h-full w-full object-cover" />
                                <div class="absolute inset-0 bg-gradient-to-r from-brand-ink via-brand-ink/80 to-brand-ink/25"></div>
                                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-brand-ink/70 to-transparent"></div>
                                <div
                                    class="absolute left-4 right-4 top-4 z-10 rounded-lg border border-dashed p-3 text-body-sm font-semibold text-brand-primary backdrop-blur-sm transition"
                                    :class="previewBoxClass('image')"
                                >
                                    Gambar hero
                                </div>

                                <div class="relative z-10 flex min-h-[560px] items-center px-5 py-10 sm:px-6 sm:py-12">
                                    <div class="w-full max-w-4xl">
                                        <div
                                            class="rounded-lg border border-dashed p-3 backdrop-blur-sm transition"
                                            :class="previewBoxClass('badges')"
                                        >
                                            <p class="mb-2 text-xs font-bold uppercase text-brand-primary">Eyebrow & Badge</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span v-if="preview.eyebrow" class="rounded-md border border-brand-primary bg-brand-primary px-3 py-1 text-body-sm font-semibold text-brand-ink">
                                                    {{ preview.eyebrow }}
                                                </span>
                                                <span v-if="preview.badge" class="rounded-md border border-white/20 bg-white/10 px-3 py-1 text-body-sm font-semibold text-white">
                                                    {{ preview.badge }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-4 rounded-lg border border-dashed p-3 backdrop-blur-sm transition"
                                            :class="previewBoxClass('title')"
                                        >
                                            <p class="mb-2 text-xs font-bold uppercase text-brand-primary">Judul hero</p>
                                            <h2 class="text-4xl font-bold leading-tight text-white">
                                                {{ preview.title }}
                                            </h2>
                                        </div>

                                        <div
                                            class="mt-4 rounded-lg border border-dashed p-3 backdrop-blur-sm transition"
                                            :class="previewBoxClass('subtitle')"
                                        >
                                            <p class="mb-2 text-xs font-bold uppercase text-brand-primary">Subjudul</p>
                                            <p class="text-body-md leading-7 text-white/82">
                                                {{ preview.subtitle }}
                                            </p>
                                        </div>

                                        <div
                                            class="mt-4 rounded-lg border border-dashed p-3 backdrop-blur-sm transition"
                                            :class="previewBoxClass('cta')"
                                        >
                                            <p class="mb-2 text-xs font-bold uppercase text-brand-primary">Tombol CTA</p>
                                            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                                <span class="inline-flex h-12 items-center justify-center rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink">
                                                    {{ preview.primary_cta_label }}
                                                </span>
                                                <span class="inline-flex h-12 items-center justify-center rounded-lg border border-white/25 bg-transparent px-5 text-body-sm font-semibold text-white">
                                                    Lihat Layanan
                                                </span>
                                            </div>
                                            <p class="mt-3 text-body-sm text-white/70">
                                                {{ preview.primary_cta_message }}
                                            </p>
                                        </div>

                                        <div
                                            class="mt-4 rounded-lg border border-dashed p-3 backdrop-blur-sm transition"
                                            :class="previewBoxClass('note')"
                                        >
                                            <p class="mb-2 text-xs font-bold uppercase text-brand-primary">Microcopy</p>
                                            <p class="rounded-lg border border-white/15 bg-white/10 p-3 text-body-sm text-white/78">
                                                {{ preview.note }}
                                            </p>
                                        </div>

                                        <div class="mt-7 hidden max-w-3xl grid-cols-3 gap-3 sm:grid">
                                            <div
                                                v-for="item in trustItems"
                                                :key="item.value"
                                                class="rounded-lg border border-white/20 bg-white/10 p-4 backdrop-blur"
                                            >
                                                <p class="text-lg font-bold text-brand-primary">{{ item.value }}</p>
                                                <p class="mt-1 text-body-sm text-white/75">{{ item.label }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AdminLayout>
</template>
