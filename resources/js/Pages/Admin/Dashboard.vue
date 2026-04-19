<script setup>
import { Head, Link } from '@inertiajs/vue3';
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
    contentFlow: {
        type: Array,
        default: () => [],
    },
});

const statTone = (tone) => ({
    amber: 'bg-brand-primary text-brand-ink',
    green: 'bg-green-600 text-white',
    orange: 'bg-brand-accent text-white',
    ink: 'bg-brand-ink text-white',
}[tone] || 'bg-brand-primary text-brand-ink');
</script>

<template>
    <Head title="Dashboard Admin" />

    <AdminLayout
        title="Dashboard Admin"
        description="Pantau ringkasan konten publik dan pesan calon klien dari satu tempat."
    >
        <div class="space-y-6">
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

            <section class="grid gap-6 xl:grid-cols-[1fr_0.72fr]">
                <div class="rounded-lg border border-brand-line bg-white shadow-sm">
                    <div class="border-b border-brand-line p-5">
                        <p class="text-body-sm font-semibold uppercase text-brand-accent">Konten</p>
                        <h2 class="mt-1 text-xl font-bold text-brand-ink">Alur publish / update</h2>
                    </div>

                    <div class="grid gap-4 p-5 md:grid-cols-3">
                        <div
                            v-for="(step, index) in contentFlow"
                            :key="step.title"
                            class="rounded-lg border border-brand-line bg-brand-muted p-5"
                        >
                            <span class="flex h-9 w-9 items-center justify-center rounded-md bg-brand-primary text-body-sm font-bold text-brand-ink">
                                {{ index + 1 }}
                            </span>
                            <h3 class="mt-4 text-lg font-bold text-brand-ink">{{ step.title }}</h3>
                            <p class="mt-2 text-body-sm text-stone-600">{{ step.description }}</p>
                        </div>
                    </div>
                </div>

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

            <section class="rounded-lg border border-brand-line bg-brand-ink p-5 text-white shadow-sm">
                <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-body-sm font-semibold uppercase text-brand-primary">Kelola Konten</p>
                        <h2 class="mt-1 text-2xl font-bold">Mulai dari modul yang paling sering berubah</h2>
                        <p class="mt-2 max-w-3xl text-body-sm text-white/70">
                            Struktur halaman CRUD sudah tersedia untuk layanan, sertifikasi, dan galeri. Form create/update dibuka melalui modal agar alur admin konsisten.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Link
                            :href="route('admin.services.index')"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-primary bg-brand-primary px-5 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primaryDark hover:bg-brand-primaryDark"
                        >
                            Layanan
                        </Link>
                        <Link
                            :href="route('admin.galleries.index')"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-white/20 bg-white/10 px-5 text-body-sm font-semibold text-white transition hover:bg-white/15"
                        >
                            Galeri
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>
