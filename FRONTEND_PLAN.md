# خطة تنفيذ Frontend - Palm Hotel ERP
## للموقع العام مع SSR (SvelteKit)

---

## ✅ المهام المكتملة
- ✅ Backend Core كامل (Validation, Controllers, Repositories, Services)
- ✅ CORS Headers مضافة في index.php
- ✅ Error Handling و Exception Classes
- ✅ JWT Middleware و Permission Middleware
- ✅ ResponseFormatter موحد

---

## 🚨 المرحلة 1: إعداد SvelteKit Project (SSR)

### 1.1 إنشاء المشروع
```bash
cd c:\xampp\htdocs\Palm
npm create svelte@latest Frontend
# اختيارات:
# - Skeleton project
# - TypeScript: Yes
# - ESLint: Yes
# - Prettier: Yes
# - Playwright: No
# - Vitest: Yes
```

### 1.2 تثبيت Dependencies
```bash
cd Frontend
npm install
npm add -D @sveltejs/adapter-auto
npm add @sveltejs/adapter-node  # لـ SSR على سيرفر PHP
```

### 1.3 إعداد Environment Variables
- إنشاء `.env` في Frontend
```
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

### 1.4 إعداد SvelteKit Config
- تحديث `svelte.config.js` لاستخدام adapter-node
- إعداد SSR للصفحات العامة
- إعداد CSR للـ admin dashboard

**الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 2: إنشاء API Client

### 2.1 إنشاء API Client Base
- **المسار**: `Frontend/src/lib/api/client.ts`
- **المحتوى**:
  - fetch wrapper مع error handling
  - JWT token management
  - Request/Response interceptors
  - Retry logic

### 2.2 إنشاء API Services
- **المسار**: `Frontend/src/lib/api/services/`
- **الخدمات**:
  - `authService.ts` - login, logout, refresh token
  - `roomService.ts` - get rooms, search rooms
  - `reservationService.ts` - create reservation, get my reservations
  - `guestService.ts` - create guest profile
  - `invoiceService.ts` - get invoices

**الوقت المتوقع**: 45 دقيقة

---

## 🚨 المرحلة 3: إنشاء Frontend Models

### 3.1 إنشاء TypeScript Interfaces
- **المسار**: `Frontend/src/lib/models/`
- **النماذج**:
  - `User.ts`
  - `Room.ts`
  - `Guest.ts`
  - `Reservation.ts`
  - `Invoice.ts`
  - `ApiResponse.ts`

**الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 4: الصفحات العامة (SSR)

### 4.1 الصفحة الرئيسية
- **المسار**: `Frontend/src/routes/+page.svelte`
- **المحتوى**:
  - Hero section مع صور
  - عرض الغرف المتاحة
  - نموذج البحث السريع
  - SEO meta tags

### 4.2 صفحة الغرف
- **المسار**: `Frontend/src/routes/rooms/+page.svelte`
- **المحتوى**:
  - قائمة الغرف مع الفلترة
  - عرض تفاصيل كل غرفة
  - صور الغرف
  - الأسعار

### 4.3 صفحة تفاصيل الغرفة
- **المسار**: `Frontend/src/routes/rooms/[id]/+page.svelte`
- **المحتوى**:
  - صور متعددة
  - المميزات
  - المرافق
  - زر الحجز

### 4.4 صفحة الحجز
- **المسار**: `Frontend/src/routes/book/+page.svelte`
- **المحتوى**:
  - نموذج الحجز
  - اختيار التواريخ
  - إدخال بيانات الضيف
  - ملخص الحجز
  - الدفع

### 4.5 صفحة تأكيد الحجز
- **المسار**: `Frontend/src/routes/book/confirmation/+page.svelte`
- **المحتوى**:
  - رقم الحجز
  - تفاصيل الحجز
  - QR Code

### 4.6 صفحة تسجيل الدخول
- **المسار**: `Frontend/src/routes/login/+page.svelte`
- **المحتوى**:
  - نموذج تسجيل الدخول
  - رابط نسخ كلمة المرور

**الوقت المتوقع**: 3 ساعات

---

## 🚨 المرحلة 5: Admin Dashboard (CSR)

### 5.1 Layout الرئيسي
- **المسار**: `Frontend/src/routes/admin/+layout.svelte`
- **المحتوى**:
  - Sidebar navigation
  - Header مع user menu
  - Protected route check

### 5.2 Dashboard الرئيسي
- **المسار**: `Frontend/src/routes/admin/+page.svelte`
- **المحتوى**:
  - إحصائيات سريعة
  - حجوزات اليوم
  - الغرف المتاحة
  - الرسوم المالية

### 5.3 إدارة الغرف
- **المسار**: `Frontend/src/routes/admin/rooms/+page.svelte`
- **المحتوى**:
  - قائمة الغرف
  - إضافة/تعديل غرفة
  - تغيير الحالة

### 5.4 إدارة الحجوزات
- **المسار**: `Frontend/src/routes/admin/reservations/+page.svelte`
- **المحتوى**:
  - قائمة الحجوزات
  - فلترة بالحالة
  - تعديل الحالة
  - إلغاء الحجز

### 5.5 إدارة الضيوف
- **المسار**: `Frontend/src/routes/admin/guests/+page.svelte`
- **المحتوى**:
  - قائمة الضيوف
  - البحث
  - عرض التفاصيل

### 5.6 إدارة الفواتير
- **المسار**: `Frontend/src/routes/admin/invoices/+page.svelte`
- **المحتوى**:
  - قائمة الفواتير
  - إنشاء فاتورة
  - تسجيل الدفع

### 5.7 إدارة المصروفات
- **المسار**: `Frontend/src/routes/admin/expenses/+page.svelte`
- **المحتوى**:
  - قائمة المصروفات
  - إضافة مصروف
  - الموافقة/الرفض

### 5.8 التقارير
- **المسار**: `Frontend/src/routes/admin/reports/+page.svelte`
- **المحتوى**:
  - تقرير مالي
  - تقرير الحجوزات
  - تقرير الإشغال

### 5.9 الإعدادات
- **المسار**: `Frontend/src/routes/admin/settings/+page.svelte`
- **المحتوى**:
  - إعدادات التسعير
  - إعدادات النظام
  - إدارة المستخدمين

**الوقت المتوقع**: 4 ساعات

---

## 🚨 المرحلة 6: SEO Optimization

### 6.1 Meta Tags
- إضافة meta tags لكل صفحة
- Open Graph tags
- Twitter Card tags

### 6.2 Sitemap
- إنشاء sitemap.xml
- تحديثه تلقائياً

### 6.3 Robots.txt
- إنشاء robots.txt

**الوقت المتوقع**: 30 دقيقة

---

## 🚨 المرحلة 7: Testing

### 7.1 Unit Tests
- **المسار**: `Frontend/src/lib/__tests__/`
- **المحتوى**:
  - API client tests
  - Service tests
  - Model tests

### 7.2 Component Tests
- **المسار**: `Frontend/src/routes/__tests__/`
- **المحتوى**:
  - Page component tests
  - Form validation tests

**الوقت المتوقع**: 2 ساعة

---

## 🚨 المرحلة 8: Deployment

### 8.1 Build
```bash
npm run build
```

### 8.2 إعداد السيرفر
- نشر على VPS أو shared hosting
- إعداد Nginx/Apache
- إعداد SSL

**الوقت المتوقع**: 1 ساعة

---

## 📊 ملخص المهام

| المرحلة | المهام | الوقت المتوقع | الأولوية |
|---------|-------|--------------|----------|
| إعداد SvelteKit | 4 مهام | 30 دقيقة | عالية |
| API Client | 2 مهام | 45 دقيقة | عالية |
| Frontend Models | 1 مهمة | 30 دقيقة | عالية |
| الصفحات العامة | 6 صفحات | 3 ساعات | عالية |
| Admin Dashboard | 9 صفحات | 4 ساعات | عالية |
| SEO Optimization | 3 مهام | 30 دقيقة | متوسطة |
| Testing | 2 مهام | 2 ساعة | متوسطة |
| Deployment | 3 مهام | 1 ساعة | عالية |
| **المجموع** | **30 مهمة** | **~11 ساعة** | - |

---

## 🎯 ترتيب التنفيذ الموصى به

### اليوم 1:
1. إعداد SvelteKit Project (30 دقيقة)
2. إنشاء API Client (45 دقيقة)
3. إنشاء Frontend Models (30 دقيقة)

### اليوم 2:
4. الصفحة الرئيسية (45 دقيقة)
5. صفحة الغرف (45 دقيقة)
6. صفحة تفاصيل الغرفة (30 دقيقة)
7. صفحة الحجز (1 ساعة)

### اليوم 3:
8. صفحة تأكيد الحجز (30 دقيقة)
9. صفحة تسجيل الدخول (30 دقيقة)
10. Admin Dashboard Layout (45 دقيقة)
11. Dashboard الرئيسي (45 دقيقة)

### اليوم 4:
12. إدارة الغرف (45 دقيقة)
13. إدارة الحجوزات (1 ساعة)
14. إدارة الضيوف (45 دقيقة)

### اليوم 5:
15. إدارة الفواتير (45 دقيقة)
16. إدارة المصروفات (45 دقيقة)
17. التقارير (45 دقيقة)
18. الإعدادات (45 دقيقة)

### اليوم 6:
19. SEO Optimization (30 دقيقة)
20. Testing (2 ساعة)
21. Deployment (1 ساعة)

---

## ✅ معايير الإنجاز

### الصفحات العامة:
- ✅ جميع الصفحات تعمل بـ SSR
- ✅ SEO meta tags موجودة
- ✅ Mobile responsive
- ✅ Fast loading

### Admin Dashboard:
- ✅ جميع الصفحات تعمل بـ CSR
- ✅ JWT authentication يعمل
- ✅ Permission checks تعمل
- ✅ Real-time updates

### Deployment:
- ✅ موقع يعمل على production
- ✅ SSL certificate
- ✅ Fast loading
- ✅ SEO optimized

---

## 🚀 البدء بالتنفيذ

نوصي بالبدء بـ **إعداد SvelteKit Project** ثم **API Client** ثم **الصفحات العامة** لأنها الأهم للعملاء.

هل تريد أن أبدأ غداً بإعداد SvelteKit؟
