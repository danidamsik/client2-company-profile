<script setup>
import { Link } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import BaseButton from '@/Components/Public/BaseButton.vue';
import BrandLogo from '@/Components/Public/BrandLogo.vue';

const props = defineProps({
    company: {
        type: Object,
        default: () => ({
            logo: '',
            name: 'PT Secure Guard Indonesia',
            email: 'hello@secureguard.test',
            whatsapp: '+62 812 3456 7890',
            whatsapp_url: 'https://wa.me/6281234567890?text=Halo%20PT%20Secure%20Guard%20Indonesia,%20saya%20ingin%20konsultasi%20kebutuhan%20keamanan.',
            location: 'Jakarta, Indonesia',
        }),
    },
});

const navItems = [
    { id: 'home', label: 'Home' },
    { id: 'tentang', label: 'Tentang' },
    { id: 'layanan', label: 'Layanan' },
    { id: 'sertifikasi', label: 'Sertifikasi' },
    { id: 'galeri', label: 'Galeri' },
    { id: 'contact', label: 'Kontak' },
];

const activeSection = ref('home');
const isScrolled = ref(false);
const mobileMenuOpen = ref(false);

const scrollToSection = (sectionId) => {
    const section = document.getElementById(sectionId);

    if (!section) {
        return;
    }

    const top = section.getBoundingClientRect().top + window.scrollY - 78;

    window.scrollTo({
        top,
        behavior: 'smooth',
    });

    mobileMenuOpen.value = false;
};

const updateActiveSection = () => {
    isScrolled.value = window.scrollY > 12;

    const currentPosition = window.scrollY + 120;
    let currentSection = navItems[0].id;

    navItems.forEach((item) => {
        const section = document.getElementById(item.id);

        if (section && section.offsetTop <= currentPosition) {
            currentSection = item.id;
        }
    });

    activeSection.value = currentSection;
};

onMounted(() => {
    updateActiveSection();
    window.addEventListener('scroll', updateActiveSection, { passive: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', updateActiveSection);
});
</script>

<template>
    <div class="min-h-screen bg-white text-brand-ink">
        <header
            class="sticky top-0 z-50 border-b transition duration-200"
            :class="isScrolled ? 'border-brand-line bg-white/95 shadow-nav backdrop-blur' : 'border-transparent bg-white'"
        >
            <nav class="container flex h-20 items-center justify-between gap-4" aria-label="Navigasi utama">
                <button type="button" class="focus-ring rounded-lg" aria-label="Kembali ke bagian utama" @click="scrollToSection('home')">
                    <BrandLogo :logo-url="props.company.logo" :name="props.company.name" :subtitle="props.company.location" />
                </button>

                <div class="hidden items-center gap-1 lg:flex">
                    <button
                        v-for="item in navItems"
                        :key="item.id"
                        type="button"
                        class="focus-ring h-10 rounded-lg px-4 text-body-sm font-semibold transition"
                        :class="
                            activeSection === item.id
                                ? 'bg-brand-primary text-brand-ink'
                                : 'text-stone-700 hover:bg-brand-soft hover:text-brand-ink'
                        "
                        :aria-current="activeSection === item.id ? 'page' : undefined"
                        @click="scrollToSection(item.id)"
                    >
                        {{ item.label }}
                    </button>
                </div>

                <div class="hidden items-center gap-3 lg:flex">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="focus-ring inline-flex h-11 items-center rounded-lg border border-brand-line px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                    >
                        Dashboard
                    </Link>

                    <Link
                        v-else
                        :href="route('login')"
                        class="focus-ring inline-flex h-11 items-center rounded-lg border border-brand-line px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                    >
                        Masuk
                    </Link>

                    <BaseButton variant="primary" @click="scrollToSection('contact')">
                        Hubungi Kami
                    </BaseButton>
                </div>

                <button
                    type="button"
                    class="focus-ring inline-flex h-11 w-11 items-center justify-center rounded-lg border border-brand-line bg-white text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft lg:hidden"
                    aria-label="Menu"
                    title="Menu"
                    :aria-expanded="mobileMenuOpen"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <svg v-if="!mobileMenuOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <svg v-else class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </nav>

            <div v-if="mobileMenuOpen" class="border-t border-brand-line bg-white lg:hidden">
                <div class="container flex flex-col gap-2 py-4">
                    <button
                        v-for="item in navItems"
                        :key="item.id"
                        type="button"
                        class="focus-ring flex h-11 items-center rounded-lg px-4 text-left text-body-sm font-semibold transition"
                        :class="
                            activeSection === item.id
                                ? 'bg-brand-primary text-brand-ink'
                                : 'text-stone-700 hover:bg-brand-soft hover:text-brand-ink'
                        "
                        :aria-current="activeSection === item.id ? 'page' : undefined"
                        @click="scrollToSection(item.id)"
                    >
                        {{ item.label }}
                    </button>

                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line px-4 text-body-sm font-semibold"
                        >
                            Dashboard
                        </Link>

                        <Link
                            v-else
                            :href="route('login')"
                            class="focus-ring inline-flex h-11 items-center justify-center rounded-lg border border-brand-line px-4 text-body-sm font-semibold"
                        >
                            Masuk
                        </Link>

                        <BaseButton class="w-full" variant="primary" @click="scrollToSection('contact')">
                            Hubungi Kami
                        </BaseButton>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="bg-brand-ink text-white">
            <div class="container py-12">
                <div class="grid gap-10 lg:grid-cols-[1.1fr_0.7fr_0.7fr]">
                    <div>
                        <BrandLogo inverse :logo-url="props.company.logo" :name="props.company.name" :subtitle="props.company.location" />
                        <p class="mt-5 max-w-xl text-body-md text-white/75">
                            Solusi keamanan profesional untuk perusahaan, kawasan, dan event dengan layanan yang sigap
                            dan mudah dihubungi.
                        </p>
                        <div class="mt-6">
                            <BaseButton variant="primary" @click="scrollToSection('contact')">
                                Konsultasi Sekarang
                            </BaseButton>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-body-md font-bold">Kontak</h2>
                        <div class="mt-4 space-y-3 text-body-sm text-white/75">
                            <p>Email: {{ props.company.email }}</p>
                            <p>WhatsApp: {{ props.company.whatsapp }}</p>
                            <p>{{ props.company.location }}</p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-body-md font-bold">Navigasi</h2>
                        <div class="mt-4 grid gap-2">
                            <button
                                v-for="item in navItems"
                                :key="item.id"
                                type="button"
                                class="focus-ring w-fit rounded-md text-left text-body-sm text-white/75 transition hover:text-brand-primary"
                                @click="scrollToSection(item.id)"
                            >
                                {{ item.label }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-10 border-t border-white/10 pt-6 text-body-sm text-white/60">
                    Copyright 2026 {{ props.company.name }}. Hak cipta dilindungi.
                </div>
            </div>
        </footer>
    </div>
</template>
