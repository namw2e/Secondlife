@extends('layouts.app')
@section('title', 'Edit Produk — SecondLife')
@section('content')

<div class="form-page-wrap">
  <div class="form-page-box">
    <h2 class="form-page-title">✏️ Edit Produk</h2>

    @if($errors->any())
      <div class="alert alert-error">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('penjual.produk.update', $produk->id_produk) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      <!-- Foto existing -->
      @if($produk->foto->isNotEmpty())
        <div class="form-group">
          <label class="form-label">Foto Saat Ini <span style="color:var(--text2);font-weight:400">(centang untuk hapus)</span></label>
          <div style="display:flex;gap:10px;flex-wrap:wrap">
            @foreach($produk->foto as $f)
              <label style="position:relative;cursor:pointer">
                <img src="{{ asset('storage/produk/'.$f->foto) }}"
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border)">
                <input type="checkbox" name="hapus_foto[]" value="{{ $f->id_foto }}"
                       style="position:absolute;top:4px;right:4px;width:16px;height:16px">
              </label>
            @endforeach
          </div>
        </div>
      @endif

      <div class="form-group">
        <label class="form-label">Tambah Foto Baru</label>
        <div class="upload-area" onclick="document.getElementById('fotoInput').click()">
          <input type="file" id="fotoInput" name="foto_baru[]" multiple accept="image/*">
          <div style="font-size:28px">📷</div>
          <div style="font-size:13px;color:var(--text2)">Klik untuk upload foto baru</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Produk</label>
        <input class="form-control" type="text" name="nama_produk"
               value="{{ old('nama_produk', $produk->nama_produk) }}" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select class="form-control" name="id_kategori">
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $produk->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                {{ $k->nama_kategori }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kondisi</label>
          <select class="form-control" name="kondisi">
            <option value="Baru"  {{ old('kondisi', $produk->kondisi) === 'Baru'  ? 'selected' : '' }}>Baru</option>
            <option value="Bekas" {{ old('kondisi', $produk->kondisi) === 'Bekas' ? 'selected' : '' }}>Bekas</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Harga (Rp)</label>
          <div style="position:relative">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text2);font-weight:600;font-size:14px">Rp</span>
            <input class="form-control" type="number" name="harga"
                   value="{{ $produk->harga }}"
                   style="padding-left:38px" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-control" name="status">
            <option value="tersedia" {{ old('status', $produk->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="habis"    {{ old('status', $produk->status) === 'habis'    ? 'selected' : '' }}>Habis</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="deskripsi" rows="5">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
      </div>

      <div style="display:flex;gap:12px;flex-wrap:wrap">
        <button class="btn btn-primary" type="submit">💾 Simpan Perubahan</button>
        <a class="btn btn-ghost" href="{{ route('produk.detail', $produk->id_produk) }}">Batal</a>
      </div>
    </form>

    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1.5px dashed var(--border);display:flex;gap:12px;flex-wrap:wrap;align-items:center">
        <span style="font-size:13px;color:var(--text2);font-weight:600">Aksi Lainnya:</span>

        {{-- Tombol Sembunyikan / Publikasikan --}}
        <form method="POST" action="{{ route('penjual.produk.togglePublish', $produk->id_produk) }}" style="display:inline">
          @csrf @method('PATCH')
          @if($produk->status === 'nonaktif')
            <button type="submit" style="color:#3B6D11;border:1.5px solid #3B6D11;background:#EAF3DE;padding:10px 20px;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer" onclick="return confirm('Publikasikan produk ini kembali ke beranda?')">📢 Publikasikan</button>
          @else
            <button type="submit" style="color:#8B6914;border:1.5px solid #8B6914;background:#FFF8E6;padding:10px 20px;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer" onclick="return confirm('Sembunyikan produk dari beranda?\nProduk tidak akan dihapus, hanya disembunyikan.')">👁️‍🗨️ Sembunyikan dari Beranda</button>
          @endif
        </form>

        {{-- Tombol Hapus Permanen --}}
        <form method="POST" action="{{ route('penjual.produk.hapus', $produk->id_produk) }}" style="display:inline" onsubmit="return confirm('⚠️ HAPUS PERMANEN!\n\nProduk akan dihapus dari database beserta semua foto.\nAksi ini TIDAK BISA dibatalkan.\n\nLanjutkan?')">
          @csrf @method('DELETE')
          <button type="submit" style="color:var(--red);border:1.5px solid var(--red);background:#fff0f0;padding:10px 20px;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer">🗑️ Hapus Permanen</button>
        </form>
    </div>
  </div>
</div>

@endsection
