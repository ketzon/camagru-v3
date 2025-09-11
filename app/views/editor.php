<h1>Éditeur</h1>
<video id="video" autoplay playsinline></video>
<canvas id="canvas"></canvas>
<button id="snap">prendre la photo</button>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
    } catch (err) {
        alert("branche ta cam!!!");
    }
}

document.getElementById('snap').addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
});

startCamera();
</script>
