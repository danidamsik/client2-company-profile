<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    aboutSection: {
        type: Object,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const fallbackAbout = {
    eyebrow: 'Tentang Kami',
    title: 'Mitra keamanan yang menjaga ritme operasional tetap tenang',
    subtitle: 'PT Secure Guard Indonesia membantu perusahaan mengelola keamanan dengan personel yang terlatih, prosedur kerja yang jelas, dan komunikasi yang mudah diikuti.',
    image: '/images/security-guard-entrance.jpg',
    advantages: [
        'Penempatan personel menyesuaikan risiko area klien.',
        'Koordinasi lapangan jelas untuk shift, patroli, dan akses masuk.',
        'Pelaporan kegiatan membantu manajemen memantau kondisi keamanan.',
        'Pendekatan pelayanan tetap ramah tanpa mengurangi ketegasan.',
    ],
    values: [
        {
            title: 'Visi',
            description: 'Menjadi mitra keamanan yang dipercaya oleh perusahaan dan instansi di Indonesia.',
        },
        {
            title: 'Misi',
            description: 'Menyediakan layanan pengamanan yang disiplin, responsif, dan mudah dikontrol.',
        },
        {
            title: 'Nilai',
            description: 'Integritas, kesiapsiagaan, komunikasi jelas, dan rasa tanggung jawab di setiap tugas.',
        },
    ],
};

const imageInput = ref(null);
const imagePreviewUrl = ref(props.aboutSection.image_url || '');
const objectPreviewUrl = ref('');
const activePreview = ref('');

const form = useForm({
    eyebrow: props.aboutSection.eyebrow || '',
    title: props.aboutSection.title || '',
    subtitle: props.aboutSection.subtitle || '',
    advantages: props.aboutSection.advantages_text || '',
    vision: props.aboutSection.vision || '',
    mission: props.aboutSection.mission || '',
    values_text: props.aboutSection.values_text || '',
    image: null,
});

const submitLabel = computed(() => (form.processing ? 'Menyimpan...' : 'Simpan Update'));

const splitLines = (value) => value
    .split(/\r\n|\r|\n/)
    .map((item) => item.trim())
    .filter(Boolean);

const advantageItems = computed(() => {
    const items = splitLines(form.advantages || '');

    return items.length ? items : fallbackAbout.advantages;
});

const valueItems = computed(() => {
    const items = [
        {
            title: 'Visi',
            description: form.vision || fallbackAbout.values[0].description,
        },
        {
            title: 'Misi',
            description: form.mission || fallbackAbout.values[1].description,
        },
        {
            title: 'Nilai',
            description: form.values_text || fallbackAbout.values[2].description,
        },
    ];

    return items.filter((item) => item.description);
});

const preview = computed(() => ({
    eyebrow: form.eyebrow || fallbackAbout.eyebrow,
    title: form.title || fallbackAbout.title,
    subtitle: form.subtitle || fallbackAbout.subtitle,
    image: imagePreviewUrl.value || fallbackAbout.image,
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
        ? 'border-brand-primary bg-brand-soft ring-2 ring-brand-primary/50 shadow-sm'
        : 'border-brand-line bg-white/70'
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
        eyebrow: props.aboutSection.eyebrow || '',
        title: props.aboutSection.title || '',
        subtitle: props.aboutSection.subtitle || '',
        advantages: props.aboutSection.advantages_text || '',
        vision: props.aboutSection.vision || '',
        mission: props.aboutSection.mission || '',
        values_text: props.aboutSection.values_text || '',
        image: null,
    });
};

const resetToSaved = () => {
    setSavedDefaults();
    form.reset();
    form.clearErrors();
    form.image = null;
    revokeObjectPreview();
    imagePreviewUrl.value = props.aboutSection.image_url || '';
    resetFileInput();
};

const handleImageChange = (event) => {
    const [file] = event.target.files;

    setActivePreview('image');
    form.image = file || null;
    revokeObjectPreview();

    if (!file) {
        imagePreviewUrl.value = props.aboutSection.image_url || '';
        return;
    }

    objectPreviewUrl.value = URL.createObjectURL(file);
    imagePreviewUrl.value = objectPreviewUrl.value;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada data tentang kami yang belum valid.',
    });
};

const handleSuccess = () => {
    form.image = null;
    revokeObjectPreview();
    resetFileInput();
    imagePreviewUrl.value = props.aboutSection.image_url || '';
    setSavedDefaults();
};

const submitAbout = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('admin.about-sections.update'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: handleSuccess,
            onError: handleError,
            onFinish: () => form.transform((data) => data),
        });
};

watch(
    () => props.aboutSection.image_url,
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
    <Head title="Tentang Kami" />

    <AdminLayout title="Tentang Kami">
        <div class="space-y-6">
            <div class="space-y-6">
                <form class="rounded-lg border border-brand-line bg-white p-5 shadow-sm" @submit.prevent="submitAbout">
                    <div class="border-b border-brand-line pb-5">
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Form Edit</p>
                        <h3 class="mt-1 text-lg font-bold text-brand-ink">Tentang Kami</h3>
                    </div>

                    <div class="mt-5 grid gap-5">
                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Eyebrow</span>
                            <input v-model="form.eyebrow" type="text" maxlength="80" placeholder="Tentang Kami" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('header')" @blur="clearActivePreview" />
                            <p v-if="form.errors.eyebrow" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.eyebrow }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Judul section</span>
                            <input v-model="form.title" type="text" required maxlength="180" placeholder="Mitra keamanan yang menjaga ritme operasional tetap tenang" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('header')" @blur="clearActivePreview" />
                            <p v-if="form.errors.title" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.title }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Deskripsi section</span>
                            <textarea v-model="form.subtitle" required maxlength="900" rows="5" placeholder="PT Secure Guard Indonesia membantu perusahaan..." class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('header')" @blur="clearActivePreview" />
                            <p v-if="form.errors.subtitle" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.subtitle }}</p>
                        </label>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Keunggulan</span>
                            <textarea v-model="form.advantages" maxlength="2000" rows="5" placeholder="Tulis satu keunggulan per baris." class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('advantages')" @blur="clearActivePreview" />
                            <p class="mt-2 text-xs font-medium uppercase text-stone-500">Satu baris akan menjadi satu bullet di landing page.</p>
                            <p v-if="form.errors.advantages" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.advantages }}</p>
                        </label>

                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Visi</span>
                                <textarea v-model="form.vision" maxlength="700" rows="4" class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('values')" @blur="clearActivePreview" />
                                <p v-if="form.errors.vision" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.vision }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Misi</span>
                                <textarea v-model="form.mission" maxlength="700" rows="4" class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('values')" @blur="clearActivePreview" />
                                <p v-if="form.errors.mission" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.mission }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Nilai</span>
                                <textarea v-model="form.values_text" maxlength="700" rows="4" class="focus-ring mt-2 block w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" @focus="setActivePreview('values')" @blur="clearActivePreview" />
                                <p v-if="form.errors.values_text" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.values_text }}</p>
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Gambar tentang kami</span>
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
                            <h3 class="mt-1 text-lg font-bold text-brand-ink">Tampilan Tentang Kami</h3>
                            <p class="mt-1 text-body-sm text-stone-600">Outline akan aktif mengikuti input yang sedang difokuskan.</p>
                        </div>

                        <div class="overflow-hidden rounded-lg border border-brand-line bg-white">
                            <div class="bg-brand-muted p-5">
                                <div
                                    class="rounded-lg border border-dashed p-4 transition"
                                    :class="previewBoxClass('header')"
                                >
                                    <p class="mb-3 text-xs font-bold uppercase text-brand-accent">Eyebrow, judul, deskripsi</p>
                                    <p v-if="preview.eyebrow" class="text-body-sm font-bold uppercase text-brand-accent">{{ preview.eyebrow }}</p>
                                    <h2 class="mt-3 text-3xl font-bold leading-tight text-brand-ink">{{ preview.title }}</h2>
                                    <p class="mt-4 text-body-md leading-7 text-stone-600">{{ preview.subtitle }}</p>
                                </div>
                            </div>

                            <div class="grid gap-5 p-5">
                                <div
                                    class="rounded-lg border border-dashed p-3 transition"
                                    :class="previewBoxClass('image')"
                                >
                                    <p class="mb-2 text-xs font-bold uppercase text-brand-accent">Gambar tentang kami</p>
                                    <img :src="preview.image" :alt="preview.title" class="aspect-[4/3] w-full rounded-lg border border-brand-line object-cover" />
                                </div>

                                <div
                                    class="rounded-lg border border-dashed p-3 transition"
                                    :class="previewBoxClass('advantages')"
                                >
                                    <p class="mb-3 text-xs font-bold uppercase text-brand-accent">Keunggulan</p>
                                    <div class="grid gap-3">
                                        <div
                                            v-for="item in advantageItems"
                                            :key="item"
                                            class="flex gap-3 rounded-lg border border-brand-line bg-white p-3"
                                        >
                                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-brand-primary text-brand-ink">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                                    <path d="m3.2 8.2 3 3 6.6-6.8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <p class="text-body-sm leading-6 text-stone-700">{{ item }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg border border-dashed p-3 transition"
                                    :class="previewBoxClass('values')"
                                >
                                    <p class="mb-3 text-xs font-bold uppercase text-brand-accent">Visi, misi, nilai</p>
                                    <div class="grid gap-3">
                                        <article
                                            v-for="item in valueItems"
                                            :key="item.title"
                                            class="rounded-lg border border-amber-100 bg-brand-soft p-4"
                                        >
                                            <p class="text-body-sm font-bold uppercase text-brand-accent">{{ item.title }}</p>
                                            <p class="mt-2 text-body-sm leading-6 text-stone-700">{{ item.description }}</p>
                                        </article>
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
