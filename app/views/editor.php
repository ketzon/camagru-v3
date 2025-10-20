<?php 
require __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../DB.php';
?>
<h1>editor</h1>

<?php
$last = [];
if ($uid = auth_id()) {
    $pdo = DB::pdo();
    $stmt  = $pdo->prepare("SELECT id, created_at FROM images WHERE user_id = ? ORDER BY id DESC LIMIT 5");
    $stmt->execute([$uid]);
    $last = $stmt->fetchAll();
}
?>

<div style="display:flex; gap:16px; align-items:flex-start;">
    <div style="flex: 1">
        <select id="overlay">
            <option value="none">overlay</option>
            <option value="/overlays/adn.png">Adn</option>
            <option value="/overlays/mask.png">Mask</option>
            <option value="/overlays/water_flower.png">Flower</option>
            <option value="/overlays/cloud.png">Cloud</option>
            <option value="/overlays/kawaii.png">Kawaii</option>
            <option value="/overlays/beard.png">Beard</option>
            <option value="/overlays/cat_claws.png">Cat Claws</option>
            <option value="/overlays/light.png">Lighting</option>
            <option value="/overlays/bubbles.png">Bubbles</option>
            <option value="/overlays/anime_hair.png">Anime Hair</option>
        </select>
        <video id="video" autoplay playsinline></video> 
        <canvas id="canvas"></canvas>
        <button id="snap" disabled>take picture</button>
        <p>upload picture</p> 
        <input type="file" id="upload" accept="image/*"/>
        <form id="send" method="post" action="/compose" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?php require_once __DIR__.'/../csrf.php'; echo htmlspecialchars(Csrf::getToken()); ?>">
            <input type="hidden" name="data_url" id="data_url">
            <input type="hidden" name="overlay_path" id="overlay_path">
            <button type="submit">compose server side</button>
        </form>
    </div>

    <aside style="width:150px;">
        <h3 style="margin:0 0 8px;font-size:14px;">last 5 </h3>
        <?php if ($last): ?>
        <?php foreach ($last as $im): ?>
        <a href="/image/<?= (int)$im['id'] ?>" style="display:block; width:100%; margin-bottom:15px;">
            <img src="/img/<?= (int)$im['id'] ?>.png"
                alt="#<?= (int)$im['id'] ?>"
                style="width:100%;height:100px;object-fit:cover;border:4px solid #ddd;border-radius:6px;display:block;">
        </a>
        <?php endforeach; ?>
        <?php else: ?>
        <p style="color:red">no shots</p>
        <?php endif; ?>
    </aside>
</div>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const overlay = document.getElementById('overlay');
const data_url = document.getElementById('data_url');
const overlay_path = document.getElementById('overlay_path');

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
    } catch (err) { alert("branche ta cam!!!"); }
}

overlay.addEventListener('change', () => {
    snap.disabled = (overlay.value === 'none');
});

document.getElementById('snap').addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
});

document.getElementById('upload').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (!file) return;
    const img = new Image();
    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
    };
    img.src = URL.createObjectURL(file);
});

overlay.addEventListener('change', () => { overlay_path.value = overlay.value; });

document.getElementById('send').addEventListener('submit', () => {
    data_url.value = canvas.toDataURL('image/png');
});

startCamera();
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
