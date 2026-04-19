export const serviceIconOptions = [
    { value: 'shield', label: 'Shield', hint: 'Security umum, akses area, proteksi' },
    { value: 'lock', label: 'Access Lock', hint: 'Kontrol akses, pintu, gate, gerbang' },
    { value: 'key', label: 'Key Control', hint: 'Kunci, akses khusus, ruang terbatas' },
    { value: 'door-open', label: 'Gate Check', hint: 'Pemeriksaan pintu masuk dan keluar' },
    { value: 'camera', label: 'CCTV', hint: 'Kamera, surveillance, pengawasan visual' },
    { value: 'eye', label: 'Watch', hint: 'Observasi, pengawasan, monitoring area' },
    { value: 'scan', label: 'Screening', hint: 'Pemeriksaan, metal detector, scan tamu' },
    { value: 'radio', label: 'Radio', hint: 'Event, koordinasi, komunikasi lapangan' },
    { value: 'headset', label: 'Command Center', hint: 'Operator, helpdesk, dispatch, support' },
    { value: 'phone', label: 'Hotline', hint: 'Telepon, call center, kontak darurat' },
    { value: 'route', label: 'Patrol Route', hint: 'Patroli, monitoring, pengecekan rute' },
    { value: 'map-pin', label: 'Site Point', hint: 'Pos jaga, lokasi, titik pengamanan' },
    { value: 'car', label: 'Vehicle', hint: 'Parkir, kendaraan, area drop-off' },
    { value: 'truck', label: 'Logistics', hint: 'Armada, logistik, loading barang' },
    { value: 'building', label: 'Building', hint: 'Gedung, kantor, mall, front office' },
    { value: 'home', label: 'Residential', hint: 'Perumahan, apartemen, cluster' },
    { value: 'factory', label: 'Factory', hint: 'Pabrik, industri, area produksi' },
    { value: 'warehouse', label: 'Warehouse', hint: 'Gudang, stok, distribusi' },
    { value: 'users', label: 'Team', hint: 'Personel, manpower, tim security' },
    { value: 'user-check', label: 'Verified Guard', hint: 'Petugas terverifikasi, absensi, SOP' },
    { value: 'badge', label: 'VIP Badge', hint: 'VIP, VVIP, pengawalan, identitas' },
    { value: 'clipboard', label: 'Report', hint: 'Laporan, audit, checklist, dokumen' },
    { value: 'alarm', label: 'Alarm', hint: 'Alarm, darurat, respon cepat' },
    { value: 'siren', label: 'Siren', hint: 'Incident response, emergency, peringatan' },
    { value: 'fire', label: 'Fire Safety', hint: 'Kebakaran, fire safety, evakuasi' },
    { value: 'hard-hat', label: 'Safety', hint: 'K3, konstruksi, keselamatan kerja' },
];

export const serviceIconValues = serviceIconOptions.map((option) => option.value);

export const iconLabel = (value) => (
    serviceIconOptions.find((option) => option.value === value)?.label || 'Shield'
);

const normalizedText = (value) => String(value || '').toLowerCase();

const containsAny = (text, keywords) => keywords.some((keyword) => text.includes(keyword));

export const recommendServiceIcon = (title, category) => {
    const text = `${normalizedText(title)} ${normalizedText(category)}`;

    if (containsAny(text, ['cctv', 'kamera', 'camera', 'surveillance'])) {
        return 'camera';
    }

    if (containsAny(text, ['akses', 'access', 'kontrol', 'pintu', 'gate', 'gerbang'])) {
        return 'lock';
    }

    if (containsAny(text, ['patroli', 'monitoring', 'rute', 'route', 'keliling'])) {
        return 'route';
    }

    if (containsAny(text, ['event', 'acara', 'komunikasi', 'radio', 'crowd', 'kerumunan'])) {
        return 'radio';
    }

    if (containsAny(text, ['perumahan', 'apartemen', 'residensial', 'rumah', 'cluster'])) {
        return 'home';
    }

    if (containsAny(text, ['front office', 'reception', 'resepsionis', 'tamu', 'gedung', 'kantor', 'mall', 'office'])) {
        return 'building';
    }

    if (containsAny(text, ['pabrik', 'industri', 'factory', 'produksi'])) {
        return 'factory';
    }

    if (containsAny(text, ['gudang', 'warehouse', 'logistik', 'stok', 'distribusi'])) {
        return 'warehouse';
    }

    if (containsAny(text, ['kendaraan', 'parkir', 'mobil', 'car', 'vehicle'])) {
        return 'car';
    }

    if (containsAny(text, ['pengawalan', 'escort', 'vip', 'vvip'])) {
        return 'badge';
    }

    if (containsAny(text, ['personel', 'staff', 'manpower', 'tenaga', 'tim'])) {
        return 'users';
    }

    if (containsAny(text, ['laporan', 'audit', 'checklist', 'dokumen', 'sop'])) {
        return 'clipboard';
    }

    if (containsAny(text, ['alarm', 'darurat', 'emergency'])) {
        return 'alarm';
    }

    if (containsAny(text, ['api', 'kebakaran', 'fire', 'evakuasi'])) {
        return 'fire';
    }

    if (containsAny(text, ['scan', 'screening', 'metal', 'detektor', 'detector', 'pemeriksaan'])) {
        return 'scan';
    }

    if (containsAny(text, ['operator', 'dispatch', 'helpdesk', 'support'])) {
        return 'headset';
    }

    if (containsAny(text, ['telepon', 'call', 'hotline', 'whatsapp'])) {
        return 'phone';
    }

    if (containsAny(text, ['lokasi', 'area', 'pos', 'site', 'titik'])) {
        return 'map-pin';
    }

    if (containsAny(text, ['kunci', 'key'])) {
        return 'key';
    }

    if (containsAny(text, ['k3', 'safety', 'keselamatan', 'konstruksi'])) {
        return 'hard-hat';
    }

    return 'shield';
};
