<?php

namespace App\Livewire\ItemModifiers;

use App\Models\Branch;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SliderItem extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filter = '';
    public string $branchFilter = ''; // '' = الكل, 'all' = العامة فقط, أو رقم الفرع

    public bool $showModal = false;
    public ?int $sliderId = null;

    public $branch_id = ''; // '' = كل الفروع
    public string $title = '';
    public string $type = 'image';
    public string $link = '';
    public bool $status = true;
    public $sort_order = 0;

    public $images = [];
    public $video = null;
    public ?string $existing_image = null;
    public ?string $existing_video = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilter() { $this->resetPage(); }
    public function updatingBranchFilter() { $this->resetPage(); }

    protected function rid()
    {
        return auth()->user()->restaurant_id;
    }

    protected function branches()
    {
        return Branch::where('restaurant_id', $this->rid())->orderBy('name')->get(['id', 'name']);
    }

    protected function applyBranch($q)
    {
        return $this->branchFilter === 'all'
            ? $q->whereNull('branch_id')
            : $q->where(fn ($q) => $q->whereNull('branch_id')->orWhere('branch_id', $this->branchFilter));
    }

    protected function rules(): array
    {
        return [
            'branch_id'  => ['nullable', Rule::exists('branches', 'id')->where('restaurant_id', $this->rid())],
            'title'      => ['nullable', 'string', 'max:100'],
            'type'       => ['required', 'in:image,video'],
            'link'       => ['nullable', 'string', 'max:255'],
            'status'     => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'images'     => [$this->type === 'image' && ! $this->existing_image ? 'required' : 'nullable', 'array'],
            'images.*'   => ['image', 'max:4096'],
            'video'      => [$this->type === 'video' && ! $this->existing_video ? 'required' : 'nullable',
                             'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:20480'],
        ];
    }

    protected $messages = [
        'images.required' => 'اختر صورة واحدة على الأقل',
        'images.*.image'  => 'الملف لازم يكون صورة',
        'images.*.max'    => 'حجم الصورة يتجاوز 4 ميجا',
        'video.required'  => 'اختر ملف الفيديو',
        'video.mimetypes' => 'الصيغ المدعومة: mp4 / webm',
        'video.max'       => 'حجم الفيديو يتجاوز 20 ميجا',
        'branch_id.exists' => 'الفرع غير صحيح',
    ];

    public function create()
    {
        $this->resetForm();
        $this->sort_order = (int) Slider::where('restaurant_id', $this->rid())->max('sort_order') + 1;
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $s = Slider::where('restaurant_id', $this->rid())->findOrFail($id);

        $this->resetForm();
        $this->sliderId       = $s->id;
        $this->branch_id      = $s->branch_id ?: '';
        $this->title          = (string) $s->title;
        $this->type           = $s->type ?: 'image';
        $this->link           = (string) $s->link;
        $this->status         = (bool) $s->status;
        $this->sort_order     = $s->sort_order;
        $this->existing_image = $s->image;
        $this->existing_video = $s->video;
        $this->showModal = true;
    }
    

    public function save()
    {
        $this->validate();

        $base = [
            'restaurant_id' => $this->rid(),
            'branch_id'     => $this->branch_id ?: null,
            'title'         => $this->title ?: null,
            'type'          => $this->type,
            'link'          => $this->link ?: null,
            'status'        => $this->status,
        ];

        if ($this->sliderId) {
            $s = Slider::where('restaurant_id', $this->rid())->findOrFail($this->sliderId);
            $data = $base + ['sort_order' => (int) $this->sort_order];

            if ($this->type === 'image') {
                if (! empty($this->images)) {
                    $this->deleteFile($s->image);
                    $data['image'] = $this->images[0]->store('sliders/images', 'public');
                }
                $this->deleteFile($s->video);
                $data['video'] = null;
            } else {
                if ($this->video) {
                    $this->deleteFile($s->video);
                    $data['video'] = $this->video->store('sliders/videos', 'public');
                }
                $this->deleteFile($s->image);
                $data['image'] = null;
            }

            $s->update($data);
        } else {
            $order = (int) $this->sort_order;

            if ($this->type === 'image') {
                foreach ($this->images as $img) {
                    Slider::create($base + [
                        'image'      => $img->store('sliders/images', 'public'),
                        'sort_order' => $order++,
                    ]);
                }
            } else {
                Slider::create($base + [
                    'video'      => $this->video->store('sliders/videos', 'public'),
                    'sort_order' => $order,
                ]);
            }
        }

        $this->closeModal();
        session()->flash('success', 'تم حفظ السلايدر بنجاح');
    }

    public function toggle(int $id)
    {
        $s = Slider::where('restaurant_id', $this->rid())->findOrFail($id);
        $s->update(['status' => ! $s->status]);
    }

    public function move(int $id, string $dir)
    {
        $ids = Slider::where('restaurant_id', $this->rid())
            ->orderBy('sort_order')->orderBy('id')->pluck('id')->all();

        $i = array_search($id, $ids);
        $j = $dir === 'up' ? $i - 1 : $i + 1;

        if ($i === false || ! isset($ids[$j])) return;

        [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]];

        foreach ($ids as $pos => $sid) {
            Slider::where('id', $sid)->update(['sort_order' => $pos]);
        }
    }

    public function delete(int $id)
    {
        $s = Slider::where('restaurant_id', $this->rid())->findOrFail($id);
        $this->deleteFile($s->image);
        $this->deleteFile($s->video);
        $s->delete();
        session()->flash('success', 'تم حذف الشريحة');
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'sliders/')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'sliderId', 'branch_id', 'title', 'link', 'images', 'video',
            'existing_image', 'existing_video',
        ]);
        $this->type = 'image';
        $this->status = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $rid = $this->rid();

        $row = Slider::where('restaurant_id', $rid)->selectRaw("
            COUNT(*) as total,
            SUM(status = 1) as active_count,
            SUM(type = 'image') as image_count,
            SUM(type = 'video') as video_count
        ")->first();

        $stats = [
            'total'  => (int) ($row->total ?? 0),
            'active' => (int) ($row->active_count ?? 0),
            'images' => (int) ($row->image_count ?? 0),
            'videos' => (int) ($row->video_count ?? 0),
        ];

        $preview = Slider::where('restaurant_id', $rid)
            ->when($this->branchFilter !== '', fn ($q) => $this->applyBranch($q))
            ->where('status', 1)->orderBy('sort_order')->orderBy('id')->get();

        $sliders = Slider::where('restaurant_id', $rid)
            ->with('branch:id,name')
            ->when($this->branchFilter !== '', fn ($q) => $this->applyBranch($q))
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filter === 'active', fn ($q) => $q->where('status', 1))
            ->when($this->filter === 'inactive', fn ($q) => $q->where('status', 0))
            ->when(in_array($this->filter, ['image', 'video']), fn ($q) => $q->where('type', $this->filter))
            ->orderBy('sort_order')->orderBy('id')
            ->paginate(10);

        return view('livewire.item-modifiers.slider-item', [
            'sliders'  => $sliders,
            'stats'    => $stats,
            'preview'  => $preview,
            'branches' => $this->branches(),
        ]);
    }
}