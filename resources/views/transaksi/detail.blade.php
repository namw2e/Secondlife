@extends('layouts.app')
@section('title', 'Detail Transaksi #'.$transaksi->id_transaksi.' — SecondLife')
@section('content')

<div class="page-section">
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem">
    <h2>📋 Detail Transaksi #{{ $transaksi->id_transaksi }}</h2>
    <a class="btn btn-ghost" href="{{ route('transaksi.index') }}" style="padding:9px 18px;font-size:14px">← Kembali</a>
  </div>

  {{-- STATUS BADGE --}}
  <div style="margin-bottom:1.5rem">
    <span class="badge b-{{ $transaksi->status }}" style="font-size:14px;padding:8px 16px">
      {{ $transaksi->labelStatus() }}
    </span>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

    {{-- KOLOM KIRI: PRODUK + JADWAL COD --}}
    <div>
      {{-- PRODUK --}}
      <div class="sidebar-box" style="margin-bottom:1.5rem">
        <div class="sidebar-title">Produk</div>
        @foreach($transaksi->detail as $dt)
          @php $produk = $dt->produk; @endphp
          <div class="trx-card" style="margin-bottom:.5rem">
            <div class="trx-icon">
              @if($produk && $produk->foto->isNotEmpty())
                <img src="{{ asset('storage/produk/'.$produk->foto->first()->foto) }}"
                     style="width:48px;height:48px;object-fit:cover;border-radius:8px" alt="">
              @else
                📦
              @endif
            </div>
            <div class="trx-info">
              <strong>{{ $produk->nama_produk ?? 'Produk dihapus' }}</strong>
              <span>{{ $dt->qty }}x · Rp {{ number_format($dt->harga_satuan, 0, ',', '.') }}</span>
            </div>
          </div>
        @endforeach

        <div style="height:1px;background:var(--border);margin:1rem 0"></div>
        <div style="display:flex;justify-content:space-between;font-weight:700;font-size:16px">
          <span>Total</span>
          <span style="color:var(--accent)">{{ $transaksi->formatTotal() }}</span>
        </div>
      </div>

      {{-- JADWAL COD --}}
      @if($transaksi->jadwalCod)
        @php $cod = $transaksi->jadwalCod; @endphp
        <div class="sidebar-box">
          <div class="sidebar-title">📅 Jadwal COD</div>
          <div class="cod-summary-box">
            <div class="cod-summary-row"><span>Hari</span><strong>{{ $cod->hari }}</strong></div>
            <div class="cod-summary-row"><span>Tanggal</span><strong>{{ \Carbon\Carbon::parse($cod->tanggal)->format('d M Y') }}</strong></div>
            <div class="cod-summary-row"><span>Waktu</span><strong>{{ $cod->waktu }} WIB</strong></div>
            <div class="cod-summary-row"><span>Lokasi</span><strong>{{ $cod->lokasi_cod }}</strong></div>
            @if($cod->catatan)
              <div class="cod-summary-row"><span>Catatan</span><strong>{{ $cod->catatan }}</strong></div>
            @endif
          </div>

          {{-- Form Edit/Reschedule COD (Fitur #2) --}}
          @if(in_array($transaksi->status, ['menunggu_konfirmasi', 'dikonfirmasi']))
            <details style="margin-top:12px">
              <summary style="cursor:pointer;color:var(--accent);font-size:13px;font-weight:600;list-style:none;padding:6px 0">✏️ Ubah Jadwal COD ▾</summary>
              <form method="POST" action="{{ route('transaksi.jadwal.edit', $transaksi->id_transaksi) }}" style="margin-top:10px">
                @csrf @method('PUT')
                <div class="form-group" style="margin-bottom:8px">
                  <label class="form-label" style="font-size:12px">Tanggal</label>
                  <input class="form-control" type="date" name="tanggal"
                         value="{{ $cod->tanggal }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group" style="margin-bottom:8px">
                  <label class="form-label" style="font-size:12px">Hari</label>
                  <select class="form-control" name="hari" required>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                      <option value="{{ $h }}" {{ $cod->hari === $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:8px">
                  <label class="form-label" style="font-size:12px">Waktu</label>
                  <input class="form-control" type="time" name="waktu" value="{{ $cod->waktu }}" required>
                </div>
                <div class="form-group" style="margin-bottom:8px">
                  <label class="form-label" style="font-size:12px">Lokasi COD</label>
                  <input class="form-control" type="text" name="lokasi_cod" value="{{ $cod->lokasi_cod }}" required maxlength="255">
                </div>
                <div class="form-group" style="margin-bottom:10px">
                  <label class="form-label" style="font-size:12px">Catatan (opsional)</label>
                  <textarea class="form-control" name="catatan" rows="2">{{ $cod->catatan }}</textarea>
                </div>
                <button class="btn btn-primary btn-full" type="submit" style="font-size:13px">📅 Simpan Perubahan Jadwal</button>
              </form>
            </details>
          @endif
        </div>
      @endif

      {{-- Foto Bukti COD (ditampilkan jika sudah ada) --}}
      @if($transaksi->foto_bukti)
        <div class="sidebar-box" style="margin-top:1rem">
          <div class="sidebar-title">📸 Bukti Foto COD</div>
          <a href="{{ asset('storage/bukti_cod/'.$transaksi->foto_bukti) }}" target="_blank">
            <img src="{{ asset('storage/bukti_cod/'.$transaksi->foto_bukti) }}"
                 style="width:100%;border-radius:10px;object-fit:cover;max-height:220px;cursor:zoom-in"
                 alt="Bukti COD">
          </a>
          <p style="font-size:11px;color:var(--text2);margin-top:6px;text-align:center">Klik untuk perbesar</p>
        </div>
      @endif
    </div>

    {{-- KOLOM KANAN: PIHAK + AKSI --}}
    <div>
      {{-- PEMBELI & PENJUAL --}}
      <div class="sidebar-box" style="margin-bottom:1.5rem">
        <div class="sidebar-title">Pihak Transaksi</div>

        <div style="margin-bottom:1rem">
          <div style="font-size:12px;color:var(--text2);margin-bottom:4px">Pembeli</div>
          <div style="display:flex;align-items:center;gap:10px">
            <div class="seller-avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
              {{ strtoupper(substr($transaksi->pembeli->nama ?? '-', 0, 2)) }}
            </div>
            <div>
              <strong>{{ $transaksi->pembeli->nama ?? '-' }}</strong>
              <div style="font-size:13px;color:var(--text2)">{{ $transaksi->pembeli->email ?? '' }}</div>
            </div>
          </div>
        </div>

        <div>
          <div style="font-size:12px;color:var(--text2);margin-bottom:4px">Penjual</div>
          <div style="display:flex;align-items:center;gap:10px">
            <div class="seller-avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
              {{ strtoupper(substr($transaksi->penjual->nama ?? '-', 0, 2)) }}
            </div>
            <div>
              <strong>{{ $transaksi->penjual->nama ?? '-' }}</strong>
              <div style="font-size:13px;color:var(--text2)">{{ $transaksi->penjual->email ?? '' }}</div>
            </div>
          </div>
          @if($transaksi->penjual && $transaksi->penjual->no_hp)
            <a class="btn btn-ghost btn-full" href="{{ $transaksi->penjual->waLink() }}" target="_blank"
               style="margin-top:10px;font-size:13px">💬 WhatsApp Penjual</a>
          @endif
        </div>
      </div>

      {{-- AKSI --}}
      <div class="sidebar-box">
        <div class="sidebar-title">Aksi</div>

        <a class="btn btn-ghost btn-full" href="{{ route('chat.index', $transaksi->id_transaksi) }}"
           style="margin-bottom:8px;font-size:13px">💬 Chat Transaksi</a>

        @if(in_array($transaksi->status, ['menunggu_konfirmasi', 'dikonfirmasi']))
          <form method="POST" action="{{ route('transaksi.aksi', $transaksi->id_transaksi) }}">
            @csrf
            <input type="hidden" name="action" value="dibatalkan">
            <button class="btn btn-ghost btn-full" type="submit" style="font-size:13px;color:var(--red)"
                    onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
              ❌ Batalkan Transaksi
            </button>
          </form>
        @endif

        @if($transaksi->status === 'dikonfirmasi')
          {{-- Fitur #4: Form upload foto bukti sebelum selesai --}}
          <div style="background:var(--bg);border:2px dashed var(--border);border-radius:12px;padding:14px;margin-top:8px">
            <p style="font-size:13px;font-weight:600;margin-bottom:8px">📸 Upload Foto Bukti COD</p>
            <p style="font-size:12px;color:var(--text2);margin-bottom:10px">Foto COD wajib diunggah untuk menyelesaikan transaksi.</p>
            <form method="POST" action="{{ route('transaksi.aksi', $transaksi->id_transaksi) }}"
                  enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="action" value="selesai">
              <div style="margin-bottom:10px">
                <input type="file" name="foto_bukti" id="foto_bukti_input" accept="image/*"
                       style="width:100%;font-size:12px;padding:8px;background:var(--white);border:1.5px solid var(--border);border-radius:8px;cursor:pointer"
                       onchange="previewBukti(this)" required>
              </div>
              <div id="preview_bukti" style="display:none;margin-bottom:10px">
                <img id="preview_img" style="width:100%;border-radius:8px;max-height:160px;object-fit:cover" alt="Preview">
              </div>
              <button class="btn btn-primary btn-full" type="submit" style="font-size:13px"
                      onclick="return confirm('Pastikan foto sudah benar. Selesaikan transaksi?')">
                ✅ Tandai Selesai + Simpan Bukti
              </button>
            </form>
          </div>
        @endif

        @if($transaksi->status === 'selesai')
          @php $produkReview = $transaksi->detail->first()?->produk; @endphp
          @if($produkReview)
            <a class="btn btn-ghost btn-full" href="{{ route('produk.detail', $produkReview->id_produk) }}#review"
               style="margin-top:8px;font-size:13px">⭐ Beri Ulasan</a>
          @endif
        @endif
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
function previewBukti(input) {
    const preview = document.getElementById('preview_bukti');
    const img     = document.getElementById('preview_img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
