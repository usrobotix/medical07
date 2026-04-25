<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'code'                => 'email_clinic_en',
                'title'               => 'Email клинике / IPO (английский)',
                'channel'             => 'email',
                'language'            => 'en',
                'subject'             => 'Cooperation Inquiry — Medical Coordinator for Russian-Speaking Patients',
                'target_partner_type' => 'clinic',
                'body'                => <<<'BODY'
Dear International Patient Office team,

My name is [Your Name]. I am a medical coordinator based in [City/Country],
specializing in second opinion and treatment coordination for Russian-speaking
patients.

I am reaching out to explore the possibility of establishing a cooperation
agreement with [Clinic Name]. Specifically, I am interested in:

1. Understanding your process for international patient referrals.
2. Discussing case submission formats (medical summaries, DICOM, pathology).
3. Learning about your standard response SLA for second opinion requests.
4. Understanding your invoicing process for international patients.

I currently work with patients primarily in the fields of [oncology / orthopedics /
cardiology — choose relevant]. My role is strictly organizational: I prepare and
translate patient cases, coordinate scheduling, and support patients throughout
the process. I do not provide medical advice.

I would appreciate a 20–30 minute call or a brief overview of your international
referral protocol.

Best regards,
[Your Name]
[Phone / Telegram / Email]
[Website / LinkedIn, if available]
BODY,
            ],
            [
                'code'                => 'whatsapp_clinic_ru',
                'title'               => 'WhatsApp — первый контакт с IPO (русский)',
                'channel'             => 'whatsapp',
                'language'            => 'ru',
                'subject'             => null,
                'target_partner_type' => 'clinic',
                'body'                => <<<'BODY'
Добрый день! Меня зовут [Имя], я медицинский координатор для русскоязычных
пациентов. Хочу обсудить возможность сотрудничества по направлению кейсов
на второе мнение в [название клиники]. Подскажите, с кем лучше связаться
по этому вопросу? Спасибо!
BODY,
            ],
            [
                'code'                => 'telegram_clinic_ru',
                'title'               => 'Telegram — первый контакт с IPO (русский)',
                'channel'             => 'telegram',
                'language'            => 'ru',
                'subject'             => null,
                'target_partner_type' => 'clinic',
                'body'                => <<<'BODY'
Добрый день! Меня зовут [Имя], я медицинский координатор для русскоязычных
пациентов. Хочу обсудить возможность сотрудничества по направлению кейсов
на второе мнение в [название клиники]. Подскажите, с кем лучше связаться
по этому вопросу? Спасибо!
BODY,
            ],
            [
                'code'                => 'email_translator_ru',
                'title'               => 'Письмо переводчику (русский)',
                'channel'             => 'email',
                'language'            => 'ru',
                'subject'             => 'Предложение о сотрудничестве — медицинский перевод',
                'target_partner_type' => 'translator',
                'body'                => <<<'BODY'
Добрый день, [Имя]!

Меня зовут [Ваше имя], я медицинский координатор — помогаю пациентам
организовать получение второго мнения в зарубежных клиниках.

Ищу надёжного партнёра по медицинскому переводу ([языковые пары])
в направлении [онкология / ортопедия / кардиология].

Объём работы: периодические кейсы (1–4 в месяц на старте), письменный
перевод медицинских документов, иногда устное сопровождение онлайн-консультации.

Буду рад(а) обсудить:
- ваш опыт в медпереводе (ниши, примеры)
- условия и SLA
- возможность тестового перевода небольшого фрагмента

Удобно пообщаться в Telegram или по почте — как вам удобнее?

С уважением,
[Имя], [контакты]
BODY,
            ],
            [
                'code'                => 'email_curator_ru',
                'title'               => 'Письмо медицинскому редактору / куратору (русский)',
                'channel'             => 'email',
                'language'            => 'ru',
                'subject'             => 'Сотрудничество — куратор медицинских кейсов (второе мнение)',
                'target_partner_type' => 'curator',
                'body'                => <<<'BODY'
Добрый день, [Имя Отчество]!

Меня зовут [Ваше имя], занимаюсь координацией получения второго мнения
для пациентов в зарубежных клиниках.

Ищу врача-консультанта по [специальность] для следующей роли:
- проверка правильности сборки кейса (хронология, список вопросов)
- оценка полноты документов перед отправкой в клинику
- разбор заключения с пациентом «человеческим языком» после консультации

Важно: вы не ставите диагноз пациенту — только помогаете мне как координатору
структурировать кейс и правильно интерпретировать ответ клиники.

Формат сотрудничества: разовые кейсы, оплата за кейс-ревью.
NDA, конфиденциальность — само собой.

Если интересно — давайте созвонимся на 20 минут?

С уважением,
[Имя], [контакты]
BODY,
            ],
        ];

        foreach ($templates as $data) {
            MessageTemplate::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
