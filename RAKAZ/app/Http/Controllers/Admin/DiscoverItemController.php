<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscoverItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DiscoverItemController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $item = DiscoverItem::findOrFail($id);

            // Delete image if exists
            if ($item->image && strpos($item->image, '/storage/') === 0) {
                $imagePath = str_replace('/storage/', '', $item->image);
                Storage::disk('public')->delete($imagePath);
                Log::info("Deleted discover item image", ['path' => $imagePath]);
            }

            $item->delete();
            Log::info("Deleted discover item", ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to delete discover item", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item'
            ], 500);
        }
    }
}
