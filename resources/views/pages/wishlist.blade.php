@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang','id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.wish-wrap{max-width:900px;margin:56px auto 80px;padding:0 24px;font-family:'DM Sans',sans-serif;}
.page-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:400;color:#0b2a4a;margin-bottom:32px;}
.wish-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:18px;}
.wish-card{border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;position:relative;}
.wish-card-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;}
.wish-card-info{padding:13px 15px 15px;}
.wish-card-store{font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.08em;text-transform:uppercase;margin-bottom:3px;}
.wish-card-name{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.wish-card-price{font-size:14px;color:#c9a96e;font-weight:500;}
.wish-remove{position:absolute;top:10px;right:10px;width:30px;height:30px;border-radius:50%;background:white;border:.5px solid rgba(11,42,74,.12);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;}
.wish-remove:hover{background:#c0392b;border-color:#c0392b;color:white;}
.wish-card-link{text-decoration:none;color:inherit;display:block;}
.empty-wrap{text-align:center;padding:80px 0;}
.empty-title{font-family:'Cormorant Garamond',serif;font-size:28px;color:rgba(11,42,74,.25);margin-bottom:8px;}
.empty-sub{font-size:13px;color:rgba(11,42,74,.35);margin-bottom:24px;}
.btn-shop{display:inline-block;padding:12px 28px;background:#0b2a4a;color:#f0ebe0;text-decoration:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;}

.wish-card.removing {
    animation: fadeOut 0.4s ease forwards;
}

@keyframes fadeOut {
    to {
        opacity: 0;
        transform: scale(0.9);
    }
}

</style>

<div class="wish-wrap">
    <p class="page-label">Taku</p>
    <h1 class="page-title">Wishlist</h1>

    @if(session('success'))
        <div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:12px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;">{{ session('success') }}</div>
    @endif

    @if($wishlists->isEmpty())
    <div class="empty-wrap">
        <p class="empty-title">Wishlist kosong</p>
        <p class="empty-sub">Belum ada produk yang kamu simpan.</p>
        <a href="{{ route('products') }}" class="btn-shop">Mulai Belanja</a>
    </div>
    @else
    <div class="wish-grid">
        @foreach($wishlists as $w)
        @php $p = $w->product; @endphp
        @if($p)
        <div class="wish-card">
            <a href="{{ route('product.show', $p->id) }}" class="wish-card-link">
                <img src="{{ asset($p->image ?? 'images/placeholder.jpg') }}" class="wish-card-img" alt="{{ $p->name }}">
                <div class="wish-card-info">
                    <p class="wish-card-store">{{ $p->store?->name ?? 'Taku Official' }}</p>
                    <p class="wish-card-name">{{ $p->name }}</p>
                    <div style="margin-top:4px;">
                        @if($p->hasDiscount())
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <p style="font-size:13px;color:#c0392b;font-weight:500;">
                                    {{ $p->getFinalPriceFormatted() }}
                                </p>

                                <p style="font-size:11px;color:#c9a96e;text-decoration:line-through;">
                                    {{ $p->getPriceFormatted() }}
                                </p>
                            </div>
                        @else
                            <p class="wish-card-price">
                                {{ $p->getFinalPriceFormatted() }}
                            </p>
                        @endif
                    </div>
                </div>
            </a>
            <form action="{{ route('wishlist.toggle', $p) }}" method="POST">
                @csrf
                <button type="submit" class="wish-remove" title="Hapus dari wishlist">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </form>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>

<script>
document.querySelectorAll('.wish-remove').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.preventDefault();

        const form = this.closest('form');
        const card = this.closest('.wish-card');

        card.classList.add('removing');

        try {
            await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            setTimeout(() => {
                card.remove();
            }, 350);

        } catch (err) {
            console.error(err);
        }
    });
});
</script>

@endsection
