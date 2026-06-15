<?php
function createEmbedding(string $text): array {
    // تنظيف النص بدون mb_ functions
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s\x{0600}-\x{06FF}]/u', ' ', $text);
    
    // تقسيم إلى كلمات
    $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
    
    if (empty($words)) return array_fill(0, 128, 0.0);
    
    $vector = array_fill(0, 128, 0.0);
    
    foreach ($words as $word) {
        $idx = abs(crc32($word)) % 128;
        $vector[$idx] += 1.0;
    }
    
    // bigrams
    for ($i = 0; $i < count($words) - 1; $i++) {
        $bigram = $words[$i] . '_' . $words[$i+1];
        $idx = abs(crc32($bigram)) % 128;
        $vector[$idx] += 0.5;
    }
    
    // تطبيع
    $norm = sqrt(array_sum(array_map(fn($v) => $v * $v, $vector)));
    if ($norm > 0) {
        $vector = array_map(fn($v) => $v / $norm, $vector);
    }
    
    return $vector;
}
