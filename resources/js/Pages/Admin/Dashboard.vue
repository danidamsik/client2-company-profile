<script setup>
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    summary: {
        type: Object,
        required: true,
    },
    stats: {
        type: Array,
        required: true,
    },
    recentContacts: {
        type: Array,
        default: () => [],
    },
});

const exportingPdf = ref(false);

const statTone = (tone) => ({
    amber: 'bg-brand-primary text-brand-ink',
    green: 'bg-green-600 text-white',
    orange: 'bg-brand-accent text-white',
    ink: 'bg-brand-ink text-white',
}[tone] || 'bg-brand-primary text-brand-ink');

const toast = (detail) => {
    window.dispatchEvent(new CustomEvent('admin:toast', { detail }));
};

const exportCompanyProfilePdf = async () => {
    if (exportingPdf.value) {
        return;
    }

    exportingPdf.value = true;

    try {
        const response = await axios.get(route('admin.company-profile.export'), {
            responseType: 'blob',
        });

        const downloadUrl = window.URL.createObjectURL(new Blob([response.data], {
            type: response.headers['content-type'] || 'application/pdf',
        }));
        const link = document.createElement('a');
        const contentDisposition = response.headers['content-disposition'] || '';
        const filenameMatch = contentDisposition.match(/filename="?([^"]+)"?/i);

        link.href = downloadUrl;
        link.download = filenameMatch?.[1] || 'company-profile.pdf';
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(downloadUrl);

        toast({
            type: 'success',
            title: 'Export dimulai',
            message: 'PDF company profile berhasil diunduh.',
        });
    } catch (error) {
        toast({
            type: 'error',
            title: 'Export gagal',
            message: 'PDF belum berhasil dibuat. Coba lagi dalam beberapa saat.',
        });
    } finally {
        exportingPdf.value = false;
    }
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AdminLayout title="Dashboard Admin">
        <div class="space-y-6">
            <section class="rounded-lg border border-brand-line bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Company Profile PDF</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Export tampilan website untuk kebutuhan cetak</h2>
                        <p class="mt-2 max-w-2xl text-body-sm text-stone-600">
                            Sistem akan merender halaman publik dari atas sampai bawah memakai Chrome headless agar hasil PDF mendekati tampilan website.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="focus-ring inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-brand-ink px-5 text-body-sm font-semibold text-white transition hover:bg-brand-charcoal disabled:cursor-not-allowed disabled:opacity-70"
                        :disabled="exportingPdf"
                        @click="exportCompanyProfilePdf"
                    >
                        <svg
                            v-if="exportingPdf"
                            class="h-4 w-4 animate-spin"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path d="M10 3a7 7 0 1 1-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span>{{ exportingPdf ? 'Sedang export...' : 'Export PDF' }}</span>
                    </button>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <component
                    :is="stat.href ? Link : 'div'"
                    v-for="stat in stats"
                    :key="stat.label"
                    :href="stat.href || undefined"
                    class="rounded-lg border border-brand-line bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-card"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-body-sm font-semibold uppercase text-stone-500">{{ stat.label }}</p>
                            <p class="mt-3 text-3xl font-bold text-brand-ink">{{ stat.value }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg" :class="statTone(stat.tone)">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M10 3.5 15.5 6v4.5c0 3.55-2.2 5.8-5.5 7-3.3-1.2-5.5-3.45-5.5-7V6L10 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-body-sm text-stone-600">{{ stat.description }}</p>
                </component>
            </section>

            <section>
                <div class="rounded-lg border border-brand-line bg-white shadow-sm">
                    <div class="border-b border-brand-line p-5">
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Lead Contact</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Pesan terbaru</h2>
                    </div>

                    <div v-if="recentContacts.length" class="divide-y divide-brand-line">
                        <article v-for="contact in recentContacts" :key="contact.id" class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate text-body-md font-bold text-brand-ink">{{ contact.name }}</h3>
                                    <p class="truncate text-body-sm text-stone-600">{{ contact.email }}</p>
                                </div>
                                <span class="shrink-0 text-xs font-semibold uppercase text-stone-500">{{ contact.created_at }}</span>
                            </div>
                            <p class="mt-3 text-body-sm text-stone-700">{{ contact.message }}</p>
                        </article>
                    </div>

                    <div v-else class="p-8 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-brand-soft text-brand-accent">
                            <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4 5h12v10H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="m4.5 6 5.5 4 5.5-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-brand-ink">Belum ada lead</h3>
                        <p class="mt-2 text-body-sm text-stone-600">Pesan dari form publik akan muncul di sini.</p>
                    </div>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>
