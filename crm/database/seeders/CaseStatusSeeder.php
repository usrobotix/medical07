<?php

namespace Database\Seeders;

use App\Models\CaseStatus;
use Illuminate\Database\Seeder;

class CaseStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            // Воронка / этапы
            [
                'sort_order' => 1,
                'code' => 'lead_new_request',
                'name' => 'Лид (новый запрос)',
                'description' => 'есть первичное сообщение/заявка, данных мало',
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 2,
                'code' => 'screening_clarification',
                'name' => 'Скрининг / уточнение',
                'description' => "собраны базовые сведения: диагноз/подозрение, срочность, страна/язык, ожидания\nпринято решение: второй взгляд / пересмотр снимков / пересмотр гистологии / организация поездки",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 3,
                'code' => 'offer_package_agreement',
                'name' => 'Офер / согласование пакета',
                'description' => "согласован состав работ, сроки, стоимость вашей координации\nвыставлен счёт/ссылка на оплату, отправлены условия сервиса",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 4,
                'code' => 'payment_received',
                'name' => 'Оплата получена',
                'description' => 'оплачена ваша работа (координация/упаковка кейса). Оплаты клинике/врачу — отдельно, по правилам выбранного провайдера',
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 5,
                'code' => 'documents_collection',
                'name' => 'Сбор документов',
                'description' => "получены выписки/анализы/заключения\nполучены файлы DICOM (КТ/МРТ) при необходимости",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 6,
                'code' => 'translation_case_preparation',
                'name' => 'Перевод / подготовка кейса',
                'description' => "выполнен перевод ключевых документов\nподготовлен Medical case summary (хронология + список вопросов)",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 7,
                'code' => 'clinic_doctor_selection',
                'name' => 'Подбор клиники/врача',
                'description' => "подобраны 1–3 варианта\nсогласованы сроки ответа и стоимость консультации",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 8,
                'code' => 'appointment_confirmation',
                'name' => 'Запись / подтверждение консультации',
                'description' => 'консультация назначена, подтверждены время, формат, требования',
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 9,
                'code' => 'consultation_completed',
                'name' => 'Консультация проведена',
                'description' => 'получено заключение/письмо врача/результат консилиума',
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 10,
                'code' => 'result_delivered_to_client',
                'name' => 'Выдача результата клиенту',
                'description' => "передано заключение\nпроведён разбор «человеческим языком»\nсформированы следующие шаги (next steps)",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 11,
                'code' => 'treatment_coordination_optional',
                'name' => 'Координация лечения (опционально)',
                'description' => "согласование плана лечения, графика процедур\nсмета/счета, организационные вопросы",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 12,
                'code' => 'travel_treatment_optional',
                'name' => 'Поездка / лечение (опционально)',
                'description' => "визовая поддержка (без гарантий)\nлогистика, сопровождение, переводчик",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 13,
                'code' => 'post_followup',
                'name' => 'Пост‑сопровождение',
                'description' => "контрольные анализы/наблюдение\nкоммуникация с клиникой по результатам",
                'is_service' => false,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 14,
                'code' => 'closed_success',
                'name' => 'Закрыто (успешно)',
                'description' => 'кейс завершён, собрана обратная связь',
                'is_service' => false,
                'is_terminal' => true,
            ],
            [
                'sort_order' => 15,
                'code' => 'closed_unsuccess',
                'name' => 'Закрыто (неуспешно/отказ)',
                'description' => 'клиент передумал/не смог оплатить/нет контакта/не подходит по критериям',
                'is_service' => false,
                'is_terminal' => true,
            ],

            // Служебные статусы
            [
                'sort_order' => 101,
                'code' => 'waiting_client',
                'name' => 'Ожидаю клиента',
                'description' => 'жду документы/ответ/оплату',
                'is_service' => true,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 102,
                'code' => 'waiting_partner',
                'name' => 'Ожидаю партнёра',
                'description' => 'жду переводчика/клинику/врача',
                'is_service' => true,
                'is_terminal' => false,
            ],
            [
                'sort_order' => 103,
                'code' => 'stop_legal_ethical_risks',
                'name' => 'Стоп: юридические/этические риски',
                'description' => 'запрос на «серые» действия, подделки, гарантии результата и т.п.',
                'is_service' => true,
                'is_terminal' => false,
            ],
        ];

        foreach ($statuses as $s) {
            CaseStatus::updateOrCreate(
                ['code' => $s['code']],
                $s
            );
        }
    }
}