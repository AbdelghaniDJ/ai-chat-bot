# ⚡ بوت الذكاء الاصطناعي — PHP + MySQL + Groq (مجاني!)

## لماذا Groq؟
✅ مجاني تماماً — بدون بطاقة بنكية  
✅ 14,400 طلب/يوم  
✅ سريع جداً (800 token/ثانية)  
✅ يستخدم LLaMA 3.3 — نموذج قوي ومفتوح المصدر  

---

## الخطوة 1 — احصل على مفتاح Groq المجاني
1. افتح **https://console.groq.com**
2. سجّل بحسابك (Google أو Email)
3. من القائمة الجانبية اختر **"API Keys"**
4. اضغط **"Create API Key"**
5. انسخ المفتاح (يبدأ بـ `gsk_`)

---

## الخطوة 2 — إعداد قاعدة البيانات
افتح phpMyAdmin أو MySQL وشغّل ملف `setup.sql`

---

## الخطوة 3 — تعديل config.php
```php
define('DB_USER', 'root');         // مستخدم MySQL
define('DB_PASS', '');             // كلمة مرور MySQL
define('GROQ_KEY', 'gsk_...');     // مفتاح Groq
```

---

## الخطوة 4 — إضافة محتوى قاعدة المعرفة
عدّل `seed_knowledge.php` وأضف نصوصك، ثم:
```bash
php seed_knowledge.php
```

---

## الخطوة 5 — تشغيل المشروع
```bash
php -S localhost:8000
```
ثم افتح: **http://localhost:8000**

---

## النماذج المستخدمة
| الغرض | النموذج |
|---|---|
| المحادثة | `llama-3.3-70b-versatile` |
| الـ Embeddings | `nomic-embed-text-v1.5` |

## License
Apache 2.0
