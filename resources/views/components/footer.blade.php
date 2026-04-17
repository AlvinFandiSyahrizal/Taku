
<style>
.footer-title {
    font-size:11px;
    letter-spacing:0.18em;
    text-transform:uppercase;
    margin-bottom:16px;
    color:rgba(240,235,224,0.5);
}

.footer-list {
    list-style:none;
    padding:0;
    margin:0;
}

.footer-list li {
    margin-bottom:10px;
}

.footer-list a {
    text-decoration:none;
    color:rgba(240,235,224,0.75);
    font-size:13px;
    transition:all 0.25s ease;
}

.footer-list a:hover {
    color:#F5F1E8;
    letter-spacing:0.03em;
}

.social-icon {
    color:rgba(240,235,224,0.6);
    transition:all 0.25s ease;
}

.social-icon:hover {
    color:#F5F1E8;
    transform:translateY(-2px);
}

</style>

<footer style="
    background:#1F3D2B;
    color:#F5F1E8;
    padding:80px 20px 40px;
    font-family:'DM Sans', sans-serif;
">

    <div style="
        max-width:1100px;
        margin:auto;
        display:grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        gap:40px;
    ">

        {{-- BRAND --}}
        <div>
            <h2 style="
                font-family:'Cormorant Garamond', serif;
                font-size:28px;
                letter-spacing:1px;
                margin-bottom:12px;
            ">
                Taku
            </h2>

            <p style="
                font-size:13px;
                line-height:1.7;
                color:rgba(240,235,224,0.65);
                max-width:260px;
            ">
                Curated pieces from selected merchants. 
                Designed for simplicity, crafted for everyday elegance.
            </p>

            {{-- Social --}}
            <div style="
                display:flex;
                gap:14px;
                margin-top:20px;
            ">

                <a href="#" target="_blank" class="social-icon">
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

                <a href="#" target="_blank" class="social-icon">
                    <!-- TikTok -->
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 3c.5 2.5 2.5 4.5 5 5v3c-1.7 0-3.3-.5-4.7-1.5v5.5a6 6 0 11-6-6c.3 0 .6 0 .9.1v3.1c-.3-.1-.6-.2-.9-.2a3 3 0 103 3V3h2.7z"/>
                    </svg>
                </a>

            </div>
        </div>

        {{-- SHOP --}}
        <div>
            <p class="footer-title">SHOP</p>
            <ul class="footer-list">
                <li><a href="/">Home</a></li>
                <li><a href="/shop">All Products</a></li>
                <li><a href="#">Collections</a></li>
            </ul>
        </div>

        {{-- SUPPORT --}}
        <div>
            <p class="footer-title">SUPPORT</p>
            <ul class="footer-list">
                <li><a href="#">FAQ</a></li>
                <li><a href="#">How to Order</a></li>
                <li><a href="#">Returns</a></li>
            </ul>
        </div>

        {{-- LEGAL --}}
        <div>
            <p class="footer-title">LEGAL</p>
            <ul class="footer-list">
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

    </div>

    {{-- Bottom --}}
    <div style="
        max-width:1100px;
        margin:60px auto 0;
        padding-top:20px;
        border-top:0.5px solid rgba(240,235,224,0.1);
        display:flex;
        justify-content:space-between;
        font-size:12px;
        color:rgba(240,235,224,0.5);
    ">
        <span>© 2026 Taku. All rights reserved.</span>
        <span>Indonesia</span>
    </div>

</footer>