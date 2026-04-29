<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Niche;
use App\Models\Partner;
use App\Models\PartnerLayer;
use App\Models\PartnerVerification;
use App\Models\PartnerVerificationItem;
use App\Models\VerificationChecklist;
use App\Models\VerificationChecklistItem;
use Illuminate\Database\Seeder;

class DemoPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $clinicLayer     = PartnerLayer::where('code', 'clinic_ipo')->first();
        $translatorLayer = PartnerLayer::where('code', 'translator')->first();
        $curatorLayer    = PartnerLayer::where('code', 'curator')->first();

        $turkey  = Country::where('iso2', 'TR')->first();
        $israel  = Country::where('iso2', 'IL')->first();

        $oncology     = Niche::where('code', 'oncology')->first();
        $orthopedics  = Niche::where('code', 'neurosurgery_orthopedics')->first();

        // Demo clinic in Turkey
        $clinic = Partner::firstOrCreate(
            ['name' => 'Memorial Hospitals Group (Demo)'],
            [
                'partner_layer_id'  => $clinicLayer?->id,
                'type'              => 'clinic',
                'country_id'        => $turkey?->id,
                'city'              => 'Стамбул',
                'languages'         => 'EN, RU, TR',
                'contact_name'      => 'International Patient Office',
                'contact_email'     => 'ipo@memorial-demo.example',
                'contact_whatsapp'  => '+90 000 000 0000',
                'website_url'       => 'https://www.memorial.com.tr/en',
                'sla_response_hours' => 48,
                'sla_result_days'   => 10,
                'pricing_notes'     => 'Официальный счёт от юрлица клиники.',
                'invoice_required'  => true,
                'status'            => 'new',
                'notes'             => 'Демо-запись. Требует верификации перед реальным использованием.',
            ]
        );

        if ($oncology) {
            $clinic->niches()->syncWithoutDetaching([$oncology->id]);
        }
        if ($orthopedics) {
            $clinic->niches()->syncWithoutDetaching([$orthopedics->id]);
        }
        if ($turkey) {
            $clinic->countries()->syncWithoutDetaching([$turkey->id]);
        }

        // Demo translator
        $translator = Partner::firstOrCreate(
            ['name' => 'Иванова Анна Сергеевна (Demo)'],
            [
                'partner_layer_id'  => $translatorLayer?->id,
                'type'              => 'translator',
                'country_id'        => null,
                'city'              => 'Москва',
                'languages'         => 'EN↔RU, медицинская тематика',
                'contact_name'      => 'Иванова Анна',
                'contact_email'     => 'translator.demo@example.com',
                'contact_telegram'  => '@translator_demo',
                'sla_response_hours' => 24,
                'sla_result_days'   => 3,
                'pricing_notes'     => '500 ₽/стр. (стандарт), 800 ₽/стр. (срочно). Устный: 1500 ₽/ч.',
                'invoice_required'  => false,
                'status'            => 'new',
                'notes'             => 'Демо-запись. Требует тестового перевода и подписания NDA.',
            ]
        );

        if ($oncology) {
            $translator->niches()->syncWithoutDetaching([$oncology->id]);
        }
        if ($turkey) {
            $translator->countries()->syncWithoutDetaching([$turkey->id]);
        }
        if ($israel) {
            $translator->countries()->syncWithoutDetaching([$israel->id]);
        }

        // Demo curator
        $curator = Partner::firstOrCreate(
            ['name' => 'Петров Дмитрий Олегович (Demo)'],
            [
                'partner_layer_id'  => $curatorLayer?->id,
                'type'              => 'curator',
                'country_id'        => null,
                'city'              => 'Санкт-Петербург',
                'languages'         => 'RU',
                'contact_name'      => 'Петров Дмитрий Олегович',
                'contact_email'     => 'curator.demo@example.com',
                'contact_telegram'  => '@curator_demo',
                'sla_response_hours' => 24,
                'pricing_notes'     => '3000 ₽/кейс-ревью.',
                'invoice_required'  => false,
                'status'            => 'new',
                'notes'             => 'Демо-запись. Онколог, к.м.н. Требует проверки на конфликт интересов.',
            ]
        );

        if ($oncology) {
            $curator->niches()->syncWithoutDetaching([$oncology->id]);
        }

        // Create pending verification instances for the demo clinic
        $clinicChecklist = VerificationChecklist::where('code', 'clinic_verification')->first();
        if ($clinicChecklist) {
            $clinicVerification = PartnerVerification::firstOrCreate(
                ['partner_id' => $clinic->id, 'checklist_id' => $clinicChecklist->id],
                ['status' => 'not_started']
            );
            $this->seedVerificationItems($clinicVerification, $clinicChecklist);
        }

        $translatorChecklist = VerificationChecklist::where('code', 'translator_verification')->first();
        if ($translatorChecklist) {
            $translatorVerification = PartnerVerification::firstOrCreate(
                ['partner_id' => $translator->id, 'checklist_id' => $translatorChecklist->id],
                ['status' => 'not_started']
            );
            $this->seedVerificationItems($translatorVerification, $translatorChecklist);
        }

        $curatorChecklist = VerificationChecklist::where('code', 'curator_verification')->first();
        if ($curatorChecklist) {
            $curatorVerification = PartnerVerification::firstOrCreate(
                ['partner_id' => $curator->id, 'checklist_id' => $curatorChecklist->id],
                ['status' => 'not_started']
            );
            $this->seedVerificationItems($curatorVerification, $curatorChecklist);
        }
    }

    /**
     * Create (or skip if already present) one PartnerVerificationItem per
     * checklist item for the given verification record.
     */
    private function seedVerificationItems(
        PartnerVerification $verification,
        VerificationChecklist $checklist
    ): void {
        $items = VerificationChecklistItem::where('checklist_id', $checklist->id)
            ->orderBy('sort_order')
            ->get();

        foreach ($items as $item) {
            PartnerVerificationItem::firstOrCreate(
                [
                    'partner_verification_id' => $verification->id,
                    'checklist_item_id'       => $item->id,
                ],
                [
                    'is_checked' => false,
                    'checked_at' => null,
                    'notes'      => null,
                ]
            );
        }
    }
}
