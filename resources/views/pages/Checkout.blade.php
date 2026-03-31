@extends('layouts.app')

@section('content')

@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');

.co-wrap {
    max-width: 1000px;
    margin: 56px auto 80px;
    padding: 0 32px;
    font-family: 'DM Sans', sans-serif;
    display: flex;
    gap: 48px;
    align-items: flex-start;
    flex-wrap: wrap;
}

/* FORM */
.co-form-section { flex: 1; min-width: 300px; }
.co-label-top { font-size: 10px; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a96e; margin-bottom: 8px; }
.co-title { font-family: 'Cormorant Garamond', serif; font-weight: 400; font-size: 36px; color: #0b2a4a; margin-bottom: 32px; }

.co-field { margin-bottom: 20px; }
.co-field-label {
    display: block; font-size: 11px; letter-spacing: 0.12em;
    text-transform: uppercase; color: rgba(11,42,74,0.5);
    margin-bottom: 8px;
}
.co-input {
    width: 100%; padding: 12px 14px;
    border: 0.5px solid rgba(11,42,74,0.18);
    border-radius: 8px; font-size: 14px;
    color: #0b2a4a; font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s;
    background: white;
}
.co-input:focus { border-color: #c9a96e; }
.co-textarea {
    width: 100%; padding: 12px 14px;
    border: 0.5px solid rgba(11,42,74,0.18);
    border-radius: 8px; font-size: 14px;
    color: #0b2a4a; font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s;
    resize: vertical; min-height: 90px; background: white;
}
.co-textarea:focus { border-color: #c9a96e; }
.co-input::placeholder, .co-textarea::placeholder { color: rgba(11,42,74,0.25); }

.co-error { font-size: 12px; color: #c0392b; margin-top: 4px; }

.co-submit-btn {
    width: 100%; padding: 15px;
    background: #25d366; color: white;
    border: none; border-radius: 8px;
    font-size: 12px; letter-spacing: 0.14em;
    text-transform: uppercase; font-weight: 500;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    margin-top: 8px; transition: background 0.2s, transform 0.15s;
}
.co-submit-btn:hover { background: #1ebe5d; transform: translateY(-1px); }

.co-back {
    display: block; text-align: center; margin-top: 14px;
    font-size: 11px; color: rgba(11,42,74,0.4);
    text-decoration: none; letter-spacing: 0.1em; text-transform: uppercase;
    transition: color 0.2s;
}
.co-back:hover { color: #0b2a4a; }

/* SUMMARY */
.co-summary {
    width: 300px; flex-shrink: 0;
    position: sticky; top: 84px;
    background: #f8f6f2;
    border-radius: 16px;
    padding: 28px 24px;
    border: 0.5px solid rgba(201,169,110,0.2);
}
.co-summary-title {
    font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase;
    color: rgba(11,42,74,0.4); margin-bottom: 20px;
}
.co-summary-item {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 12px; margin-bottom: 14px; padding-bottom: 14px;
    border-bottom: 0.5px solid rgba(11,42,74,0.07);
}
.co-summary-item:last-of-type { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.co-item-left { display: flex; align-items: center; gap: 10px; }
.co-item-img { width: 44px; height: 44px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
.co-item-name { font-size: 13px; font-weight: 500; color: #0b2a4a; }
.co-item-qty { font-size: 11px; color: rgba(11,42,74,0.4); margin-top: 2px; }
.co-item-price { font-size: 13px; font-weight: 500; color: #0b2a4a; white-space: nowrap; }

.co-summary-divider { height: 0.5px; background: rgba(11,42,74,0.1); margin: 16px 0; }
.co-total-row { display: flex; justify-content: space-between; align-items: baseline; }
.co-total-label { font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.4); }
.co-total-amount { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 400; color: #0b2a4a; }

@media (max-width: 700px) {
    .co-wrap { flex-direction: column-reverse; }
    .co-summary { width: 100%; position: static; }
}
</style>

<div class="co-wrap">

    <div class="co-form-section">
        <p class="co-label-top">Taku</p>
        <h1 class="co-title">{{ __('app.checkout_title') }}</h1>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="co-field">
                <label class="co-field-label">{{ __('app.checkout_name') }}</label>
                <input
                    type="text" name="name"
                    class="co-input"
                    value="{{ old('name') }}"
                    placeholder="John Doe"
                    required
                >
                @error('name') <p class="co-error">{{ $message }}</p> @enderror
            </div>

            <div class="co-field">
                <label class="co-field-label">{{ __('app.checkout_phone') }}</label>
                <input
                    type="text" name="phone"
                    class="co-input"
                    value="{{ old('phone') }}"
                    placeholder="08xxxxxxxxxx"
                    required
                >
                @error('phone') <p class="co-error">{{ $message }}</p> @enderror
            </div>

            <div class="co-field">
                <label class="co-field-label">{{ __('app.checkout_address') }}</label>
                <textarea
                    name="address"
                    class="co-textarea"
                    placeholder="Jl. Contoh No. 10, Kota, Provinsi"
                    required
                >{{ old('address') }}</textarea>
                @error('address') <p class="co-error">{{ $message }}</p> @enderror
            </div>

            <div class="co-field">
                <label class="co-field-label">{{ __('app.checkout_note') }}</label>
                <textarea
                    name="note"
                    class="co-textarea"
                    style="min-height:70px;"
                    placeholder="{{ __('app.checkout_note_ph') }}"
                >{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="co-submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                </svg>
                {{ __('app.checkout_submit') }}
            </button>

        </form>

        <a href="{{ route('cart.index') }}" class="co-back">← {{ __('app.checkout_back') }}</a>
    </div>

    <div class="co-summary">
        <p class="co-summary-title">{{ __('app.checkout_order') }}</p>

        @foreach($cart as $item)
        @php
            $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
            $sub = $price * $item['qty'];
        @endphp
        <div class="co-summary-item">
            <div class="co-item-left">
                <img src="{{ asset($item['image']) }}" class="co-item-img" alt="{{ $item['name'] }}">
                <div>
                    <p class="co-item-name">{{ $item['name'] }}</p>
                    <p class="co-item-qty">x{{ $item['qty'] }}</p>
                </div>
            </div>
            <p class="co-item-price">Rp {{ number_format($sub, 0, ',', '.') }}</p>
        </div>
        @endforeach

        <div class="co-summary-divider"></div>
        <div class="co-total-row">
            <p class="co-total-label">{{ __('app.checkout_total') }}</p>
            <p class="co-total-amount">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
    </div>

</div>

@endsection
