<?php
/**
 * نقطة الدخول الرئيسية — تستقبل رسالة المستخدم وتُعيد رد البوت
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'embeddings.php';
require_once 'search.php';
require_once 'llm.php';

try {
    // ─── 1. استقبال الرسالة ──────────────────────────────────
    $userMessage = trim($_POST['message'] ?? '');
    if (empty($userMessage)) {
        echo json_encode(['reply' => 'من فضلك أدخل رسالة.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ─── 2. إنشاء Embedding للرسالة ─────────────────────────
    $embedding = createEmbedding($userMessage);

    // ─── 3. البحث الدلالي في قاعدة البيانات ─────────────────
    $results = semanticSearch($embedding, 5);

    // ─── 4. بناء السياق ─────────────────────────────────────
    $context = '';
    foreach ($results as $doc) {
        if ($doc['score'] > 0.3) {          // تجاهل النتائج ضعيفة الصلة
            $context .= $doc['content'] . "\n";
        }
    }
    if (empty(trim($context))) {
        $context = 'لا توجد معلومات متاحة في قاعدة المعرفة حول هذا الموضوع.';
    }

    // ─── 5. استدعاء نموذج LLM ───────────────────────────────
    $reply = callLLM($userMessage, $context);

    // ─── 6. حفظ المحادثة (اختياري) ──────────────────────────
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare('INSERT INTO chat_history (user_msg, bot_reply) VALUES (?, ?)');
        $stmt->execute([$userMessage, $reply]);
    } catch (Exception $e) {
        // لا نوقف التطبيق إذا فشل الحفظ
    }

    echo json_encode(['reply' => $reply], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    
    echo json_encode([
        'reply' => 'حدث خطأ: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
