<?php
/**
 * البحث الدلالي (Semantic Search) باستخدام MySQL
 *
 * بما أن MySQL لا تدعم Vector Search مدمجاً (إلا في الإصدار 9+)،
 * نجلب جميع السجلات ونحسب cosine similarity في PHP.
 *
 * للإنتاج على نطاق واسع: استخدم MySQL 9 أو pgvector مع PostgreSQL.
 */
require_once 'config.php';

/**
 * يحسب Cosine Similarity بين متجهَين
 */
function cosineSimilarity(array $a, array $b): float {
    $dot = 0.0; $normA = 0.0; $normB = 0.0;
    $len = min(count($a), count($b));
    for ($i = 0; $i < $len; $i++) {
        $dot   += $a[$i] * $b[$i];
        $normA += $a[$i] * $a[$i];
        $normB += $b[$i] * $b[$i];
    }
    $denom = sqrt($normA) * sqrt($normB);
    return $denom > 0 ? $dot / $denom : 0.0;
}

/**
 * يُعيد أقرب $topK وثائق للـ embedding المُدخَل
 */
function semanticSearch(array $queryEmbedding, int $topK = 5): array {
    $pdo  = getDB();
    $rows = $pdo->query('SELECT id, content, embedding FROM knowledge')->fetchAll();

    $scored = [];
    foreach ($rows as $row) {
        $vec = json_decode($row->embedding, true);
        if (empty($vec)) continue;          // تجاهل السجلات بدون embedding
        $score = cosineSimilarity($queryEmbedding, $vec);
        $scored[] = ['score' => $score, 'content' => $row->content];
    }

    // ترتيب تنازلي حسب التشابه
    usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

    return array_slice($scored, 0, $topK);
}
