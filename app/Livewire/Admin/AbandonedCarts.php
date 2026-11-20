<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination; // هذا السطر هو المطلوب
use App\Models\AbandonedCart;


class AbandonedCarts extends Component
{
       use WithPagination;

    public string $search = '';
    public string $status = 'pending';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function render()
    {
    $storeId = auth()->user()->restaurant_id;

    $row = AbandonedCart::where('store_id', $storeId)
        ->selectRaw("
            SUM(status = 'pending')   as pending_count,
            SUM(status = 'recovered') as recovered_count,
            AVG(CASE WHEN status IN ('pending','recovered') THEN total_amount END) as avg_value
        ")
        ->first();
    $pending   = (int) ($row->pending_count ?? 0);
    $recovered = (int) ($row->recovered_count ?? 0);
    $totalLeft = $pending + $recovered;
     $stats = [
        'total'         => $pending,
        'recovery_rate' => $totalLeft > 0 ? round($recovered / $totalLeft * 100, 1) : 0,
        'avg_value'     => (float) ($row->avg_value ?? 0),
    ];
        $carts = AbandonedCart::query()
            ->where('store_id', auth()->user()->restaurant_id) // عزل كل مطعم
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->latest('last_active_at')
            ->paginate(15);


        return view('livewire.admin.abandoned-carts', compact('carts', 'stats'));
    }
}
