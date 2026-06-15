<?php
/**
 * سكريبت لإضافة محتوى لقاعدة المعرفة وحساب embeddings له
 * شغّله من سطر الأوامر: php seed_knowledge.php
 *
 * عدّل مصفوفة $documents بالمحتوى الذي تريده
 */
require_once 'config.php';
require_once 'embeddings.php';

// ════════════════════════════════════════════════
//  ✏️  أضف هنا محتوى قاعدة المعرفة الخاصة بك
// ════════════════════════════════════════════════
$documents = [
    
];

$pdo = getDB();

// مسح البيانات القديمة (اختياري)
$pdo->exec('DELETE FROM knowledge');
echo "✓ تم مسح البيانات القديمة\n";

$stmt = $pdo->prepare('INSERT INTO knowledge (content, embedding) VALUES (?, ?)');

foreach ($documents as $i => $text) {
    echo "⏳ معالجة السجل " . ($i + 1) . "/" . count($documents) . " ...";
    try {
        $embedding = createEmbedding($text);
        $stmt->execute([$text, json_encode($embedding)]);
        echo " ✓\n";
        usleep(300_000); // انتظار 300ms لتجنب تجاوز حد الطلبات
    } catch (Exception $e) {
        echo " ✗ خطأ: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ تم بنجاح! أُضيف " . count($documents) . " سجل إلى قاعدة المعرفة.\n";
