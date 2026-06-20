<?php
namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller {
    public function store(Request $request, $id) {
        $user   = Auth::user();
        $produk = Produk::findOrFail($id);
        $request->validate(['rating'=>'required|integer|min:1|max:5','komentar'=>'required|string|max:500']);
        Review::updateOrCreate(
            ['id_produk'=>$id,'id_user'=>$user->id_user],
            ['id_penjual'=>$produk->id_user,'rating'=>$request->rating,'komentar'=>$request->komentar,'created_at'=>now()]
        );
        return back()->with('success','✅ Review berhasil dikirim!');
    }
    public function balas(Request $request, $id) {
        $user   = Auth::user();
        $review = Review::where('id_review',$id)->where('id_penjual',$user->id_user)->firstOrFail();
        $request->validate(['balasan'=>'required|string|max:500']);
        $review->update(['balasan_penjual'=>$request->balasan]);
        return back()->with('success','✅ Balasan dikirim!');
    }
}
