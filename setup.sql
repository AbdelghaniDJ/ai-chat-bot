-- ══════════════════════════════════════════════════════════
--  إنشاء قاعدة البيانات وجداول المشروع
--  نفّذ هذا الملف مرة واحدة فقط قبل تشغيل التطبيق
-- ══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS chatbot_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE chatbot_db;

-- ── جدول المعرفة (Knowledge Base) ──────────────────────────
-- يحتوي على النصوص والـ embeddings المخزّنة كـ JSON
CREATE TABLE IF NOT EXISTS knowledge (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    content   TEXT         NOT NULL,
    embedding JSON         NOT NULL,   -- الـ vector مخزّن كمصفوفة JSON
    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── جدول سجل المحادثات (اختياري) ───────────────────────────
CREATE TABLE IF NOT EXISTS chat_history (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_msg   TEXT       NOT NULL,
    bot_reply  TEXT       NOT NULL,
    created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ══════════════════════════════════════════════════════════
--  بيانات تجريبية — استبدلها بمحتواك الفعلي
-- ══════════════════════════════════════════════════════════
-- (سيتم حساب الـ embeddings عبر السكريبت seed_knowledge.php)
-- يمكنك إضافة سجلات يدوياً بدون embedding أولاً ثم تشغيل السكريبت

INSERT INTO knowledge (content, embedding) VALUES
('مرحباً! أنا بوت ذكاء اصطناعي يجيب على أسئلتك.', '[]'),
('يمكنني مساعدتك في الإجابة على الأسئلة المتعلقة بمحتوى قاعدة المعرفة.', '[]');
