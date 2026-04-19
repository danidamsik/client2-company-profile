<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
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
    publicGalleries: {
        type: Array,
        default: () => [],
    },
});

const whatsappUrl = 'https://wa.me/6281234567890?text=Halo%20PT%20Secure%20Guard%20Indonesia,%20saya%20ingin%20konsultasi%20kebutuhan%20keamanan.';

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

const advantages = [
    'Penempatan personel menyesuaikan risiko area klien.',
    'Koordinasi lapangan jelas untuk shift, patroli, dan akses masuk.',
    'Pelaporan kegiatan membantu manajemen memantau kondisi keamanan.',
    'Pendekatan pelayanan tetap ramah tanpa mengurangi ketegasan.',
];

const values = [
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

const serviceItems = [
    {
        title: 'Security Perusahaan',
        category: 'Korporat',
        description: 'Personel keamanan untuk kantor, gudang, pabrik, dan area operasional bisnis.',
        icon: 'shield',
        points: ['Kontrol akses tamu dan karyawan', 'Patroli shift area kerja', 'Laporan aktivitas harian'],
    },
    {
        title: 'Pengamanan Event',
        category: 'Event',
        description: 'Tim lapangan untuk event perusahaan, pameran, gathering, dan kegiatan publik.',
        icon: 'radio',
        points: ['Pengaturan akses peserta', 'Koordinasi titik rawan', 'Pengamanan crowd flow'],
    },
    {
        title: 'Patroli Area',
        category: 'Monitoring',
        description: 'Pengawasan rutin untuk menjaga aset, akses, dan aktivitas di area klien.',
        icon: 'route',
        points: ['Jadwal patroli terukur', 'Pengecekan perimeter', 'Eskalasi insiden cepat'],
    },
    {
        title: 'Security Perumahan',
        category: 'Residensial',
        description: 'Pengamanan cluster, apartemen, dan kawasan hunian dengan pendekatan ramah penghuni.',
        icon: 'building',
        points: ['Pencatatan tamu', 'Pengawasan gerbang', 'Patroli lingkungan'],
    },
    {
        title: 'Pengamanan Kawasan Industri',
        category: 'Industri',
        description: 'Tim keamanan untuk area produksi, logistik, dan gudang dengan prosedur operasional ketat.',
        icon: 'shield',
        points: ['Pengawasan loading area', 'Kontrol kendaraan', 'Penjagaan aset strategis'],
    },
    {
        title: 'Reception Security',
        category: 'Front Office',
        description: 'Petugas keamanan yang menjaga area depan sekaligus membantu alur tamu secara profesional.',
        icon: 'building',
        points: ['Penerimaan tamu', 'Screening awal', 'Koordinasi dengan admin'],
    },
];

const certificationItems = [
    {
        title: 'Sertifikat Badan Usaha Jasa Pengamanan',
        issuer: 'Legalitas Perusahaan',
        image: '/storage/certifications/bujp-certificate.svg',
        alt: 'Ilustrasi sertifikat BUJP PT Secure Guard Indonesia',
        description: 'Dokumen legal perusahaan untuk penyediaan jasa pengamanan profesional.',
        tags: ['BUJP', 'Legal', 'Perusahaan'],
    },
    {
        title: 'Sertifikat Pelatihan Gada Pratama',
        issuer: 'Kompetensi Personel',
        image: '/storage/certifications/gada-pratama.svg',
        alt: 'Ilustrasi sertifikat pelatihan Gada Pratama',
        description: 'Bukti kompetensi dasar personel security untuk menjalankan tugas lapangan.',
        tags: ['Gada Pratama', 'Pelatihan', 'Security'],
    },
    {
        title: 'Sertifikat Keselamatan dan Kesehatan Kerja',
        issuer: 'Standar Operasional',
        image: '/storage/certifications/k3-certificate.svg',
        alt: 'Ilustrasi sertifikat K3 umum',
        description: 'Komitmen terhadap prosedur kerja aman dalam layanan operasional pengamanan.',
        tags: ['K3', 'SOP', 'Safety'],
    },
];

const staticGalleryItems = [
    {
        title: 'Briefing Personel',
        category: 'Operasional',
        year: '2026',
        image: '/storage/galleries/security-briefing.jpg',
        alt: 'Tim security berdiri di area kendaraan patroli sebelum bertugas',
        caption: 'Koordinasi personel sebelum menjalankan shift dan pembagian titik penjagaan.',
    },
    {
        title: 'Patroli Area',
        category: 'Monitoring',
        year: '2026',
        image: '/storage/galleries/area-patrol.jpg',
        alt: 'Dua petugas security melakukan patroli di area indoor',
        caption: 'Patroli rutin untuk memastikan area publik dan operasional tetap aman.',
    },
    {
        title: 'Akses Masuk',
        category: 'Kontrol Akses',
        year: '2026',
        image: '/storage/galleries/access-control.jpg',
        alt: 'Petugas keamanan berjaga di pintu masuk gedung',
        caption: 'Pengawasan akses masuk untuk tamu, karyawan, dan vendor di area klien.',
    },
    {
        title: 'Pengamanan Event',
        category: 'Event',
        year: '2026',
        image: '/storage/galleries/event-security.jpg',
        alt: 'Petugas security mengawasi kerumunan acara luar ruang',
        caption: 'Pengamanan kegiatan publik dengan pengawasan crowd flow dan titik rawan.',
    },
];

const galleryItems = computed(() => (props.publicGalleries.length ? props.publicGalleries : staticGalleryItems));

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
        'Halo PT Secure Guard Indonesia, saya ingin konsultasi kebutuhan keamanan.',
        `Nama: ${payload.name}`,
        `Email: ${payload.email}`,
        `Pesan: ${payload.message}`,
    ].join('\n');

    return `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
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
    revealObserver?.disconnect();
});
</script>

<template>
    <Head title="PT Secure Guard Indonesia" />

    <MainLayout>
        <section
            id="home"
            class="relative flex min-h-[calc(100svh-8.5rem)] scroll-mt-24 overflow-hidden bg-brand-ink text-white"
        >
            <img
                src="/images/security-guard-entrance.jpg"
                alt="Petugas keamanan profesional berjaga di pintu masuk gedung"
                class="absolute inset-0 h-full w-full object-cover"
                loading="eager"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-brand-ink via-brand-ink/80 to-brand-ink/25" />
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-brand-ink/70 to-transparent" />

            <div class="container relative z-10 flex items-center py-10 md:py-14">
                <div class="max-w-4xl">
                    <div class="reveal-on-scroll flex flex-wrap gap-3" data-reveal>
                        <BadgeLabel>Security Profesional</BadgeLabel>
                        <BadgeLabel tone="dark">Siap Operasional</BadgeLabel>
                    </div>

                    <h1 class="reveal-on-scroll mt-6 max-w-4xl text-display-md text-white md:text-display-lg" data-reveal>
                        PT Secure Guard Indonesia
                    </h1>

                    <p class="reveal-on-scroll mt-5 max-w-2xl text-body-lg text-white/80" data-reveal>
                        Solusi keamanan profesional dan terpercaya untuk perusahaan, kawasan, dan event yang membutuhkan
                        personel sigap, terlatih, dan mudah dikoordinasikan.
                    </p>

                    <div class="reveal-on-scroll mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-reveal>
                        <BaseButton :href="whatsappUrl" size="lg" variant="primary">
                            Hubungi Kami
                        </BaseButton>
                        <BaseButton :href="whatsappUrl" size="lg" variant="secondary">
                            Konsultasi Sekarang
                        </BaseButton>
                        <BaseButton size="lg" variant="outline" @click="scrollToSection('layanan')">
                            Lihat Layanan
                        </BaseButton>
                    </div>

                    <p class="reveal-on-scroll mt-4 text-body-sm text-white/70" data-reveal>
                        Respon awal untuk kebutuhan kantor, gudang, pabrik, kawasan, dan kegiatan perusahaan.
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
                        eyebrow="Tentang Kami"
                        title="Mitra keamanan yang menjaga ritme operasional tetap tenang"
                        subtitle="PT Secure Guard Indonesia membantu perusahaan mengelola keamanan dengan personel yang terlatih, prosedur kerja yang jelas, dan komunikasi yang mudah diikuti."
                    />

                    <div class="mt-8 grid gap-3">
                        <div
                            v-for="item in advantages"
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
                            src="/images/security-guard-entrance.jpg"
                            alt="Petugas keamanan sedang berjaga di area gedung"
                            class="aspect-[4/3] w-full object-cover"
                            loading="lazy"
                        />
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <BaseCard v-for="item in values" :key="item.title" tone="soft">
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
                        eyebrow="Layanan"
                        title="Pengamanan untuk kebutuhan operasional"
                        subtitle="Pilih model pengamanan yang sesuai dengan karakter lokasi, jumlah personel, jam operasional, dan tingkat risiko bisnis."
                    />

                    <BaseButton :href="whatsappUrl" variant="outline" class="w-fit">
                        Diskusi Kebutuhan
                    </BaseButton>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <ServiceCard
                        v-for="service in serviceItems"
                        :key="service.title"
                        :service="service"
                        class="reveal-on-scroll"
                        data-reveal
                    />
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
                        v-for="certification in certificationItems"
                        :key="certification.title"
                        :certification="certification"
                        class="reveal-on-scroll"
                        data-reveal
                    />
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

                    <BaseButton :href="whatsappUrl" variant="outline" class="w-fit">
                        Minta Portofolio
                    </BaseButton>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <GalleryCard
                        v-for="item in galleryItems"
                        :key="item.title"
                        :item="item"
                        class="reveal-on-scroll"
                        data-reveal
                    />
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="contact" tone="dark">
            <div class="grid gap-10 lg:grid-cols-[1fr_0.8fr] lg:items-center">
                <div class="reveal-on-scroll" data-reveal>
                    <SectionHeading
                        eyebrow="Contact"
                        title="Mulai konsultasi keamanan"
                        subtitle="Tim kami siap membantu menyesuaikan kebutuhan pengamanan perusahaan, kawasan, atau event."
                        inverse
                    />

                    <div class="mt-7 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-white/10 bg-white/10 p-5">
                            <p class="text-body-sm font-bold uppercase text-brand-primary">WhatsApp</p>
                            <p class="mt-2 text-body-md text-white">+62 812 3456 7890</p>
                            <p class="mt-2 text-body-sm text-white/70">Respon awal untuk konsultasi kebutuhan personel.</p>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/10 p-5">
                            <p class="text-body-sm font-bold uppercase text-brand-primary">Email</p>
                            <p class="mt-2 text-body-md text-white">hello@secureguard.test</p>
                            <p class="mt-2 text-body-sm text-white/70">Kirim detail kebutuhan untuk penawaran tertulis.</p>
                        </div>
                    </div>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <BaseButton :href="whatsappUrl" variant="primary" size="lg">
                            Chat Langsung
                        </BaseButton>
                        <BaseButton href="mailto:hello@secureguard.test" variant="outline" size="lg">
                            Email Kami
                        </BaseButton>
                    </div>
                </div>

                <form
                    class="reveal-on-scroll rounded-lg border border-white/10 bg-white p-6 shadow-lift"
                    data-reveal
                    @submit.prevent="submitContact"
                >
                    <div>
                        <p class="text-body-sm font-bold uppercase text-brand-accent">Form Contact</p>
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
