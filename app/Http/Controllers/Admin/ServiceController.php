<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServiceSectionRequest;
use App\Http\Requests\Admin\UpsertServiceRequest;
use App\Models\Service;
use App\Models\ServiceSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $serviceSection = $this->serviceSection();

        $services = Service::query()
            ->latest()
            ->paginate(8)
            ->through(fn (Service $service): array => $this->serializeService($service));

        return Inertia::render('Admin/Services/Index', [
            'serviceSection' => $this->serializeServiceSection($serviceSection),
            'services' => $services,
            'summary' => [
                'total' => Service::query()->count(),
                'latestUpdated' => collect([
                    Service::query()->latest('updated_at')->first()?->updated_at,
                    $serviceSection->updated_at,
                ])->filter()->max()?->diffForHumans(),
            ],
        ]);
    }

    public function updateSection(UpdateServiceSectionRequest $request): RedirectResponse
    {
        $this->serviceSection()->update($request->validated());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Konten section layanan berhasil diperbarui.');
    }

    public function store(UpsertServiceRequest $request): RedirectResponse
    {
        Service::query()->create($this->validatedServiceData($request));

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(UpsertServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($this->validatedServiceData($request));

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    private function serviceSection(): ServiceSection
    {
        return ServiceSection::query()->first()
            ?? ServiceSection::query()->create($this->defaultSectionData());
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeService(Service $service): array
    {
        $category = $service->category ?: $this->serviceCategory($service->title);
        $icon = $service->icon ?: $this->serviceIcon($service->title);
        $points = $service->points ?: $this->servicePoints($service->title);

        return [
            'id' => $service->id,
            'title' => $service->title,
            'description' => $service->description,
            'excerpt' => Str::limit($service->description, 130),
            'category' => $category,
            'icon' => $icon,
            'points' => $points,
            'points_text' => implode("\n", $points),
            'updated_at' => $service->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    private function serializeServiceSection(ServiceSection $serviceSection): array
    {
        return [
            'id' => $serviceSection->id,
            'eyebrow' => $serviceSection->eyebrow,
            'title' => $serviceSection->title,
            'subtitle' => $serviceSection->subtitle,
            'cta_label' => $serviceSection->cta_label,
            'cta_url' => $serviceSection->cta_url,
            'updated_at' => $serviceSection->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedServiceData(UpsertServiceRequest $request): array
    {
        $data = $request->validated();
        $pointsText = (string) ($data['points'] ?? '');

        $data['points'] = collect(preg_split('/\r\n|\r|\n/', $pointsText) ?: [])
            ->map(fn (string $item): string => Str::squish($item))
            ->filter()
            ->values()
            ->all();

        return $data;
    }

    /**
     * @return array<string, string>
     */
    private function defaultSectionData(): array
    {
        return [
            'eyebrow' => 'Layanan',
            'title' => 'Pengamanan untuk kebutuhan operasional',
            'subtitle' => 'Pilih model pengamanan yang sesuai dengan karakter lokasi, jumlah personel, jam operasional, dan tingkat risiko bisnis.',
            'cta_label' => 'Diskusi Kebutuhan',
            'cta_url' => null,
        ];
    }

    private function serviceCategory(string $title): string
    {
        $lowerTitle = Str::lower($title);

        return match (true) {
            Str::contains($lowerTitle, ['event', 'acara']) => 'Event',
            Str::contains($lowerTitle, ['patroli', 'monitoring']) => 'Monitoring',
            Str::contains($lowerTitle, ['perumahan', 'apartemen', 'residensial']) => 'Residensial',
            Str::contains($lowerTitle, ['industri', 'gudang', 'pabrik']) => 'Industri',
            Str::contains($lowerTitle, ['reception', 'front office', 'tamu']) => 'Front Office',
            default => 'Korporat',
        };
    }

    private function serviceIcon(string $title): string
    {
        $lowerTitle = Str::lower($title);

        return match (true) {
            Str::contains($lowerTitle, ['cctv', 'kamera', 'camera', 'surveillance']) => 'camera',
            Str::contains($lowerTitle, ['akses', 'access', 'kontrol', 'pintu', 'gate', 'gerbang']) => 'lock',
            Str::contains($lowerTitle, ['patroli', 'monitoring', 'rute', 'route', 'keliling']) => 'route',
            Str::contains($lowerTitle, ['event', 'acara', 'komunikasi', 'radio', 'crowd', 'kerumunan']) => 'radio',
            Str::contains($lowerTitle, ['perumahan', 'apartemen', 'residensial', 'rumah', 'cluster']) => 'home',
            Str::contains($lowerTitle, ['front office', 'reception', 'resepsionis', 'tamu', 'gedung', 'kantor', 'mall', 'office']) => 'building',
            Str::contains($lowerTitle, ['pabrik', 'industri', 'factory', 'produksi']) => 'factory',
            Str::contains($lowerTitle, ['gudang', 'warehouse', 'logistik', 'stok', 'distribusi']) => 'warehouse',
            Str::contains($lowerTitle, ['kendaraan', 'parkir', 'mobil', 'car', 'vehicle']) => 'car',
            Str::contains($lowerTitle, ['pengawalan', 'escort', 'vip', 'vvip']) => 'badge',
            Str::contains($lowerTitle, ['personel', 'staff', 'manpower', 'tenaga', 'tim']) => 'users',
            Str::contains($lowerTitle, ['laporan', 'audit', 'checklist', 'dokumen', 'sop']) => 'clipboard',
            Str::contains($lowerTitle, ['alarm', 'darurat', 'emergency']) => 'alarm',
            Str::contains($lowerTitle, ['api', 'kebakaran', 'fire', 'evakuasi']) => 'fire',
            Str::contains($lowerTitle, ['scan', 'screening', 'metal', 'detektor', 'detector', 'pemeriksaan']) => 'scan',
            Str::contains($lowerTitle, ['operator', 'dispatch', 'helpdesk', 'support']) => 'headset',
            Str::contains($lowerTitle, ['telepon', 'call', 'hotline', 'whatsapp']) => 'phone',
            Str::contains($lowerTitle, ['lokasi', 'area', 'pos', 'site', 'titik']) => 'map-pin',
            Str::contains($lowerTitle, ['kunci', 'key']) => 'key',
            Str::contains($lowerTitle, ['k3', 'safety', 'keselamatan', 'konstruksi']) => 'hard-hat',
            default => 'shield',
        };
    }

    private function servicePoints(string $title): array
    {
        $category = $this->serviceCategory($title);

        return match ($category) {
            'Event' => ['Pengaturan akses peserta', 'Koordinasi titik rawan', 'Pengamanan alur kerumunan'],
            'Monitoring' => ['Jadwal patroli terukur', 'Pengecekan area prioritas', 'Eskalasi insiden cepat'],
            'Residensial' => ['Pencatatan tamu', 'Pengawasan gerbang', 'Patroli lingkungan'],
            'Industri' => ['Kontrol kendaraan', 'Pengawasan area operasional', 'Penjagaan aset strategis'],
            'Front Office' => ['Penerimaan tamu', 'Screening awal', 'Koordinasi dengan admin'],
            default => ['Kontrol akses area', 'Patroli shift kerja', 'Laporan aktivitas rutin'],
        };
    }
}
