# إعداد EmailJS لإرسال حجوزات البريد الإلكتروني

## 🎉 الحل النهائي: EmailJS

تم اختيار EmailJS كحل نهائي لأنه:
- ✅ يعمل مباشرة من Frontend بدون Backend
- ✅ مجاني 200 رسالة شهرياً
- ✅ تصميمات HTML مخصصة بالكامل
- ✅ لا مشاكل CORS
- ✅ سهل الإعداد والتخصيص
- ✅ SDK جاهز للجافا سكريبت

## 📋 الخطوات للإعداد:

### 1. تسجيل في EmailJS
1. اذهب إلى [emailjs.com](https://www.emailjs.com)
2. اضغط "Sign Up" (مجاني بالكامل)
3. سجل حساب جديد باستخدام البريد الإلكتروني

### 2. إنشاء Email Service
1. بعد تسجيل الدخول، اذهب إلى "Email Services"
2. اضغط "Add New Service"
3. اختر المزود الذي تفضله:
   - **Gmail** (موصى به - مجاني)
   - Outlook
   - أو أي مزود آخر
4. اتبع خطوات الربط بالبريد الإلكتروني

### 3. إنشاء Email Template
1. اذهب إلى "Email Templates"
2. اضغط "Create New Template"
3. أدخل اسم للـ template (مثال: "Hotel Booking")
4. اضبط الموضوع (Subject):
   ```
   New Booking Request - {{name}} - {{roomType}}
   ```
5. اضبط المتغيرات (Variables):
   - `name` - اسم العميل
   - `phone` - رقم الهاتف
   - `email` - البريد الإلكتروني
   - `checkIn` - تاريخ الوصول
   - `checkOut` - تاريخ المغادرة
   - `guests` - عدد الضيوف
   - `roomType` - نوع الغرفة
   - `notes` - ملاحظات خاصة

### 4. تصميم محتوى الإيميل (HTML Template)
في قسم "Content"، أضف الكود التالي:
```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f4ea;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #16332A;
            color: #D8BD86;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            padding: 20px;
        }
        .field {
            margin: 10px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #16332A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>THE PALM HOTEL</h1>
            <h2>New Booking Request</h2>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Guest Name:</span> {{name}}
            </div>
            <div class="field">
                <span class="label">Phone:</span> {{phone}}
            </div>
            <div class="field">
                <span class="label">Email:</span> {{email}}
            </div>
            <div class="field">
                <span class="label">Room Type:</span> {{roomType}}
            </div>
            <div class="field">
                <span class="label">Guests:</span> {{guests}}
            </div>
            <div class="field">
                <span class="label">Check-in:</span> {{checkIn}}
            </div>
            <div class="field">
                <span class="label">Check-out:</span> {{checkOut}}
            </div>
            {{#if notes}}
            <div class="field">
                <span class="label">Special Requests:</span> {{notes}}
            </div>
            {{/if}}
        </div>
    </div>
</body>
</html>
```

### 5. الحصول على المعرفات (IDs)
من EmailJS dashboard، ستحتاج إلى:
- **Service ID** - من قسم "Email Services"
- **Template ID** - من قسم "Email Templates"
- **Public Key** - من "Account" → "API Keys"

### 6. تحديث الكود في BookingModal.svelte
افتح الملف: `Frontend/src/Views/components/BookingModal.svelte`

استبدل القيم التالية:
```javascript
const EMAILJS_SERVICE_ID = 'YOUR_SERVICE_ID';      // استبدل بـ Service ID الخاص بك
const EMAILJS_TEMPLATE_ID = 'YOUR_TEMPLATE_ID';    // استبدل بـ Template ID الخاص بك
const EMAILJS_PUBLIC_KEY = 'YOUR_PUBLIC_KEY';      // استبدل بـ Public Key الخاص بك
```

## 🎨 المميزات:
- تصميم HTML مخصص بالكامل
- 200 رسالة مجانية شهرياً
- إحصائيات وتتبع
- ردود تلقائية
- سهل الإدارة

## 📧 ما ستحصل عليه:
- إيميلات بتصميم احترافي
- جميع بيانات الحجز منسقة
- أمان عالي
- معالجة أخطاء تلقائية

## 🔧 إعادة تشغيل المشروع
بعد تحديث الكود، أعد تشغيل المشروع:
```bash
cd Frontend
npm run dev
```

## ⚠️ ملاحظات مهمة:
- الـ Public Key آمن للاستخدام في Frontend
- لا تقم بمشاركة الـ Private Key
- يمكنك مراقبة الاستخدام من Dashboard
- عند تجاوز الحد المجاني، يمكنك الترقية

## 🎯 النظام جاهز!
بمجرد تحديث الـ IDs، النظام سيعمل مباشرة!

الإيميلات ستصلك إلى البريد الذي ربطته في EmailJS Service.