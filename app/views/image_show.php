<h1>Image #<?= (int)$image['id'] ?></h1>
<p><a href="/gallery">&larr; retour</a></p>

<img
    src="/img/<?= (int)$image['id'] ?>.png"
    alt="image"
    style="max-width:100%;height:auto;display:block;border:1px solid #ddd;border-radius:8px;"
>

<p>
    <?= (int)$likes ?> like<?= $likes > 1 ? 's' : '' ?>
    <?= !empty($liked) ? '(Already Liked)' : '' ?>
</p>

<?php if (auth_id()): ?>
<form method="post" action="/like" style="margin:8px 0;">
    <input type="hidden" name="_csrf" value="<?php require __DIR__.'/csrf.php'; echo htmlspecialchars(csrf::getToken()); ?>">
    <input type="hidden" name="image_id" value="<?= (int)$image['id'] ?>">
    <button type="submit"><?= !empty($liked) ? '(Already Liked)' : 'Like' ?></button>
</form>
<?php endif; ?>

<hr>

<h2>Comment</h2>

<?php if (auth_id()): ?>
<form method="post" action="/comment" style="margin-bottom:16px;">
    <input type="hidden" name="_csrf" value="<?php require __DIR__.'/csrf.php'; echo htmlspecialchars(csrf::getToken()); ?>">
    <input type="hidden" name="image_id" value="<?= (int)$image['id'] ?>">
    <textarea name="body" rows="3" cols="40" placeholder="Your comment..." required></textarea><br>
    <button type="submit">send</button>
</form>
<?php else: ?>
<p><a href="/login">login</a> to comment</p>
<?php endif; ?>

<ul style="list-style:none;padding:0;">
    <?php foreach ($comments as $c): ?>
    <?php
    $who = isset($c['username']) && $c['username'] !== ''
    ? $c['username']
    : ('User #'.(isset($c['user_id']) ? (int)$c['user_id'] : '?'));
    $created = $c['created_at'] ?? '';
    ?>
    <li style="margin:10px 0;padding:8px;border:1px solid #eee;border-radius:6px;">
        <strong><?= htmlspecialchars($who) ?></strong>
        <small><?= htmlspecialchars($created) ?></small>
        <div><?= nl2br(htmlspecialchars($c['body'] ?? '')) ?></div>
    </li>
    <?php endforeach; ?>
</ul>
