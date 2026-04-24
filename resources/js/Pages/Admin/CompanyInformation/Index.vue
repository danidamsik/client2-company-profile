<script setup>
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    companyInformation: {
        type: Object,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const logoInput = ref(null);

const form = useForm({
    logo: null,
    name: props.companyInformation.name || '',
    email: props.companyInformation.email || '',
    whatsapp: props.companyInformation.whatsapp || '',
    location: props.companyInformation.location || '',
});

const submitLabel = computed(() => (form.processing ? 'Menyimpan...' : 'Simpan Update'));

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const resetFileInput = () => {
    if (logoInput.value) {
        logoInput.value.value = '';
    }
};

const setSavedDefaults = () => {
    form.defaults({
        logo: null,
        name: props.companyInformation.name || '',
        email: props.companyInformation.email || '',
        whatsapp: props.companyInformation.whatsapp || '',
        location: props.companyInformation.location || '',
    });
};

const resetToSaved = () => {
    setSavedDefaults();
    form.reset();
    form.clearErrors();
    form.logo = null;
    resetFileInput();
};

const handleLogoChange = (event) => {
    const [file] = event.target.files;

    form.logo = file || null;
};

const handleError = () => {
    toast({
        type: 'error',
        title: 'Periksa form',
        message: 'Ada informasi company yang belum valid.',
    });
};

const handleSuccess = () => {
    form.logo = null;
    resetFileInput();
    setSavedDefaults();
};

const submitCompanyInformation = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(route('admin.company-information.update'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: handleSuccess,
            onError: handleError,
            onFinish: () => form.transform((data) => data),
        });
};
</script>

<template>
    <Head title="Informasi Company" />

    <AdminLayout title="Informasi Company">
        <div class="space-y-6">
            <div class="space-y-6">
                <form class="rounded-lg border border-brand-line bg-white p-5 shadow-sm" @submit.prevent="submitCompanyInformation">
                    <div class="border-b border-brand-line pb-5">
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Form Edit</p>
                        <h3 class="mt-1 text-lg font-bold text-brand-ink">Informasi Company</h3>
                    </div>

                    <div class="mt-5 grid gap-5">
                        <label class="block">
                            <span class="text-body-sm font-semibold text-brand-ink">Logo</span>
                            <input ref="logoInput" type="file" accept="image/jpeg,image/png,image/webp" class="focus-ring mt-2 block w-full rounded-lg border border-brand-line bg-white text-body-sm text-brand-ink file:mr-4 file:border-0 file:bg-brand-primary file:px-4 file:py-3 file:text-body-sm file:font-semibold file:text-brand-ink hover:file:bg-brand-primaryDark" @change="handleLogoChange" />
                            <p class="mt-2 text-xs font-medium uppercase text-stone-500">Format JPG/PNG/WEBP, maksimal 2MB.</p>
                            <p v-if="form.errors.logo" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.logo }}</p>
                        </label>

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Nama company</span>
                                <input v-model="form.name" type="text" required maxlength="120" placeholder="PT Secure Guard Indonesia" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                                <p v-if="form.errors.name" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.name }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Email</span>
                                <input v-model="form.email" type="email" required maxlength="150" placeholder="hello@secureguard.test" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                                <p v-if="form.errors.email" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.email }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">WhatsApp</span>
                                <input v-model="form.whatsapp" type="text" required maxlength="40" placeholder="+62 812 3456 7890" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                                <p v-if="form.errors.whatsapp" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.whatsapp }}</p>
                            </label>

                            <label class="block">
                                <span class="text-body-sm font-semibold text-brand-ink">Lokasi</span>
                                <input v-model="form.location" type="text" required maxlength="180" placeholder="Jakarta, Indonesia" class="focus-ring mt-2 block h-11 w-full rounded-lg border-brand-line text-body-sm shadow-sm focus:border-brand-primary" />
                                <p v-if="form.errors.location" class="mt-2 text-body-sm font-medium text-red-700">{{ form.errors.location }}</p>
                            </label>
                        </div>
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

            </div>
        </div>
    </AdminLayout>
</template>
