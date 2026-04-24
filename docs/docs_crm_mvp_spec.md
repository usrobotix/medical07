# CRM (MVP) — спецификация (Laravel + JS)

## Цель
- вести кейсы пациентов
- хранить базу клиник/врачей/партнёров
- управлять документами, задачами и коммуникациями

## Роли
- Admin
- Coordinator
- Intake/Sales

## Сущности (минимум)
Patients, Cases, Clinics, Doctors, Partners, Documents, Tasks, Communications (опционально), Payments (позже).

## Сценарии
1) Лид → пациент → кейс  
2) Статусы (воронка) + дедлайны  
3) Загрузка документов + типы (imaging/pathology/labs/summary/consent)  
4) Подбор клиники/врача по фильтрам  
5) Формирование пакета (summary + ссылки на файлы)  
6) Логирование коммуникаций (позже интеграции)

# CRM (MVP) — спецификация (Laravel + JS)

## 1. Цель CRM
Единая система для:
- ведения кейсов пациентов
- базы клиник/врачей/партнёров
- управления документами, задачами и коммуникациями

## 2. Роли
- Admin
- Coordinator
- Intake/Sales

## 3. Сущности (минимум)
### 3.1 Patients
- id
- name (или код)
- contacts (tg/wa/phone/email)
- birth_date / age_group (adult/child)
- language

### 3.2 Cases
- id
- patient_id
- direction (oncology/ALS/orthopedics/neurosurgery/pediatric)
- urgency
- status (см. status.md)
- target_country (nullable)
- notes
- created_at

### 3.3 Clinics
- id
- name
- country, city
- specialties
- intl_office_contacts
- submission_requirements (text)
- typical_response_time
- payment_methods

### 3.4 Doctors
- id
- clinic_id
- name
- specialty
- languages
- contact (если применимо)

### 3.5 Partners
- id
- type (translator/logistics/visa/taxi/guide)
- country/city
- contacts
- sla
- pricing_notes

### 3.6 Documents
- id
- case_id
- type (summary, labs, imaging, pathology, consent, other)
- language
- file_path / storage_key
- uploaded_at

### 3.7 Tasks
- id
- case_id
- title
- status (todo/doing/done)
- due_date

### 3.8 Communications (опционально MVP)
- id
- case_id
- channel (email/phone/tg/wa)
- direction (in/out)
- body (text)
- created_at

## 4. Основные сценарии
1) Создать лид → создать пациента → создать кейс
2) Проставить статус и дедлайны
3) Загрузить документы + типизация
4) Поиск клиники/врача по фильтрам (страна/спец/сроки)
5) Экспорт пакета документов (ссылки + summary)

## 5. Нефункциональные требования
- Контроль доступа к документам (RBAC)
- Логи действий
- Резервное копирование
- Хранение чувствительных данных: минимум, шифрование (где возможно)
