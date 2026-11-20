<?php
// app/Livewire/Admin/Coupons.php
namespace App\Livewire\Admin;

use App\Models\Coupon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Coupons extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filter = '';

    public bool $showModal = false;
    public ?int $couponId = null;

    public string $code = '';
    public string $type = 'percentage';
    public $value = '';
    public $minimum_amount = 0;
    public $maximum_discount = null;
    public $usage_limit = null;
    public $starts_at = null;
    public $expires_at = null;
    public bool $is_active = true;
    public $ad_title = '';
    public $ad_description = '';
    public $button_text = '';
    public $ad_image = null;
    public ?string $existing_image = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }

    protected function restaurantId()
    {
        return auth()->user()->restaurant_id;
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50',
                Rule::unique('coupons', 'code')
                    ->where('restaurant_id', $this->restaurantId())
                    ->ignore($this->couponId)],
            'type'             => ['required', 'in:percentage,fixed'],
            'value'            => ['required', 'numeric', 'min:0.01',
                $this->type === 'percentage' ? 'max:100' : 'max:100000'],
            'minimum_amount'   => ['nullable', 'numeric', 'min:0'],
            'maximum_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit'      => ['nullable', 'integer', 'min:1'],
            'starts_at'        => ['nullable', 'date'],
            'expires_at'       => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'        => ['boolean'],
            'ad_title'         => ['nullable', 'string', 'max:100'],
            'ad_description'   => ['nullable', 'string', 'max:255'],
            'button_text'      => ['nullable', 'string', 'max:40'],
            'ad_image'         => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $c = Coupon::where('restaurant_id', $this->restaurantId())->findOrFail($id);

        $this->resetForm();
        $this->couponId         = $c->id;
        $this->code             = $c->code;
        $this->type             = $c->type;
        $this->value            = $c->value;
        $this->minimum_amount   = $c->minimum_amount;
        $this->maximum_discount = $c->maximum_discount;
        $this->usage_limit      = $c->usage_limit;
        $this->starts_at        = $c->starts_at?->format('Y-m-d\TH:i');
        $this->expires_at       = $c->expires_at?->format('Y-m-d\TH:i');
        $this->is_active        = $c->is_active;
        $this->ad_title         = $c->ad_title;
        $this->ad_description   = $c->ad_description;
        $this->button_text      = $c->button_text;
        $this->existing_image   = $c->ad_image;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'restaurant_id'    => $this->restaurantId(),
            'code'             => strtoupper(trim($this->code)),
            'type'             => $this->type,
            'value'            => $this->value,
            'minimum_amount'   => $this->minimum_amount ?: 0,
            'maximum_discount' => $this->maximum_discount ?: null,
            'usage_limit'      => $this->usage_limit ?: null,
            'starts_at'        => $this->starts_at ?: null,
            'expires_at'       => $this->expires_at ?: null,
            'is_active'        => $this->is_active,
            'ad_title'         => $this->ad_title ?: null,
            'ad_description'   => $this->ad_description ?: null,
            'button_text'      => $this->button_text ?: null,
        ];

        if ($this->ad_image) {
            if ($this->existing_image) {
                Storage::disk('public')->delete($this->existing_image);
            }
            $data['ad_image'] = $this->ad_image->store('coupons', 'public');
        }

        if ($this->couponId) {
            Coupon::where('restaurant_id', $this->restaurantId())
                ->findOrFail($this->couponId)->update($data);
        } else {
            Coupon::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', 'تم حفظ الكوبون بنجاح');
    }

    public function toggle(int $id)
    {
        $c = Coupon::where('restaurant_id', $this->restaurantId())->findOrFail($id);
        $c->update(['is_active' => ! $c->is_active]);
    }

    public function delete(int $id)
    {
        $c = Coupon::where('restaurant_id', $this->restaurantId())->findOrFail($id);
        if ($c->ad_image) {
            Storage::disk('public')->delete($c->ad_image);
        }
        $c->delete();
        session()->flash('success', 'تم حذف الكوبون');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'couponId', 'code', 'value', 'maximum_discount', 'usage_limit',
            'starts_at', 'expires_at', 'ad_title', 'ad_description',
            'button_text', 'ad_image', 'existing_image',
        ]);
        $this->type = 'percentage';
        $this->minimum_amount = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $rid = $this->restaurantId();

        $row = Coupon::where('restaurant_id', $rid)->selectRaw("
            COUNT(*) as total,
            SUM(is_active = 1 AND (expires_at IS NULL OR expires_at > NOW())) as active_count,
            SUM(expires_at IS NOT NULL AND expires_at <= NOW()) as expired_count,
            COALESCE(SUM(used), 0) as used_total
        ")->first();

        $stats = [
            'total'   => (int) ($row->total ?? 0),
            'active'  => (int) ($row->active_count ?? 0),
            'expired' => (int) ($row->expired_count ?? 0),
            'used'    => (int) ($row->used_total ?? 0),
        ];

        $coupons = Coupon::where('restaurant_id', $rid)
            ->when($this->search, fn ($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->when($this->filter === 'active', fn ($q) => $q->where('is_active', 1)
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now())))
            ->when($this->filter === 'inactive', fn ($q) => $q->where('is_active', 0))
            ->when($this->filter === 'expired', fn ($q) => $q->where('expires_at', '<=', now()))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.coupons', compact('coupons', 'stats'));
    }
}