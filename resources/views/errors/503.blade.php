{{-- resources/views/errors/503.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Taku — Sedang Maintenance</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Inter:wght@300;400;500&display=swap');

*{margin:0;padding:0;box-sizing:border-box;}

body{
    background:#f5f1e8;
    color:#2c1810;
    font-family:'Inter',sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
    position:relative;
}

.bg1,.bg2,.bg3{
    position:fixed;
    border-radius:50%;
    filter:blur(10px);
    opacity:.5;
    animation:float 8s ease-in-out infinite;
}
.bg1{
    width:420px;height:420px;
    background:rgba(201,106,61,.08);
    top:-100px;right:-100px;
}
.bg2{
    width:300px;height:300px;
    background:rgba(107,124,92,.08);
    bottom:-80px;left:-80px;
    animation-delay:-3s;
}
.bg3{
    width:220px;height:220px;
    background:rgba(201,169,110,.10);
    top:50%;left:50%;
    transform:translate(-50%,-50%);
    animation-delay:-6s;
}

@keyframes float{
    0%,100%{transform:translateY(0);}
    50%{transform:translateY(-18px);}
}

.wrap{
    width:100%;
    max-width:560px;
    text-align:center;
    padding:40px 24px;
    position:relative;
    z-index:10;
}

.logo{
    font-family:'Cormorant Garamond',serif;
    font-size:52px;
    letter-spacing:.22em;
    font-weight:300;
    margin-bottom:20px;
}

.dot{
    width:8px;height:8px;
    border-radius:50%;
    background:#c96a3d;
    display:inline-block;
    margin-left:8px;
    animation:pulse 1.5s infinite;
}

@keyframes pulse{
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:.4;transform:scale(1.4);}
}

.line{
    width:180px;
    height:1px;
    background:rgba(44,24,16,.12);
    margin:0 auto 30px;
    position:relative;
    overflow:hidden;
}

.line::after{
    content:'';
    position:absolute;
    width:80px;height:100%;
    background:linear-gradient(90deg,transparent,#c96a3d,transparent);
    animation:scan 2s linear infinite;
}

@keyframes scan{
    from{left:-80px;}
    to{left:180px;}
}

.badge{
    display:inline-flex;
    gap:8px;
    align-items:center;
    border:1px solid rgba(201,106,61,.18);
    padding:8px 16px;
    border-radius:999px;
    font-size:11px;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:#c96a3d;
    margin-bottom:24px;
}

.badge span{
    width:6px;height:6px;
    border-radius:50%;
    background:#c96a3d;
    animation:pulse 1.5s infinite;
}

h1{
    font-family:'Cormorant Garamond',serif;
    font-size:38px;
    font-weight:300;
    margin-bottom:14px;
}

h1 em{
    color:#c96a3d;
    font-style:italic;
}

p.desc{
    font-size:14px;
    color:rgba(44,24,16,.58);
    line-height:1.8;
    max-width:470px;
    margin:0 auto 36px;
}

.countdown{
    display:flex;
    justify-content:center;
    gap:18px;
    margin-bottom:32px;
}

.box{
    min-width:78px;
}

.num{
    font-family:'Cormorant Garamond',serif;
    font-size:42px;
    line-height:1;
}

.label{
    margin-top:6px;
    font-size:10px;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:rgba(44,24,16,.4);
}

.sep{
    font-size:34px;
    opacity:.2;
    padding-top:2px;
}

.note{
    font-size:12px;
    color:rgba(44,24,16,.42);
    margin-top:10px;
}

.footer{
    position:fixed;
    left:0;right:0;bottom:20px;
    text-align:center;
    font-size:11px;
    color:rgba(44,24,16,.28);
}

@media(max-width:480px){
    .logo{font-size:40px;}
    h1{font-size:28px;}
    .num{font-size:32px;}
    .box{min-width:62px;}
}
</style>
</head>
<body>

<div class="bg1"></div>
<div class="bg2"></div>
<div class="bg3"></div>

<div class="wrap">

    <div class="logo">
        TAKU <span class="dot"></span>
    </div>

    <div class="line"></div>

    <div class="badge">
        <span></span> Sedang Maintenance
    </div>

    <h1>Kami sedang <em>berbenah</em></h1>

    <p class="desc">
        Website sedang diperbarui, sabar ya wok.
        Kami akan kembali online setelah proses selesai.
    </p>

    <div class="countdown">
        <div class="box">
            <div class="num" id="h">00</div>
            <div class="label">Jam</div>
        </div>

        <div class="sep">:</div>

        <div class="box">
            <div class="num" id="m">00</div>
            <div class="label">Menit</div>
        </div>

        <div class="sep">:</div>

        <div class="box">
            <div class="num" id="s">00</div>
            <div class="label">Detik</div>
        </div>
    </div>

    <div class="note">
        Halaman akan aktif otomatis setelah maintenance selesai.
    </div>

</div>

<div class="footer">
    © {{ date('Y') }} Taku Marketplace · Semua hak dilindungi
</div>

<script>

const retrySeconds = {{ request()->header('Retry-After', 3600) }};

const targetTime = new Date(Date.now() + (retrySeconds * 1000));

function updateCountdown(){
    const now = new Date();
    const diff = targetTime - now;

    if(diff <= 0){
        document.getElementById('h').textContent = '00';
        document.getElementById('m').textContent = '00';
        document.getElementById('s').textContent = '00';

        setTimeout(() => location.reload(), 3000);
        return;
    }

    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);

    document.getElementById('h').textContent = String(h).padStart(2,'0');
    document.getElementById('m').textContent = String(m).padStart(2,'0');
    document.getElementById('s').textContent = String(s).padStart(2,'0');
}

updateCountdown();
setInterval(updateCountdown,1000);
</script>

</body>
</html>
