# خطة تنفيذ المراحل - Palm Hotel ERP (الخطة المحدثة)

## 📋 نظرة عامة
هذه الخطة تركز على إكمال جميع المكونات المفقودة في النظام لجعله نظام ERP متكامل وجاهز للإنتاج.

---

## ✅ المراحل المكتملة (السابقة)

### المرحلة 1-10: تنظيف المشروع + Seeders + اختبار أساسي ✅
- حذف Sales Module
- حذف Migrations القديمة
- إنشاء Seeders (User, Setting, Room)
- تشغيل Migrations + Seeders
- اختبار Login و Settings endpoints
- تحديث README.md

---

## 🚨 المرحلة 11: تحديث Validation Layer (فوري - عالية الأولوية)

### 11.1 إنشاء Validator Class محترف
- **المسار**: `app/Core/Validation/Validator.php`
- **المحتوى**:
  - دعم جميع الـ rules: required, email, min, max, numeric, date, confirmed, unique, regex, between, in, array, string, integer, url, phone
  - Custom validation rules
  - Validation error messages مخصصة
  - Multi-language support
- **الوقت المتوقع**: 30 دقيقة

### 11.2 إنشاء Form Request Classes
- **المسار**: `app/Core/Validation/Requests/`
- **المحتوى**:
  - LoginRequest
  - CreateUserRequest
  - CreateGuestRequest
  - CreateReservationRequest
  - CreateInvoiceRequest
  - CreateExpenseRequest
  - UpdateSettingRequest
- **الوقت المتوقع**: 45 دقيقة

### 11.3 تحديث Request Class
- **المسار**: `app/Core/Http/Request.php`
- **التغييرات**:
  - دمج Validator class
  - تحسين error handling
- **الوقت المتوقع**: 15 دقيقة

---

## 🚨 المرحلة 12: تحديث جميع Controllers (فوري - عالية الأولوية)

### 12.1 تحديث GuestController
- **المسار**: `app/Modules/Guests/Presentation/GuestController.php`
- **التغييرات**:
  - استخدام App\Core\Http\Request بدلاً من Illuminate
  - استخدام Form Requests للـ validation
  - إرجاع arrays بدلاً من JsonResponse
- **الوقت المتوقع**: 20 دقيقة

### 12.2 تحديث RoomController
- **المسار**: `app/Modules/Rooms/Presentation/RoomController.php`
- **التغييرات**: نفس GuestController
- **الوقت المتوقع**: 20 دقيقة

### 12.3 تحديث ReservationController
- **المسار**: `app/Modules/Reservations/Presentation/ReservationController.php`
- **التغييرات**: نفس GuestController
- **الوقت المتوقع**: 25 دقيقة

### 12.4 تحديث InvoiceController
- **المسار**: `app/Modules/Accounting/Presentation/InvoiceController.php`
- **التغييرات**: نفس GuestController
- **الوقت المتوقع**: 20 دقيقة

### 12.5 تحديث ExpenseController
- **المسار**: `app/Modules/Accounting/Presentation/ExpenseController.php`
- **التغييرات**: نفس GuestController
- **الوقت المتوقع**: 20 دقيقة

### 12.6 تحديث ReportController
- **المسار**: `app/Modules/Reports/Presentation/ReportController.php`
- **التغييرات**: نفس GuestController
- **الوقت المتوقع**: 15 دقيقة

### 12.7 تحديث index.php helpers
- **المسار**: `index.php`
- **التغييرات**:
  - تحديث handleGuestRequest
  - تحديث handleRoomRequest
  - تحديث handleReservationRequest
  - تحديث handleInvoiceRequest
  - تحديث handleExpenseRequest
  - تحديث handleReportRequest
- **الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 13: تحديث جميع Repositories (فوري - عالية الأولوية)

### 13.1 تحديث EloquentGuestRepository
- **المسار**: `app/Modules/Guests/Infrastructure/EloquentGuestRepository.php`
- **التغييرات**:
  - استخدام PDO بدلاً من Laravel DB
  - تحديث جميع methods
- **الوقت المتوقع**: 25 دقيقة

### 13.2 تحديث EloquentRoomRepository
- **المسار**: `app/Modules/Rooms/Infrastructure/EloquentRoomRepository.php`
- **التغييرات**: نفس GuestRepository
- **الوقت المتوقع**: 25 دقيقة

### 13.3 تحديث EloquentReservationRepository
- **المسار**: `app/Modules/Reservations/Infrastructure/EloquentReservationRepository.php`
- **التغييرات**: نفس GuestRepository
- **الوقت المتوقع**: 30 دقيقة

### 13.4 تحديث EloquentInvoiceRepository
- **المسار**: `app/Modules/Accounting/Infrastructure/EloquentInvoiceRepository.php`
- **التغييرات**: نفس GuestRepository
- **الوقت المتوقع**: 25 دقيقة

### 13.5 تحديث EloquentExpenseRepository
- **المسار**: `app/Modules/Accounting/Infrastructure/EloquentExpenseRepository.php`
- **التغييرات**: نفس GuestRepository
- **الوقت المتوقع**: 25 دقيقة

### 13.6 تحديث Domain Models
- **المسارات**: Guest, Room, Reservation, Invoice, Expense
- **التغييرات**:
  - تحويل من Eloquent إلى simple PHP classes
  - إضافة toArray() methods
- **الوقت المتوقع**: 40 دقيقة

### 13.7 تحديث Command/Query Handlers
- **التغييرات**:
  - تحديث جميع handlers لإنشاء repositories مباشرة
  - إزالة dependency injection
- **الوقت المتوقع**: 45 دقيقة

---

## 🚨 المرحلة 14: إنشاء Pricing Service (فوري - عالية الأولوية)

### 14.1 إنشاء PricingService
- **المسار**: `app/Core/Services/PricingService.php`
- **المحتوى**:
  - calculateReservationTotal()
  - calculateTax()
  - calculateServiceCharge()
  - calculateDeposit()
  - calculateDiscount()
  - applySeasonalPricing()
  - applyLoyaltyDiscount()
- **الوقت المتوقع**: 45 دقيقة

### 14.2 تحديث CreateReservationCommandHandler
- **التغييرات**:
  - استخدام PricingService
  - حساب جميع الرسوم تلقائياً
  - استخدام Settings للـ tax_rate, service_charge_rate
- **الوقت المتوقع**: 20 دقيقة

### 14.3 إضافة Pricing Settings
- **المسار**: `database/seeders/SettingSeeder.php`
- **التغييرات**:
  - إضافة إعدادات تسعير إضافية
  - seasonal_pricing_enabled
  - loyalty_discount_enabled
  - early_booking_discount
- **الوقت المتوقع**: 10 دقيقة

---

## 🚨 المرحلة 15: إنشاء Invoice Service (فوري - عالية الأولوية)

### 15.1 إنشاء InvoiceService
- **المسار**: `app/Core/Services/InvoiceService.php`
- **المحتوى**:
  - generateFromReservation()
  - calculateInvoiceTotal()
  - addLateFees()
  - addEarlyCheckoutFees()
  - addNoShowPenalty()
  - applyPaymentTerms()
- **الوقت المتوقع**: 45 دقيقة

### 15.2 تحديث CreateInvoiceCommandHandler
- **التغييرات**:
  - استخدام InvoiceService
  - حساب جميع الرسوم تلقائياً
  - ربط Invoice بالـ Reservation
- **الوقت المتوقع**: 20 دقيقة

### 15.3 إنشاء Business Rules Engine
- **المسار**: `app/Core/Services/BusinessRulesService.php`
- **المحتوى**:
  - calculateLateCheckoutFee()
  - calculateEarlyCheckoutFee()
  - calculateNoShowPenalty()
  - calculateCancellationFee()
- **الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 16: Error Handling (متوسطة الأولوية)

### 16.1 إنشاء Global Exception Handler
- **المسار**: `app/Core/Exceptions/ExceptionHandler.php`
- **المحتوى**:
  - handle validation errors
  - handle authentication errors
  - handle authorization errors
  - handle not found errors
  - handle database errors
- **الوقت المتوقع**: 30 دقيقة

### 16.2 إنشاء Custom Exception Classes
- **المسار**: `app/Core/Exceptions/`
- **المحتوى**:
  - ValidationException
  - AuthenticationException
  - AuthorizationException
  - NotFoundException
  - BusinessException
- **الوقت المتوقع**: 20 دقيقة

### 16.3 تحديث index.php
- **التغييرات**:
  - إضافة try-catch global
  - استخدام ExceptionHandler
- **الوقت المتوقع**: 15 دقيقة

---

## 🚨 المرحلة 17: Authentication Middleware (متوسطة الأولوية)

### 17.1 إنشاء JWT Middleware
- **المسار**: `app/Core/Middleware/JWTMiddleware.php`
- **المحتوى**:
  - verify JWT token
  - extract user from token
  - add user to request
- **الوقت المتوقع**: 30 دقيقة

### 17.2 تحديث Permission Middleware
- **المسار**: `app/Core/Middleware/PermissionMiddleware.php`
- **التغييرات**:
  - دمج مع JWT verification
  - تحسين error messages
- **الوقت المتوقع**: 15 دقيقة

### 17.3 تطبيق Middleware على index.php
- **التغييرات**:
  - إضافة middleware للـ protected routes
  - تحديث checkPermission function
- **الوقت المتوقع**: 20 دقيقة

---

## 🚨 المرحلة 18: Response Standardization (متوسطة الأولوية)

### 18.1 إنشاء Response Formatter
- **المسار**: `app/Core/Http/Response.php`
- **المحتوى**:
  - success()
  - error()
  - validationError()
  - notFound()
  - unauthorized()
- **الوقت المتوقع**: 20 دقيقة

### 18.2 تحديث جميع Controllers
- **التغييرات**:
  - استخدام Response formatter
  - توحيد format الـ responses
- **الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 19: Testing (منخفضة الأولوية)

### 19.1 إنشاء Unit Tests
- **المسار**: `tests/Unit/`
- **المحتوى**:
  - PricingService tests
  - InvoiceService tests
  - BusinessRulesService tests
  - Validator tests
- **الوقت المتوقع**: 60 دقيقة

### 19.2 إنشاء Integration Tests
- **المسار**: `tests/Integration/`
- **المحتوى**:
  - API endpoint tests
  - Repository tests
  - Handler tests
- **الوقت المتوقع**: 90 دقيقة

### 19.3 إنشاء Feature Tests
- **المسار**: `tests/Feature/`
- **المحتوى**:
  - Reservation flow tests
  - Invoice generation tests
  - Authentication tests
- **الوقت المتوقع**: 60 دقيقة

---

## 🚨 المرحلة 20: Frontend Development (منخفضة الأولوية)

### 20.1 إعداد Frontend Project
- **المسار**: `Frontend/`
- **المحتوى**:
  - إنشاء Svelte project
  - إعداد Vite
  - تثبيت dependencies
- **الوقت المتوقع**: 30 دقيقة

### 20.2 إنشاء Models
- **المسار**: `Frontend/src/Models/`
- **المحتوى**:
  - User model
  - Room model
  - Guest model
  - Reservation model
  - Invoice model
- **الوقت المتوقع**: 45 دقيقة

### 20.3 إنشاء Controllers
- **المسار**: `Frontend/src/Controllers/`
- **المحتوى**:
  - AuthController
  - RoomController
  - ReservationController
  - InvoiceController
- **الوقت المتوقع**: 60 دقيقة

### 20.4 إنشاء Views
- **المسار**: `Frontend/src/Views/`
- **المحتوى**:
  - Login page
  - Dashboard
  - Rooms list
  - Reservations list
  - Invoices list
- **الوقت المتوقع**: 120 دقيقة

---

## 📊 ملخص المهام

| المرحلة | عدد المهام | الأولوية | الوقت المتوقع | الحالة |
|---------|-----------|----------|---------------|--------|
| تنظيف المشروع + Seeders | 9 مهام | عالية | 52 دقيقة | ✅ مكتمل |
| Validation Layer | 3 مهام | عالية | 90 دقيقة | ⏳ قيد الانتظار |
| تحديث Controllers | 7 مهام | عالية | 150 دقيقة | ⏳ قيد الانتظار |
| تحديث Repositories | 7 مهام | عالية | 190 دقيقة | ⏳ قيد الانتظار |
| Pricing Service | 3 مهام | عالية | 75 دقيقة | ⏳ قيد الانتظار |
| Invoice Service | 3 مهام | عالية | 95 دقيقة | ⏳ قيد الانتظار |
| Error Handling | 3 مهام | متوسطة | 65 دقيقة | ⏳ قيد الانتظار |
| Authentication Middleware | 3 مهام | متوسطة | 65 دقيقة | ⏳ قيد الانتظار |
| Response Standardization | 2 مهام | متوسطة | 50 دقيقة | ⏳ قيد الانتظار |
| Testing | 3 مهام | منخفضة | 210 دقيقة | ⏳ قيد الانتظار |
| Frontend Development | 4 مهام | منخفضة | 255 دقيقة | ⏳ قيد الانتظار |
| **المجموع** | **47 مهمة** | - | **1297 دقيقة (~21.5 ساعة)** | **9% مكتمل** |

---

## 🎯 الأولويات الموصى بها

### المرحلة 1: Backend Core (ضروري - 10.5 ساعة)
1. Validation Layer (90 دقيقة)
2. تحديث Controllers (150 دقيقة)
3. تحديث Repositories (190 دقيقة)
4. Pricing Service (75 دقيقة)
5. Invoice Service (95 دقيقة)
6. Error Handling (65 دقيقة)
7. Authentication Middleware (65 دقيقة)
8. Response Standardization (50 دقيقة)

### المرحلة 2: Testing (موصى به - 3.5 ساعة)
1. Unit Tests (60 دقيقة)
2. Integration Tests (90 دقيقة)
3. Feature Tests (60 دقيقة)

### المرحلة 3: Frontend (اختياري - 4.25 ساعة)
1. إعداد Frontend (30 دقيقة)
2. إنشاء Models (45 دقيقة)
3. إنشاء Controllers (60 دقيقة)
4. إنشاء Views (120 دقيقة)

---

## 📅 الجدول الزمني المقترح

### الأسبوع 1: Backend Core
- **السبت**: Validation Layer + تحديث Controllers (4 ساعات)
- **الأحد**: تحديث Repositories (3.2 ساعة)
- **الاثنين**: Pricing Service + Invoice Service (3 ساعة)
- **الثلاثاء**: Error Handling + Authentication Middleware (2.2 ساعة)
- **الأربعاء**: Response Standardization + اختبار شامل (1.5 ساعة)

### الأسبوع 2: Testing + Frontend
- **السبت**: Unit Tests + Integration Tests (2.5 ساعة)
- **الأحد**: Feature Tests (1 ساعة)
- **الاثنين**: إعداد Frontend + Models (1.25 ساعة)
- **الثلاثاء**: Controllers (1 ساعة)
- **الأربعاء**: Views (2 ساعة)

---

## ✅ معايير الإنجاز

### Backend Core:
- ✅ Validation layer محترف يعمل
- ✅ جميع Controllers محدثة وتعمل بدون Laravel
- ✅ جميع Repositories محدثة وتستخدم PDO
- ✅ Pricing Service يحسب جميع الرسوم بشكل صحيح
- ✅ Invoice Service يولد فواتير مفصلة
- ✅ Error handling مركزي يعمل
- ✅ JWT middleware يعمل
- ✅ جميع الـ endpoints موحدة

### Testing:
- ✅ Unit tests للـ services
- ✅ Integration tests للـ endpoints
- ✅ Feature tests للـ flows

### Frontend:
- ✅ Frontend project يعمل
- ✅ جميع الـ pages موجودة
- ✅ API integration يعمل

---

## 🚀 البدء بالتنفيذ

نوصي بالبدء بـ **Validation Layer** لأنه أساس كل شيء، ثم **تحديث Controllers و Repositories**، ثم **Pricing و Invoice Services**.

هل تريد أن أبدأ بـ Validation Layer الآن؟
