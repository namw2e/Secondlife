@extends('layouts.app')
@section('title', $produk->nama_produk . ' — SecondLife')
@section('content')

<div class="detail-wrap">

  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <a href="{{ route('home') }}">Beranda</a> /
    <a href="{{ route('home', ['kategori' => $produk->id_kategori]) }}">{{ $produk->kategori->nama_kategori }}</a> /
    <span>{{ $produk->nama_produk }}</span>
  </div>

  <div class="detail-grid">

    <!-- KIRI: Foto -->
    <div class="detail-left">
      <div class="detail-foto">
        @if($produk->foto->isNotEmpty())
          <img src="{{ asset('storage/produk/'.$produk->foto->first()->foto) }}"
               alt="{{ $produk->nama_produk }}" class="foto-main" id="fotoMain">
          @if($produk->foto->count() > 1)
            <div class="foto-thumbs">
              @foreach($produk->foto as $foto)
                <img src="{{ asset('storage/produk/'.$foto->foto) }}"
                     alt="" class="foto-thumb"
                     onclick="document.getElementById('fotoMain').src=this.src">
              @endforeach
            </div>
          @endif
        @else
          <div class="foto-placeholder">📦</div>
        @endif
      </div>
    </div>

    <!-- KANAN: Detail & Aksi -->
    <div class="detail-right">
      <div class="pcard-cat" style="margin-bottom:6px">{{ $produk->kategori->nama_kategori }}</div>
      <h1 class="detail-nama">{{ $produk->nama_produk }}</h1>

      <div class="detail-badges">
        <span class="badge {{ $produk->kondisi === 'Baru' ? 'b-baru' : 'b-bekas' }}">{{ $produk->kondisi }}</span>
        <span class="badge {{ $produk->status === 'tersedia' ? 'b-tersedia' : 'b-habis' }}">
          {{ $produk->status === 'tersedia' ? 'Tersedia' : 'Habis' }}
        </span>
      </div>

      <div class="detail-harga">{{ $produk->formatHarga() }}</div>

      @if($produk->deskripsi)
        <p class="detail-desc">{!! nl2br(e($produk->deskripsi)) !!}</p>
      @endif

      <!-- PENJUAL -->
      <div class="seller-card">
        <div class="seller-avatar">{{ strtoupper(substr($produk->penjual->nama, 0, 2)) }}</div>
        <div>
          <strong>{{ $produk->penjual->nama }}</strong>
          <div style="font-size:12px;color:var(--text2)">📍 {{ $produk->penjual->lokasi ?? '-' }}</div>
          <div style="font-size:12px;color:#EF9F27">
            ⭐ {{ $produk->avgRating() }} ({{ $produk->review->count() }} ulasan)
          </div>
        </div>
      </div>

      <!-- TOMBOL BELI -->
      @if($produk->status === 'tersedia')
        @auth
          @if(auth()->user()->id_user !== $produk->penjual->id_user && !auth()->user()->isAdmin())
            <a class="btn btn-primary btn-full" href="{{ route('checkout', $produk->id_produk) }}">🛒 Pesan &amp; Atur COD</a>
          @elseif(auth()->user()->id_user === $produk->penjual->id_user)
            <a class="btn btn-ghost btn-full" href="{{ route('penjual.produk.edit', $produk->id_produk) }}">✏️ Edit Produk Saya</a>
          @endif
        @else
          <a class="btn btn-primary btn-full" href="{{ route('login') }}">Login untuk Membeli</a>
        @endauth
      @else
        <button class="btn btn-primary btn-full" disabled>Stok Habis</button>
      @endif

    </div><!-- /detail-right -->
  </div><!-- /detail-grid -->

  <!-- REVIEW SECTION -->
  <div class="review-section">
    <h2 class="review-title" style="font-size:18px;font-weight:700;margin-bottom:1rem">
      Ulasan Pembeli ({{ $produk->review->count() }})
    </h2>

    @if($produk->review->isNotEmpty())
      <div class="review-list">
        @foreach($produk->review as $rv)
          <div class="review-item">
            <div class="review-header" style="display:flex;justify-content:space-between;margin-bottom:4px">
              <strong class="review-name">{{ $rv->user->nama }}</strong>
              <div class="review-stars">{{ str_repeat('★', $rv->rating) }}{{ str_repeat('☆', 5 - $rv->rating) }}</div>
            </div>
            <p class="review-text">{{ $rv->komentar }}</p>
            @if($rv->balasan_penjual)
              <div class="balasan-penjual">
                💬 <strong>Penjual:</strong> {{ $rv->balasan_penjual }}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <p style="color:var(--text2);font-size:14px">Belum ada ulasan untuk produk ini.</p>
    @endif

    <!-- FORM ULASAN -->
    @guest
      <div style="background:var(--bg);border-radius:10px;padding:1rem;text-align:center;margin-top:1rem;font-size:14px;color:var(--text2)">
        <a href="{{ route('login') }}" style="color:var(--accent);font-weight:700">Login</a> untuk menulis ulasan.
      </div>
    @else
      @php
        $sudahReview = $produk->review->where('id_user', auth()->id())->isNotEmpty();
        $bolehReview = $produk->status === 'habis';
      @endphp
      @if($sudahReview)
        <div style="background:var(--green-bg);color:var(--green);border-radius:10px;padding:1rem;margin-top:1rem;font-size:14px;font-weight:600">
          ✅ Kamu sudah memberikan ulasan untuk produk ini.
        </div>
      @elseif(!$bolehReview)
        <div style="background:var(--bg);border-radius:10px;padding:1rem;margin-top:1rem;font-size:14px;color:var(--text2)">
          💬 Ulasan hanya bisa ditulis setelah transaksi <strong>Selesai</strong>.
        </div>
      @else
        <div class="review-form-box">
          <h3 style="font-size:16px;font-weight:700;margin-bottom:1rem">⭐ Tulis Ulasan</h3>
          <form method="POST" action="{{ route('review.store', $produk->id_produk) }}">
            @csrf
            <div class="form-group">
              <label class="form-label">Rating</label>
              <div class="star-select">
                @for($i = 5; $i >= 1; $i--)
                  <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }}>
                  <label for="star{{ $i }}">★</label>
                @endfor
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Komentar</label>
              <textarea class="form-control" name="komentar" rows="3"
                        placeholder="Ceritakan pengalaman transaksimu..."></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Kirim Ulasan</button>
          </form>
        </div>
      @endif
    @endguest
  </div>

</div><!-- /detail-wrap -->

@endsection
