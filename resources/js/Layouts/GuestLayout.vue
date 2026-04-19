<script setup>
import BrandLogo from '@/Components/Public/BrandLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-brand-white text-brand-ink lg:grid lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative hidden overflow-hidden bg-brand-ink text-white lg:block">
            <img
                src="/images/security-guard-entrance.jpg"
                alt="Petugas keamanan profesional berjaga di pintu masuk gedung"
                class="absolute inset-0 h-full w-full object-cover"
                loading="eager"
                decoding="async"
            />
            <div class="absolute inset-0 bg-brand-ink/82" />
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-brand-ink to-transparent" />

            <div class="relative z-10 flex min-h-screen flex-col justify-between p-10 xl:p-14">
                <Link href="/" class="focus-ring w-fit rounded-lg" aria-label="Kembali ke halaman utama">
                    <BrandLogo inverse />
                </Link>

                <div class="max-w-xl pb-8">
                    <p class="text-body-sm font-bold uppercase text-brand-primary">Dashboard Admin</p>
                    <h1 class="mt-4 text-display-md text-white">
                        Kelola konten perusahaan dengan tenang.
                    </h1>
                    <p class="mt-5 text-body-lg text-white/78">
                        Masuk untuk memperbarui layanan, sertifikasi, galeri, dan lead yang tampil di website publik.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex min-h-screen flex-col px-5 py-6 sm:px-8 lg:px-12">
            <div class="flex items-center justify-between gap-4 lg:hidden">
                <Link href="/" class="focus-ring rounded-lg" aria-label="Kembali ke halaman utama">
                    <BrandLogo />
                </Link>
            </div>

            <main class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-md rounded-lg border border-brand-line bg-white p-6 shadow-lift sm:p-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
