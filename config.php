<?php
/**
 * AI Chat Bot (PHP + MySQL + Groq + LLaMA)
 * License : Apache 2.0
 */

// ─── إعدادات MySQL ───────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // ← غيّر هذا إذا لزم
define('DB_PASS', '');              // ← كلمة مرور MySQL (فارغة في XAMPP)
define('DB_NAME', 'chatbot_db');    // ← اسم قاعدة البيانات

// ─── مفتاح Groq ──────────────────────────────────────────
define('GROQ_KEY', '');      // ← ضع مفتاحك هنا

// ─── نموذج المحادثة ──────────────────────────────────────
define('CHAT_MODEL', 'llama-3.3-70b-versatile');

// ─── اتصال قاعدة البيانات ────────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]);
    }
    return $pdo;
}
