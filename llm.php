<?php
/**
 * استدعاء نموذج LLaMA 3.3 عبر Groq API
 */
require_once 'config.php';

function callLLM(string $userMessage, string $context): string {
    $systemPrompt = 'أنت مساعد ذكاء اصطناعي مفيد. أجب على الأسئلة بناءً على السياق المُقدَّم فقط. '
                  . 'إذا لم يكن السياق كافياً، قل ذلك بوضوح. أجب دائماً بنفس لغة المستخدم.';

    $userPrompt = "السياق:\n{$context}\n\nسؤال المستخدم: {$userMessage}";

    $payload = json_encode([
        'model'       => CHAT_MODEL,
        'messages'    => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user',   'content' => $userPrompt],
        ],
        'max_tokens'  => 800,
        'temperature' => 0.7,
    ]);

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . GROQ_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false || $httpCode !== 200) {
        return 'عذراً، حدث خطأ في الاتصال بـ Groq. (HTTP ' . $httpCode . ') — ' . $raw;
    }

    $response = json_decode($raw, true);
    return $response['choices'][0]['message']['content'] ?? 'لم أتمكن من الحصول على رد.';
}
