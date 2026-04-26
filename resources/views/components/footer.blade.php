<style>
.footer-wrap{
    background:linear-gradient(180deg,#1F3D2B 0%, #183223 100%);
    color:#F5F1E8;
    padding:90px 20px 35px;
    font-family:'DM Sans',sans-serif;
}

.footer-grid{
    max-width:1180px;
    margin:auto;
    display:grid;
    grid-template-columns:1.5fr 1fr 1fr 1.2fr;
    gap:50px;
}

.footer-brand{
    font-family:'Cormorant Garamond',serif;
    font-size:34px;
    letter-spacing:1px;
    margin-bottom:14px;
}

.footer-desc{
    font-size:13px;
    line-height:1.8;
    color:rgba(245,241,232,.72);
    max-width:320px;
}

.footer-title{
    font-size:11px;
    letter-spacing:.22em;
    text-transform:uppercase;
    margin-bottom:18px;
    color:rgba(245,241,232,.48);
}

.footer-list{
    list-style:none;
    padding:0;
    margin:0;
}

.footer-list li{
    margin-bottom:12px;
}

.footer-list a{
    text-decoration:none;
    color:rgba(245,241,232,.78);
    font-size:13px;
    transition:.25s ease;
}

.footer-list a:hover{
    color:#fff;
    letter-spacing:.04em;
}

.footer-social{
    display:flex;
    gap:14px;
    margin-top:22px;
}

.footer-social a{
    width:38px;
    height:38px;
    border:1px solid rgba(255,255,255,.10);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:rgba(245,241,232,.72);
    transition:.25s ease;
}

.footer-social a:hover{
    transform:translateY(-3px);
    color:#fff;
    border-color:rgba(255,255,255,.28);
    background:rgba(255,255,255,.04);
}

.footer-newsletter{
    margin-top:10px;
}

.footer-newsletter p{
    font-size:13px;
    line-height:1.7;
    color:rgba(245,241,232,.72);
    margin-bottom:14px;
}

.footer-form{
    display:flex;
    border:1px solid rgba(255,255,255,.12);
    border-radius:999px;
    overflow:hidden;
    background:rgba(255,255,255,.03);
}

.footer-form input{
    flex:1;
    background:transparent;
    border:none;
    padding:13px 16px;
    color:#fff;
    font-size:13px;
    outline:none;
}

.footer-form input::placeholder{
    color:rgba(255,255,255,.38);
}

.footer-form button{
    border:none;
    background:#D7C3A3;
    color:#1B2E22;
    font-size:12px;
    font-weight:600;
    padding:0 18px;
    cursor:pointer;
    transition:.25s ease;
}

.footer-form button:hover{
    background:#e6d4b8;
}

.footer-bottom{
    max-width:1180px;
    margin:65px auto 0;
    padding-top:22px;
    border-top:1px solid rgba(255,255,255,.08);
    display:flex;
    justify-content:space-between;
    gap:20px;
    flex-wrap:wrap;
    font-size:12px;
    color:rgba(245,241,232,.48);
}

@media(max-width:980px){
    .footer-grid{
        grid-template-columns:1fr 1fr;
    }
}

@media(max-width:680px){
    .footer-grid{
        grid-template-columns:1fr;
        gap:34px;
    }

    .footer-bottom{
        flex-direction:column;
        gap:8px;
    }

    .footer-form{
        flex-direction:column;
        border-radius:18px;
    }

    .footer-form button{
        padding:14px;
    }
}
</style>

<footer class="footer-wrap">

    <div class="footer-grid">

        <!-- BRAND -->
        <div>
            <div class="footer-brand">Taku</div>

            <p class="footer-desc">
                Curated premium plants for modern spaces.
                Designed to bring calm, elegance, and life into every corner of your home.
            </p>

            <div class="footer-social">

                <a href="#" target="_blank">
                    <!-- IG -->
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7.75 2C4.574 2 2 4.574 2 7.75v8.5C2 19.426 4.574 22 7.75 22h8.5C19.426 22 22 19.426 22 16.25v-8.5C22 4.574 19.426 2 16.25 2h-8.5zm0 2h8.5C18.545 4 20 5.455 20 7.75v8.5C20 18.545 18.545 20 16.25 20h-8.5C5.455 20 4 18.545 4 16.25v-8.5C4 5.455 5.455 4 7.75 4zm9.25 1.5a1 1 0 100 2 1 1 0 000-2zM12 7a5 5 0 100 10 5 5 0 000-10z"/>
                    </svg>
                </a>

                <a href="#" target="_blank" class="social-icon">
                    <!-- FB -->
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 22v-9h3l1-4h-4V7c0-1 0-2 2-2h2V1h-3c-4 0-5 2-5 5v3H6v4h3v9h4z"/>
                    </svg>
                </a>

                <a href="#" target="_blank">
                    <!-- TikTok -->
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 3c.5 2.5 2.5 4.5 5 5v3c-1.7 0-3.3-.5-4.7-1.5v5.5a6 6 0 11-6-6c.3 0 .6 0 .9.1v3.1c-.3-.1-.6-.2-.9-.2a3 3 0 103 3V3h2.7z"/>
                    </svg>
                </a>

            </div>
        </div>

        <!-- SHOP -->
        <div>
            <div class="footer-title">Shop</div>

            <ul class="footer-list">
                <li><a href="/">Home</a></li>
                <li><a href="/shop">All Products</a></li>
                <li><a href="/shop?category=indoor">Indoor Plants</a></li>
                <li><a href="/shop?category=premium">Premium Collection</a></li>
            </ul>
        </div>

        <!-- SUPPORT -->
        <div>
            <div class="footer-title">Support</div>

            <ul class="footer-list">
                <li><a href="/faq">FAQ</a></li>
                <li><a href="/how-to-order">How To Order</a></li>
                <li><a href="/shipping">Shipping Info</a></li>
                <li><a href="/contact">Contact Us</a></li>
            </ul>
        </div>

        <!-- RIGHT COLUMN -->
        <div>
            <div class="footer-title">Need Assistance?</div>

            <div class="footer-newsletter">

                <p>
                    Need help choosing the right plant for your space?
                    Our team is ready to assist you with recommendations and orders.
                </p>

                <a href="https://wa.me/6289696256706" target="_blank" style="
                    display:inline-flex;
                    align-items:center;
                    gap:10px;
                    background:#D7C3A3;
                    color:#1B2E22;
                    text-decoration:none;
                    padding:14px 22px;
                    border-radius:999px;
                    font-size:13px;
                    font-weight:600;
                    transition:all .25s ease;
                    margin-top:6px;
                "
                onmouseover="this.style.transform='translateY(-2px)'"
                onmouseout="this.style.transform='translateY(0)'">

                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A11.86 11.86 0 0012.06 0C5.45 0 .06 5.39.06 12c0 2.11.55 4.18 1.6 6.02L0 24l6.17-1.62A11.93 11.93 0 0012.06 24C18.67 24 24 18.61 24 12c0-3.2-1.25-6.22-3.48-8.52zM12.06 21.8c-1.82 0-3.59-.49-5.14-1.42l-.37-.22-3.66.96.98-3.57-.24-.37A9.67 9.67 0 012.4 12c0-5.33 4.33-9.66 9.66-9.66 2.58 0 5 .99 6.82 2.82A9.57 9.57 0 0121.72 12c0 5.33-4.33 9.8-9.66 9.8zm5.3-7.24c-.29-.14-1.71-.84-1.98-.94-.26-.1-.46-.14-.65.14-.19.29-.75.94-.91 1.13-.17.19-.34.22-.63.08-.29-.14-1.22-.45-2.32-1.43-.86-.76-1.44-1.71-1.61-2-.17-.29-.02-.45.13-.59.13-.13.29-.34.43-.51.14-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.08-.14-.65-1.57-.89-2.15-.24-.58-.49-.5-.65-.5h-.56c-.19 0-.5.07-.77.36-.26.29-1 1-.1 2.44.91 1.44 2.31 2.83 3.97 3.84 1.67 1 2.31 1.11 3.15.94.52-.1 1.71-.7 1.95-1.37.24-.67.24-1.25.17-1.37-.07-.12-.26-.19-.55-.34z"/>
                    </svg>

                    Chat via WhatsApp
                </a>

                <p style="
                    margin-top:14px;
                    font-size:12px;
                    color:rgba(245,241,232,.52);
                ">
                    Fast response • Order support • Plant recommendations
                </p>

            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <span>© 2026 Taku. All rights reserved.</span>
        <span>Premium Plants • Based in Indonesia</span>
    </div>

</footer>
