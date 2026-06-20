@extends('layouts.app')
@section('title', 'Jual Barang Baru — SecondLife')
@section('content')

<div class="form-page-wrap">
  <div class="form-page-box">
    <h2 class="form-page-title">📦 Jual Barang Baru</h2>

    @if($errors->any())
      <div class="alert alert-error">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('penjual.produk.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label class="form-label">Foto Produk <span style="color:var(--text2);font-weight:400">(maks 5 foto, masing-masing 2MB)</span></label>
        <div class="upload-area" onclick="document.getElementById('fotoInput').click()">
          <input type="file" id="fotoInput" name="foto[]" multiple accept="image/*" onchange="previewFotos(this)" required>
          <div id="fotoPreview">
            <div style="font-size:36px">📷</div>
            <div style="font-size:13px;color:var(--text2)">Klik untuk pilih foto</div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Produk</label>
        <input class="form-control" type="text" name="nama_produk"
               placeholder="Contoh: Buku Kalkulus Purcell Edisi 9"
               value="{{ old('nama_produk') }}" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select class="form-control" name="id_kategori" required>
            <option value="">Pilih kategori...</option>
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                {{ $k->nama_kategori }}
              </option>
            @endforeach
          </select>
          <small style="font-size:12px;color:var(--text2);margin-top:5px;display:block">
            Tidak ada yang cocok? <a href="{{ route('request.kategori') }}" style="color:var(--accent);font-weight:600">Request kategori baru →</a>
          </small>
        </div>
        <div class="form-group">
          <label class="form-label">Kondisi</label>
          <select class="form-control" name="kondisi">
            <option value="Baru"  {{ old('kondisi') === 'Baru'  ? 'selected' : '' }}>Baru</option>
            <option value="Bekas" {{ old('kondisi', 'Bekas') === 'Bekas' ? 'selected' : '' }}>Bekas</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Harga (Rp)</label>
        <div style="position:relative">
          <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text2);font-weight:600;font-size:14px">Rp</span>
          <input class="form-control" type="number" name="harga"
                 placeholder="0" value="{{ old('harga') }}"
                 style="padding-left:38px" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Produk</label>
        <textarea class="form-control" name="deskripsi" rows="5"
                  placeholder="Jelaskan kondisi barang, alasan jual, kelengkapan, dll...">{{ old('deskripsi') }}</textarea>
      </div>

      <div style="display:flex;gap:12px">
        <button class="btn btn-primary" type="submit">🚀 Posting Sekarang</button>
        <a class="btn btn-ghost" href="{{ route('home') }}">Batal</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function previewFotos(input) {
  const preview = document.getElementById('fotoPreview');
  if (!input.files.length) return;
  preview.innerHTML = '';
  const wrap = document.createElement('div');
  wrap.style.cssText = 'display:flex;gap:8px;flex-wrap:wrap;justify-content:center;padding:8px 0';
  preview.appendChild(wrap);
  const count = Math.min(input.files.length, 5);
  Array.from(input.files).slice(0, 5).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border)';
      wrap.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
  const info = document.createElement('div');
  info.style.cssText = 'margin-top:8px;font-size:13px;color:var(--text2)';
  info.textContent = input.files.length + ' foto dipilih';
  preview.appendChild(info);
}
</script>
@endpush

@endsection
