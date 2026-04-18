<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taku — Sedang Maintenance</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300&family=DM+Sans:wght@300;400;500&display=swap');
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

    body{
        font-family:'DM Sans',sans-serif;
        background:#f5f1e8;
        color:#2c1810;
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        position:relative;
    }

    .bg-circle{
        position:fixed;
        border-radius:50%;
        pointer-events:none;
        animation:float 8s ease-in-out infinite;
    }
    .bg-circle:nth-child(1){
        width:600px;height:600px;
        background:radial-gradient(circle,rgba(201,106,61,.06) 0%,transparent 70%);
        top:-200px;right:-200px;
        animation-delay:0s;
    }
    .bg-circle:nth-child(2){
        width:400px;height:400px;
        background:radial-gradient(circle,rgba(107,124,92,.06) 0%,transparent 70%);
        bottom:-100px;left:-100px;
        animation-delay:-3s;
    }
    .bg-circle:nth-child(3){
        width:300px;height:300px;
        background:radial-gradient(circle,rgba(201,169,110,.08) 0%,transparent 70%);
        top:50%;left:50%;transform:translate(-50%,-50%);
        animation-delay:-6s;
    }
    @keyframes float{
        0%,100%{transform:translateY(0) scale(1);}
        50%{transform:translateY(-20px) scale(1.05);}
    }

    .container{
        text-align:center;
        position:relative;
        z-index:10;
        padding:40px 20px;
        max-width:520px;
    }

    .logo-wrap{
        margin-bottom:40px;
        position:relative;
        display:inline-block;
    }
    .logo{
        font-family:'Cormorant Garamond',serif;
        font-weight:300;
        font-size:48px;
        color:#2c1810;
        letter-spacing:.22em;
        text-transform:uppercase;
        display:flex;
        align-items:center;
        gap:12px;
        justify-content:center;
    }
    .logo-dot{
        width:8px;height:8px;
        background:#c96a3d;
        border-radius:50%;
        animation:pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot{
        0%,100%{transform:scale(1);opacity:1;}
        50%{transform:scale(1.4);opacity:.7;}
    }

    .progress-wrap{
        width:200px;height:1px;
        background:rgba(44,24,16,.12);
        margin:0 auto 40px;
        position:relative;
        overflow:hidden;
    }
    .progress-bar{
        position:absolute;
        top:0;left:-100%;
        width:100%;height:100%;
        background:linear-gradient(90deg,transparent,#c96a3d,transparent);
        animation:progress-scan 2s ease-in-out infinite;
    }
    @keyframes progress-scan{
        0%{left:-100%;}
        100%{left:100%;}
    }

    .title{
        font-family:'Cormorant Garamond',serif;
        font-weight:300;
        font-size:32px;
        color:#2c1810;
        margin-bottom:12px;
        letter-spacing:.02em;
    }
    .title em{
        font-style:italic;
        color:#c96a3d;
    }

    .desc{
        font-size:14px;
        color:rgba(44,24,16,.55);
        line-height:1.8;
        margin-bottom:36px;
    }

    .countdown-wrap{
        display:flex;
        gap:20px;
        justify-content:center;
        margin-bottom:40px;
    }
    .countdown-item{
        text-align:center;
    }
    .countdown-num{
        font-family:'Cormorant Garamond',serif;
        font-size:36px;
        font-weight:300;
        color:#2c1810;
        line-height:1;
        display:block;
        min-width:60px;
    }
    .countdown-label{
        font-size:9px;
        letter-spacing:.16em;
        text-transform:uppercase;
        color:rgba(44,24,16,.4);
        margin-top:4px;
        display:block;
    }
    .countdown-sep{
        font-family:'Cormorant Garamond',serif;
        font-size:32px;
        color:rgba(44,24,16,.2);
        align-self:flex-start;
        padding-top:4px;
    }

    .status-badge{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:rgba(201,106,61,.08);
        border:.5px solid rgba(201,106,61,.25);
        border-radius:100px;
        padding:8px 18px;
        font-size:11px;
        letter-spacing:.1em;
        text-transform:uppercase;
        color:#c96a3d;
        margin-bottom:32px;
    }
    .status-dot-anim{
        width:6px;height:6px;
        border-radius:50%;
        background:#c96a3d;
        animation:blink 1.5s ease-in-out infinite;
    }
    @keyframes blink{
        0%,100%{opacity:1;}
        50%{opacity:.2;}
    }

    .notify-wrap{
        display:flex;
        align-items:center;
        gap:0;
        max-width:340px;
        margin:0 auto;
        border:.5px solid rgba(44,24,16,.15);
        border-radius:10px;
        overflow:hidden;
        background:white;
    }
    .notify-input{
        flex:1;
        padding:12px 16px;
        border:none;
        outline:none;
        font-size:13px;
        color:#2c1810;
        font-family:'DM Sans',sans-serif;
        background:transparent;
    }
    .notify-input::placeholder{color:rgba(44,24,16,.3);}
    .notify-btn{
        padding:12px 18px;
        background:#2c1810;
        color:#f5f1e8;
        border:none;
        cursor:pointer;
        font-size:11px;
        letter-spacing:.1em;
        text-transform:uppercase;
        font-family:'DM Sans',sans-serif;
        transition:background .2s;
        white-space:nowrap;
    }
    .notify-btn:hover{background:#c96a3d;}
    .notify-success{
        font-size:12px;
        color:#6b7c5c;
        margin-top:10px;
        display:none;
    }

    .social-wrap{
        display:flex;
        gap:12px;
        justify-content:center;
        margin-top:32px;
    }
    .social-link{
        width:36px;height:36px;
        border-radius:50%;
        border:.5px solid rgba(44,24,16,.12);
        display:flex;align-items:center;justify-content:center;
        color:rgba(44,24,16,.45);
        text-decoration:none;
        transition:all .2s;
    }
    .social-link:hover{border-color:#c96a3d;color:#c96a3d;background:rgba(201,106,61,.06);}

    .footer-note{
        position:fixed;
        bottom:20px;left:0;right:0;
        text-align:center;
        font-size:11px;
        color:rgba(44,24,16,.3);
        letter-spacing:.06em;
    }

    @media(max-width:480px){
        .logo{font-size:36px;}
        .title{font-size:24px;}
        .countdown-num{font-size:28px;min-width:46px;}
    }
    </style>
</head>
<body>

<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>

<div class="container">
    <div class="logo-wrap">
        <div class="logo">
            TAKU <div class="logo-dot"></div>
        </div>
    </div>

    <div class="progress-wrap">
        <div class="progress-bar"></div>
    </div>

    <span class="status-badge">
        <span class="status-dot-anim"></span>
        Sedang Maintenance
    </span>

    <h1 class="title">Kami sedang <em>berbenah</em></h1>
    <p class="desc">
        Website Taku sedang dalam proses pembaruan, sabar yaa wok.
        Kami akan segera kembali.
    </p>

    <div class="countdown-wrap" id="countdown">
        <div class="countdown-item">
            <span class="countdown-num" id="cd-h">--</span>
            <span class="countdown-label">Jam</span>
        </div>
        <span class="countdown-sep">:</span>
        <div class="countdown-item">
            <span class="countdown-num" id="cd-m">--</span>
            <span class="countdown-label">Menit</span>
        </div>
        <span class="countdown-sep">:</span>
        <div class="countdown-item">
            <span class="countdown-num" id="cd-s">--</span>
            <span class="countdown-label">Detik</span>
        </div>
    </div>

    <div class="notify-wrap">
        <input type="email" class="notify-input" id="notifyEmail" placeholder="Email kamu untuk notifikasi">
        <button class="notify-btn" onclick="submitNotify()">Beritahu Saya</button>
    </div>
    <p class="notify-success" id="notifySuccess">✓ Kami akan memberitahu kamu saat website aktif kembali!</p>

    <div class="social-wrap">
        <a href="https://wa.me/6281324683769" target="_blank" class="social-link" title="WhatsApp">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
        </a>
        <a href="mailto:infotaku.official@gmail.com" class="social-link" title="Email">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </a>
    </div>
</div>

<p class="footer-note">© {{ date('Y') }} Taku Marketplace · Semua hak dilindungi</p>

<script>
const retrySeconds = {{ file_exists(storage_path('framework/down'))
    ? (json_decode(file_get_contents(storage_path('framework/down')), true)['retry'] ?? 600)
    : 600 }};

const downSince = {{ file_exists(storage_path('framework/down'))
    ? filemtime(storage_path('framework/down')) * 1000
    : round(microtime(true) * 1000) }};

const targetTime = new Date(downSince + (retrySeconds * 1000));

function updateCountdown() {
    const now = new Date().getTime();
    const diff = targetTime - now;

    if (diff <= 0) {
        document.getElementById('cd-h').textContent = '00';
        document.getElementById('cd-m').textContent = '00';
        document.getElementById('cd-s').textContent = '00';

        setTimeout(() => location.reload(), 3000);
        return;
    }

    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);

    document.getElementById('cd-h').textContent = String(h).padStart(2,'0');
    document.getElementById('cd-m').textContent = String(m).padStart(2,'0');
    document.getElementById('cd-s').textContent = String(s).padStart(2,'0');
}

updateCountdown();
setInterval(updateCountdown, 1000);

function submitNotify() {
    const email = document.getElementById('notifyEmail').value;
    if (!email || !email.includes('@')) return;

    document.getElementById('notifySuccess').style.display = 'block';
    document.getElementById('notifyEmail').value = '';
    document.querySelector('.notify-btn').textContent = '✓ Tersimpan';
}
</script>

</body>
</html>
