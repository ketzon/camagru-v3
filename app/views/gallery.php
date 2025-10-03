<?php
require __DIR__ . '/partials/header.php';
?>
<h1>Galerie</h1>




<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;">
    <?php foreach ($rows as $r): ?>
    <a href="/image/<?= (int)$r['id'] ?>" style="text-decoration:none;">
        <img src="/img/<?= (int)$r['id'] ?>.png" alt="img <?= (int)$r['id'] ?>" style="width:100%;height:auto;display:block;border:1px solid #ddd;border-radius:8px;">
        <small>#<?= (int)$r['id'] ?> — <?= htmlspecialchars($r['created_at']) ?></small>
    </a>
    <?php endforeach; ?>
</div>

<?php
echo '<nav style="margin-top:12px;">';
for ($i=1; $i<=$pages; $i++) {
    if ($i === $page) echo "<strong>[$i]</strong> ";
    else echo '<a href="/gallery?page='.$i.'&size='. $size .'">'.$i.'</a> ';
}
echo '</nav>';
echo '<br/>';
require __DIR__ . '/partials/footer.php'; ?>
