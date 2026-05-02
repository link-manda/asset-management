<?php

namespace App\Http\Controllers;

use App\Models\AssetImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetImageController extends Controller
{
    public function destroy(AssetImage $image)
    {
        // Delete physical file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}
