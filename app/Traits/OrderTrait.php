<?php

namespace App\Traits;

use App\Models\MstParentChecklists;

trait OrderTrait
{
    public function reindexParentPoint($typechecklist){
        // Ambil semua checklist dari tipe tertentu, urutkan berdasarkan `order` yang ada
        $parentPoints = MstParentChecklists::where('type_checklist', $typechecklist)
        ->orderBy('order_no', 'asc')
        ->get();

        // Re-index, berikan urutan baru mulai dari 1
        foreach ($parentPoints as $index => $parentPoint) {
            $parentPoint->update(['order_no' => $index + 1]);
        }
    }
}
