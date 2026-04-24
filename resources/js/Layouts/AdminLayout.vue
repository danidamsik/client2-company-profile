<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import BrandLogo from '@/Components/Public/BrandLogo.vue';
import AdminConfirmDialog from '@/Components/Admin/AdminConfirmDialog.vue';
import ToastHub from '@/Components/Admin/ToastHub.vue';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const page = usePage();
const showingSidebar = ref(false);
const sidebarHidden = ref(false);

const navItems = [
    {
        label: 'Dashboard',
        href: route('dashboard'),
        active: 'dashboard',
        icon: 'dashboard',
    },
    {
        label: 'Hero Section',
        href: route('admin.hero-sections.index'),
        active: 'admin.hero-sections.*',
        icon: 'hero',
    },
    {
        label: 'Tentang Kami',
        href: route('admin.about-sections.index'),
        active: 'admin.about-sections.*',
        icon: 'about',
    },
    {
        label: 'Informasi Company',
        href: route('admin.company-information.index'),
        active: 'admin.company-information.*',
        icon: 'company',
    },
    {
        label: 'Layanan',
        href: route('admin.services.index'),
        active: 'admin.services.*',
        icon: 'services',
    },
    {
        label: 'Sertifikasi',
        href: route('admin.certifications.index'),
        active: 'admin.certifications.*',
        icon: 'certificate',
    },
    {
        label: 'Galeri',
        href: route('admin.galleries.index'),
        active: 'admin.galleries.*',
        icon: 'gallery',
    },
];

const user = computed(() => page.props.auth.user);
const companyBrand = computed(() => ({
    logo: page.props.companyBrand?.logo || '',
    name: page.props.companyBrand?.name || 'PT Secure Guard Indonesia',
    location: page.props.companyBrand?.location || 'Jakarta, Indonesia',
}));
const initials = computed(() => user.value?.name
    ?.split(' ')
    .map((part) => part.charAt(0))
    .join('')
    .slice(0, 2)
    .toUpperCase() || 'AD');

const isActive = (item) => route().current(item.active);
const closeSidebar = () => {
    showingSidebar.value = false;
};
const toggleSidebarVisibility = () => {
    sidebarHidden.value = !sidebarHidden.value;
};

onMounted(() => {
    sidebarHidden.value = window.localStorage.getItem('adminSidebarHidden') === 'true';
});

watch(sidebarHidden, (value) => {
    window.localStorage.setItem('adminSidebarHidden', value ? 'true' : 'false');
});
</script>

<template>
    <div class="min-h-screen bg-brand-muted text-brand-ink">
        <ToastHub :flash="$page.props.flash" />
        <AdminConfirmDialog />

        <div
            v-if="showingSidebar"
            class="fixed inset-0 z-40 bg-brand-ink/55 lg:hidden"
            aria-hidden="true"
            @click="closeSidebar"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 transform flex-col border-r border-brand-line bg-white transition duration-200"
            :class="[
                showingSidebar ? 'translate-x-0' : '-translate-x-full',
                sidebarHidden ? 'lg:-translate-x-full' : 'lg:translate-x-0',
                ]"
        >
            <div class="flex h-20 items-center justify-between border-b border-brand-line px-5">
                <Link :href="route('dashboard')" @click="closeSidebar">
                    <BrandLogo :logo-url="companyBrand.logo" :name="companyBrand.name" />
                </Link>

                <button
                    type="button"
                    class="focus-ring flex h-10 w-10 items-center justify-center rounded-md text-stone-500 transition hover:bg-brand-soft hover:text-brand-ink lg:hidden"
                    aria-label="Tutup menu dashboard"
                    @click="closeSidebar"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m6 6 8 8M14 6l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="focus-ring hidden h-10 w-10 items-center justify-center rounded-md text-stone-500 transition hover:bg-brand-soft hover:text-brand-ink lg:flex"
                    aria-label="Sembunyikan sidebar"
                    title="Sembunyikan sidebar"
                    @click="toggleSidebarVisibility"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M13 5 8 10l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-2 overflow-y-auto px-4 py-5">
                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    class="focus-ring flex h-12 items-center gap-3 rounded-lg px-4 text-body-sm font-semibold transition"
                    :class="isActive(item)
                        ? 'bg-brand-primary text-brand-ink shadow-sm'
                        : 'text-stone-700 hover:bg-brand-soft hover:text-brand-ink'"
                    @click="closeSidebar"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-md" :class="isActive(item) ? 'bg-white/55' : 'bg-white'">
                        <svg v-if="item.icon === 'dashboard'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 5.5h5v5H4v-5ZM11 5.5h5v3h-5v-3ZM11 10.5h5v4h-5v-4ZM4 12.5h5v2H4v-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        </svg>
                        <svg v-else-if="item.icon === 'hero'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 5h12v10H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M6.5 8h5M6.5 11h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <svg v-else-if="item.icon === 'about'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 4.5a2 2 0 1 1 0 4 2 2 0 0 1 0-4Z" stroke="currentColor" stroke-width="1.8" />
                            <path d="M5.5 15c.75-2.3 2.35-3.5 4.5-3.5s3.75 1.2 4.5 3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <svg v-else-if="item.icon === 'company'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4.5 16V5.5A1.5 1.5 0 0 1 6 4h8a1.5 1.5 0 0 1 1.5 1.5V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3.5 16h13M7.5 7h1M11.5 7h1M7.5 10h1M11.5 10h1M8 16v-3h4v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg v-else-if="item.icon === 'services'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 3.5 15.5 6v4.5c0 3.55-2.2 5.8-5.5 7-3.3-1.2-5.5-3.45-5.5-7V6L10 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="m7.5 10.2 1.6 1.6 3.5-3.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg v-else-if="item.icon === 'certificate'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 3.5h10v13H5v-13Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M7.5 7h5M7.5 10h5M8 13h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <svg v-else class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 5h12v10H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="m5.5 13 3.25-3.25 2.1 2.1 1.4-1.4L15 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="border-t border-brand-line p-4">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="focus-ring flex h-11 w-full items-center justify-center rounded-lg border border-brand-line bg-white text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft"
                >
                    Logout
                </Link>
            </div>
        </aside>

        <div class="transition-[padding] duration-200" :class="sidebarHidden ? 'lg:pl-0' : 'lg:pl-72'">
            <header class="sticky top-0 z-30 border-b border-brand-line bg-white/95 backdrop-blur">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <button
                        type="button"
                        class="focus-ring flex h-11 w-11 items-center justify-center rounded-lg border border-brand-line bg-white text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft lg:hidden"
                        aria-label="Buka menu dashboard"
                        @click="showingSidebar = true"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 6h12M4 10h12M4 14h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="focus-ring hidden h-11 w-11 items-center justify-center rounded-lg border border-brand-line bg-white text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft lg:flex"
                        :aria-label="sidebarHidden ? 'Tampilkan sidebar' : 'Sembunyikan sidebar'"
                        :title="sidebarHidden ? 'Tampilkan sidebar' : 'Sembunyikan sidebar'"
                        @click="toggleSidebarVisibility"
                    >
                        <svg v-if="sidebarHidden" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M4 6h12M4 10h12M4 14h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <svg v-else class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M13 5 8 10l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        <slot name="header">
                            <h1 class="truncate text-2xl font-bold text-brand-ink">{{ title }}</h1>
                        </slot>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="/"
                            class="focus-ring hidden h-11 items-center justify-center rounded-lg border border-brand-line bg-white px-4 text-body-sm font-semibold text-brand-ink transition hover:border-brand-primary hover:bg-brand-soft sm:inline-flex"
                        >
                            Situs Publik
                        </a>

                        <Link
                            :href="route('profile.edit')"
                            class="focus-ring flex h-11 items-center gap-3 rounded-lg border border-brand-line bg-white px-3 text-left transition hover:border-brand-primary hover:bg-brand-soft"
                        >
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-brand-primary text-body-sm font-bold text-brand-ink">
                                {{ initials }}
                            </span>
                            <span class="hidden min-w-0 md:block">
                                <span class="block truncate text-body-sm font-bold text-brand-ink">{{ user?.name }}</span>
                                <span class="block truncate text-xs text-stone-500">{{ user?.email }}</span>
                            </span>
                        </Link>
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
