<?php

namespace App\Livewire\ItemModifiers;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Slider;

class SliderItem extends Component
{
    use WithPagination, WithFileUploads;


    public $search = '';

    public $slider;

    public $showAddSliderModal = false;
    public $showEditSliderModal = false;
    public $confirmDeleteSlider = false;
   
    public function render()
    {
         $sliders = Slider::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
        return view('livewire.item-modifiers.slider-item', [
            'sliders' => $sliders
        ]);
    }
}
