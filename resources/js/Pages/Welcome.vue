<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import BadgeLabel from '@/Components/Public/BadgeLabel.vue';
import BaseButton from '@/Components/Public/BaseButton.vue';
import BaseCard from '@/Components/Public/BaseCard.vue';
import BaseInput from '@/Components/Public/BaseInput.vue';
import CertificationCard from '@/Components/Public/CertificationCard.vue';
import GalleryCard from '@/Components/Public/GalleryCard.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import SectionContainer from '@/Components/Public/SectionContainer.vue';
import SectionHeading from '@/Components/Public/SectionHeading.vue';
import ServiceCard from '@/Components/Public/ServiceCard.vue';

const props = defineProps({
    seo: {
        type: Object,
        default: () => ({
            title: 'PT Secure Guard Indonesia | Jasa Security Profesional',
            description: 'Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan industri, perumahan, dan event di Indonesia.',
            keywords: 'jasa security, jasa keamanan, security perusahaan, pengamanan event',
            url: '/',
            image: '/images/security-guard-entrance.jpg',
            type: 'website',
            siteName: 'PT Secure Guard Indonesia',
            locale: 'id_ID',
        }),
    },
    publicHeroSection: {
        type: Object,
        default: null,
    },
    publicAboutSection: {
        type: Object,
        default: null,
    },
    publicServiceSection: {
        type: Object,
        default: null,
    },
    publicCompanyInformation: {
        type: Object,
        default: null,
    },
    publicServices: {
        type: Array,
        default: () => [],
    },
    publicCertifications: {
        type: Array,
        default: () => [],
    },
    publicGalleries: {
        type: Array,
        default: () => [],
    },
    isPrintMode: {
        type: Boolean,
        default: false,
    },
});

const contactForm = useForm({
    name: '',
    email: '',
    message: '',
});

const submittedWhatsappUrl = ref('');

const trustItems = [
    { value: 'Legal', label: 'BUJP dan dokumen pendukung' },
    { value: 'Terlatih', label: 'Personel dengan SOP lapangan' },
    { value: 'Responsif', label: 'Konsultasi cepat via WhatsApp' },
];

const defaultAdvantages = [
    'Penempatan personel menyesuaikan risiko area klien.',
    'Koordinasi lapangan jelas untuk shift, patroli, dan akses masuk.',
    'Pelaporan kegiatan membantu manajemen memantau kondisi keamanan.',
    'Pendekatan pelayanan tetap ramah tanpa mengurangi ketegasan.',
];

const defaultValues = [
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
];

const serviceItems = computed(() => props.publicServices);
const certificationItems = computed(() => props.publicCertifications);
const galleryItems = computed(() => props.publicGalleries);
const companyInfo = computed(() => ({
    logo: props.publicCompanyInformation?.logo || '',
    name: props.publicCompanyInformation?.name || 'PT Secure Guard Indonesia',
    email: props.publicCompanyInformation?.email || 'hello@secureguard.test',
    whatsapp: props.publicCompanyInformation?.whatsapp || '+62 812 3456 7890',
    whatsapp_number: props.publicCompanyInformation?.whatsapp_number || '6281234567890',
    whatsapp_url: props.publicCompanyInformation?.whatsapp_url || 'https://wa.me/6281234567890?text=Halo%20PT%20Secure%20Guard%20Indonesia,%20saya%20ingin%20konsultasi%20kebutuhan%20keamanan.',
    location: props.publicCompanyInformation?.location || 'Jakarta, Indonesia',
}));
const whatsappUrl = computed(() => companyInfo.value.whatsapp_url);
const emailUrl = computed(() => `mailto:${companyInfo.value.email}`);
const heroContent = computed(() => ({
    eyebrow: props.publicHeroSection?.eyebrow || 'Security Profesional',
    badge: props.publicHeroSection?.badge || 'Siap Operasional',
    title: props.publicHeroSection?.title || companyInfo.value.name,
    subtitle: props.publicHeroSection?.subtitle || 'Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan, dan event yang membutuhkan personel sigap, terlatih, dan mudah dikoordinasikan.',
    primary_cta_label: props.publicHeroSection?.primary_cta_label || 'Hubungi Kami',
    primary_cta_url: props.publicHeroSection?.primary_cta_url || whatsappUrl.value,
    image: props.publicHeroSection?.image || '/images/security-guard-entrance.jpg',
    note: props.publicHeroSection?.note || 'Respon awal untuk kebutuhan kantor, gudang, pabrik, kawasan, dan kegiatan perusahaan.',
}));
const aboutContent = computed(() => ({
    eyebrow: props.publicAboutSection?.eyebrow || 'Tentang Kami',
    title: props.publicAboutSection?.title || 'Mitra keamanan yang menjaga ritme operasional tetap tenang',
    subtitle: props.publicAboutSection?.subtitle || `${companyInfo.value.name} membantu perusahaan mengelola keamanan dengan personel yang terlatih, prosedur kerja yang jelas, dan komunikasi yang mudah diikuti.`,
    image: props.publicAboutSection?.image || '/images/security-guard-entrance.jpg',
}));
const aboutAdvantages = computed(() => (
    props.publicAboutSection?.advantages?.length ? props.publicAboutSection.advantages : defaultAdvantages
));
const aboutValues = computed(() => {
    const values = props.publicAboutSection?.values
        ?.filter((item) => item.description)
        .map((item) => ({
            title: item.title,
            description: item.description,
        })) || [];

    return values.length ? values : defaultValues;
});
const serviceSectionContent = computed(() => ({
    eyebrow: props.publicServiceSection?.eyebrow || 'Layanan',
    title: props.publicServiceSection?.title || 'Pengamanan untuk kebutuhan operasional',
    subtitle: props.publicServiceSection?.subtitle || 'Pilih model pengamanan yang sesuai dengan karakter lokasi, jumlah personel, jam operasional, dan tingkat risiko bisnis.',
    cta_label: props.publicServiceSection?.cta_label || 'Diskusi Kebutuhan',
    cta_url: props.publicServiceSection?.cta_url || whatsappUrl.value,
}));

let revealObserver = null;

const cleanContactValue = (value) => value
    .replace(/<script\b[^>]*>.*?<\/script>/gis, '')
    .replace(/<style\b[^>]*>.*?<\/style>/gis, '')
    .replace(/<[^>]*>/g, '')
    .trim();

const getSanitizedContactData = () => ({
    name: cleanContactValue(contactForm.name),
    email: cleanContactValue(contactForm.email).toLowerCase(),
    message: cleanContactValue(contactForm.message),
});

const buildContactWhatsappUrl = (payload) => {
    const message = [
        `Halo ${companyInfo.value.name}, saya ingin konsultasi kebutuhan keamanan.`,
        `Nama: ${payload.name}`,
        `Email: ${payload.email}`,
        `Pesan: ${payload.message}`,
    ].join('\n');

    return `https://wa.me/${companyInfo.value.whatsapp_number}?text=${encodeURIComponent(message)}`;
};

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
};

const submitContact = () => {
    submittedWhatsappUrl.value = '';

    const payload = getSanitizedContactData();
    const redirectUrl = buildContactWhatsappUrl(payload);

    contactForm
        .transform(() => payload)
        .post(route('contacts.store'), {
            preserveScroll: true,
            onSuccess: () => {
                contactForm.reset();
                submittedWhatsappUrl.value = redirectUrl;
                window.open(redirectUrl, '_blank', 'noopener,noreferrer');
            },
        });
};

onMounted(() => {
    const elements = document.querySelectorAll('[data-reveal]');

    if (props.isPrintMode) {
        elements.forEach((element) => element.classList.add('is-visible'));

        nextTick(() => {
            document.body.dataset.pdfReady = 'true';
        });

        return;
    }

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.16,
        },
    );

    elements.forEach((element) => revealObserver.observe(element));
});

onBeforeUnmount(() => {
    delete document.body.dataset.pdfReady;
    revealObserver?.disconnect();
});
</script>

<template>
    <Head :title="seo.title" />

    <MainLayout :company="companyInfo" :print-mode="isPrintMode">
        <section
            id="home"
            class="relative flex min-h-[calc(100svh-8.5rem)] scroll-mt-24 overflow-hidden bg-brand-ink text-white"
        >
            <img
                :src="heroContent.image"
                alt="Petugas keamanan profesional berjaga di pintu masuk gedung"
                class="absolute inset-0 h-full w-full object-cover"
                loading="eager"
                decoding="async"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-brand-ink via-brand-ink/80 to-brand-ink/25" />
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-brand-ink/70 to-transparent" />

            <div class="container relative z-10 flex items-center py-10 md:py-14">
                <div class="max-w-4xl">
                    <div class="reveal-on-scroll flex flex-wrap gap-3" data-reveal>
                        <BadgeLabel v-if="heroContent.eyebrow">{{ heroContent.eyebrow }}</BadgeLabel>
                        <BadgeLabel v-if="heroContent.badge" tone="dark">{{ heroContent.badge }}</BadgeLabel>
                    </div>

                    <h1 class="reveal-on-scroll mt-6 max-w-4xl text-display-sm text-white sm:text-display-md md:text-display-lg" data-reveal>
                        {{ heroContent.title }}
                    </h1>

                    <p class="reveal-on-scroll mt-5 max-w-2xl text-body-lg text-white/80" data-reveal>
                        {{ heroContent.subtitle }}
                    </p>

                    <div class="reveal-on-scroll mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-reveal>
                        <BaseButton :href="heroContent.primary_cta_url" size="lg" variant="primary" class="w-full sm:w-auto">
                            {{ heroContent.primary_cta_label }}
                        </BaseButton>
                        <BaseButton size="lg" variant="outline" class="w-full sm:w-auto" @click="scrollToSection('layanan')">
                            Lihat Layanan
                        </BaseButton>
                    </div>

                    <p class="reveal-on-scroll mt-4 text-body-sm text-white/70" data-reveal>
                        {{ heroContent.note }}
                    </p>

                    <div class="reveal-on-scroll mt-7 hidden max-w-3xl grid-cols-3 gap-3 sm:grid" data-reveal>
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
        </section>

        <SectionContainer id="tentang" tone="white" tight-top>
            <div class="grid gap-12 lg:grid-cols-[1fr_0.86fr] lg:items-center">
                <div class="reveal-on-scroll" data-reveal>
                    <SectionHeading
                        :eyebrow="aboutContent.eyebrow"
                        :title="aboutContent.title"
                        :subtitle="aboutContent.subtitle"
                    />

                    <div class="mt-8 grid gap-3">
                        <div
                            v-for="item in aboutAdvantages"
                            :key="item"
                            class="flex gap-3 rounded-lg border border-brand-line bg-white p-4 shadow-sm"
                        >
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-brand-primary text-brand-ink">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="m5 10.4 3 3L15.5 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="text-body-md text-stone-700">{{ item }}</p>
                        </div>
                    </div>
                </div>

                <div class="reveal-on-scroll" data-reveal>
                    <div class="overflow-hidden rounded-lg border border-brand-line bg-brand-muted shadow-lift">
                        <img
                            :src="aboutContent.image"
                            alt="Petugas keamanan sedang berjaga di area gedung"
                            class="aspect-[4/3] w-full object-cover"
                            :loading="isPrintMode ? 'eager' : 'lazy'"
                            decoding="async"
                        />
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <BaseCard v-for="item in aboutValues" :key="item.title" tone="soft">
                            <h3 class="text-lg font-bold text-brand-ink">{{ item.title }}</h3>
                            <p class="mt-2 text-body-sm text-stone-700">{{ item.description }}</p>
                        </BaseCard>
                    </div>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="layanan">
            <div class="flex flex-col gap-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <SectionHeading
                        :eyebrow="serviceSectionContent.eyebrow"
                        :title="serviceSectionContent.title"
                        :subtitle="serviceSectionContent.subtitle"
                    />

                    <BaseButton :href="serviceSectionContent.cta_url" variant="outline" class="w-full sm:w-fit">
                        {{ serviceSectionContent.cta_label }}
                    </BaseButton>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <ServiceCard
                        v-for="(service, index) in serviceItems"
                        :key="service.id || service.title"
                        :service="service"
                        class="reveal-on-scroll"
                        :style="{ transitionDelay: `${Math.min(index, 5) * 70}ms` }"
                        data-reveal
                    />
                </div>

                <div
                    v-if="!serviceItems.length"
                    class="reveal-on-scroll rounded-lg border border-dashed border-brand-line bg-brand-soft p-6 text-center"
                    data-reveal
                >
                    <h3 class="text-lg font-bold text-brand-ink">Layanan belum tersedia</h3>
                    <p class="mt-2 text-body-sm text-stone-700">
                        Data layanan akan tampil otomatis setelah admin menambahkannya dari dashboard.
                    </p>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="sertifikasi" tone="muted">
            <div class="flex flex-col gap-10">
                <SectionHeading
                    eyebrow="Sertifikasi"
                    title="Sertifikasi yang memperkuat kepercayaan"
                    subtitle="Dokumen legal dan bukti kompetensi menjadi bagian dari standar layanan yang transparan untuk calon klien."
                />

                <div class="grid gap-5 lg:grid-cols-3">
                    <CertificationCard
                        v-for="(certification, index) in certificationItems"
                        :key="certification.id || certification.title"
                        :certification="certification"
                        :print-mode="isPrintMode"
                        class="reveal-on-scroll"
                        :style="{ transitionDelay: `${Math.min(index, 5) * 70}ms` }"
                        data-reveal
                    />
                </div>

                <div
                    v-if="!certificationItems.length"
                    class="reveal-on-scroll rounded-lg border border-dashed border-brand-line bg-white p-6 text-center"
                    data-reveal
                >
                    <h3 class="text-lg font-bold text-brand-ink">Sertifikasi belum tersedia</h3>
                    <p class="mt-2 text-body-sm text-stone-700">
                        Data sertifikasi akan tampil otomatis setelah admin mengunggah dokumen dari dashboard.
                    </p>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="galeri">
            <div class="flex flex-col gap-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <SectionHeading
                        eyebrow="Galeri"
                        title="Dokumentasi kegiatan"
                        subtitle="Cuplikan aktivitas tim dalam menjaga area kerja, akses masuk, patroli, dan kegiatan klien."
                    />

                    <BaseButton :href="whatsappUrl" variant="outline" class="w-full sm:w-fit">
                        Minta Portofolio
                    </BaseButton>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <GalleryCard
                        v-for="(item, index) in galleryItems"
                        :key="item.id || item.title"
                        :item="item"
                        :print-mode="isPrintMode"
                        class="reveal-on-scroll"
                        :style="{ transitionDelay: `${Math.min(index, 7) * 55}ms` }"
                        data-reveal
                    />
                </div>

                <div
                    v-if="!galleryItems.length"
                    class="reveal-on-scroll rounded-lg border border-dashed border-brand-line bg-brand-soft p-6 text-center"
                    data-reveal
                >
                    <h3 class="text-lg font-bold text-brand-ink">Galeri belum tersedia</h3>
                    <p class="mt-2 text-body-sm text-stone-700">
                        Dokumentasi kegiatan akan tampil otomatis setelah admin menambahkan gambar dari dashboard.
                    </p>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="contact" tone="dark">
            <div class="grid gap-10 lg:grid-cols-[1fr_0.8fr] lg:items-center">
                <div class="reveal-on-scroll" data-reveal>
                    <SectionHeading
                        eyebrow="Kontak"
                        title="Mulai konsultasi keamanan"
                        subtitle="Tim kami siap membantu menyesuaikan kebutuhan pengamanan perusahaan, kawasan, atau event."
                        inverse
                    />

                    <div class="mt-7 overflow-hidden rounded-lg border border-white/10 bg-white/10">
                        <div class="grid divide-y divide-white/10 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                            <div class="p-5">
                                <p class="text-body-sm font-bold uppercase text-brand-primary">WhatsApp</p>
                                <p class="mt-2 text-body-md text-white">{{ companyInfo.whatsapp }}</p>
                            </div>

                            <div class="p-5">
                                <p class="text-body-sm font-bold uppercase text-brand-primary">Email</p>
                                <p class="mt-2 text-body-md text-white">{{ companyInfo.email }}</p>
                            </div>

                            <div class="p-5">
                                <p class="text-body-sm font-bold uppercase text-brand-primary">Lokasi</p>
                                <p class="mt-2 text-body-md text-white">{{ companyInfo.location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <BaseButton :href="heroContent.primary_cta_url" variant="primary" size="lg" class="w-full sm:w-auto">
                            Chat Langsung
                        </BaseButton>
                        <BaseButton :href="emailUrl" variant="outline" size="lg" class="w-full sm:w-auto">
                            Email Kami
                        </BaseButton>
                    </div>
                </div>

                <form
                    class="reveal-on-scroll rounded-lg border border-white/10 bg-white p-5 shadow-lift sm:p-6"
                    data-reveal
                    @submit.prevent="submitContact"
                >
                    <div>
                        <p class="text-body-sm font-bold uppercase text-brand-accent">Form Kontak</p>
                        <h2 class="mt-2 text-2xl font-bold text-brand-ink">Ceritakan kebutuhan Anda</h2>
                        <p class="mt-2 text-body-sm text-stone-600">
                            Pesan akan tersimpan sebagai lead dan diteruskan ke WhatsApp setelah berhasil dikirim.
                        </p>
                    </div>

                    <div
                        v-if="$page.props.flash && $page.props.flash.success"
                        class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-body-sm font-medium text-green-800"
                    >
                        <p>{{ $page.props.flash.success }}</p>
                        <a
                            v-if="submittedWhatsappUrl"
                            :href="submittedWhatsappUrl"
                            class="mt-3 inline-flex rounded-md bg-green-700 px-4 py-2 text-white transition hover:bg-green-800"
                        >
                            Buka WhatsApp Sekarang
                        </a>
                    </div>

                    <div
                        v-if="$page.props.flash && $page.props.flash.error"
                        class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-body-sm font-medium text-red-800"
                    >
                        {{ $page.props.flash.error }}
                    </div>

                    <div class="mt-6 grid gap-5">
                        <BaseInput
                            id="contact-name"
                            v-model="contactForm.name"
                            label="Nama"
                            placeholder="Nama lengkap"
                            autocomplete="name"
                            required
                            :error="contactForm.errors.name"
                        />

                        <BaseInput
                            id="contact-email"
                            v-model="contactForm.email"
                            label="Email"
                            type="email"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required
                            :error="contactForm.errors.email"
                        />

                        <BaseInput
                            id="contact-message"
                            v-model="contactForm.message"
                            label="Pesan"
                            placeholder="Ceritakan lokasi, jumlah personel, jadwal, atau kebutuhan keamanan Anda."
                            textarea
                            :rows="5"
                            required
                            :error="contactForm.errors.message"
                        />
                    </div>

                    <BaseButton
                        class="mt-6 w-full"
                        type="submit"
                        variant="primary"
                        size="lg"
                        :disabled="contactForm.processing"
                    >
                        {{ contactForm.processing ? 'Mengirim...' : 'Kirim dan Buka WhatsApp' }}
                    </BaseButton>
                </form>
            </div>
        </SectionContainer>
    </MainLayout>
</template>
