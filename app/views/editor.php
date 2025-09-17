<h1>editor</h1>

<select id="overlay">
    <option value="none">overlay</option>
    <option value="../../public/overlays/hat.png">Hat</option>
    <option value="../../public/overlays/water_flower.png">Flower</option>
</select>


<video id="video" autoplay playsinline></video> 
<canvas id="canvas"></canvas>
<button id="snap">take picture</button>

//upload
<p>upload picture</p> 
<input  type="file"
        id="upload"
        accept="image/*"
/>

<form id="send" method="post" action="/compose" enctype="multipart/form-data">
  <input type="hidden" name="_csrf" value="<?php require_once __DIR__.'/../csrf.php'; echo htmlspecialchars(Csrf::getToken()); ?>">
  <input type="hidden" name="data_url" id="data_url">
  <input type="hidden" name="overlay_path" id="overlay_path">
  <button type="submit">compose server side</button>
</form>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const overlay = document.getElementById('overlay');
const data_url = document.getElementById('data_url');
const overlay_path = document.getElementById('overlay_path');



//inject stream in object video
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
    } catch (err) {
        alert("branche ta cam!!!");
    }
}

//take a screenshot on click
document.getElementById('snap').addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
});

//upload
document.getElementById('upload').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (!file) return ;
    const img = new Image();
    img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.width;
        ctx.drawImage(img, 0, 0);
    }
    img.src = URL.createObjectURL(file);
});
//change
overlay.addEventListener('change', () => {
    overlay_path.value = overlay.value
});

//send
document.getElementById('send').addEventListener('submit', () => {
    data_url.value = canvas.toDataURL('image/png');
});

startCamera();
</script>
