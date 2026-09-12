<div>
    <!-- Header & Search Bar -->
    <div class="p-4 bg-white block sm:flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">@lang('menu.sliders')</h1>
            </div>
            <div class="items-center justify-between block sm:flex">
                <div class="flex items-center mb-4 sm:mb-0">
                    <form class="sm:pr-3" action="#" method="GET">
                        <label for="sliders-search" class="sr-only">Search</label>
                        <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                            <x-input id="slider_title" class="block mt-1 w-full" type="text" placeholder="{{ __('placeholders.searchSlider') }}" wire:model.live.debounce.500ms="search" />
                        </div>
                    </form>
                </div>

                <div class="inline-flex gap-x-4 mb-4 sm:mb-0">
                    @if(user_can('Create Slider'))
                    <x-button type='button' wire:click="$toggle('showAddSliderModal')">@lang('modules.menu.addSlider')</x-button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sliders Table -->
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                                    @lang('modules.menu.image')
                                </th>
                                <th scope="col" class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                                    @lang('menu.sliderTitle')
                                </th>
                                <th scope="col" class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                                    @lang('app.status')
                                </th>
                                <th scope="col" class="py-2.5 px-4 text-xs font-medium ltr:text-left rtl:text-right text-gray-500 uppercase dark:text-gray-400">
                                    @lang('app.action')
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" wire:key='slider-list-{{ microtime() }}'>

                            @forelse ($sliders as $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" wire:key='slider-item-{{ $item->id . microtime() }}' wire:loading.class.delay='opacity-10'>
                                <td class="py-2.5 px-4 text-base text-gray-900 whitespace-nowrap dark:text-white">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-16 h-10 object-cover rounded">
                                </td>
                                <td class="py-2.5 px-4 text-base text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->title }}
                                </td>
                                <td class="py-2.5 px-4 text-base text-gray-900 whitespace-nowrap dark:text-white">
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $item->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $item->status ? __('app.active') : __('app.inactive') }}
                                    </span>
                                </td>

                                <td class="py-2.5 px-4 space-x-2 whitespace-nowrap text-right">
                                    @if(user_can('Update Slider'))
                                    <x-secondary-button-table wire:click='showEditSlider({{ $item->id }})' wire:key='edit-slider-button-{{ $item->id }}'>
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        @lang('app.update')
                                    </x-secondary-button-table>
                                    @endif

                                    @if(user_can('Delete Slider'))
                                    <x-danger-button-table wire:click="showDeleteSlider({{ $item->id }})">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </x-danger-button-table>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-2.5 px-4 space-x-6 dark:text-gray-400" colspan="4">
                                    @lang('messages.noSliderAdded')
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div wire:key='slider-paginate-{{ microtime() }}' class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center mb-4 sm:mb-0 w-full">
            {{ $sliders->links() }}
        </div>
    </div>

    <!-- Edit Slider Modal -->
    <x-dialog-modal wire:model.live="showEditSliderModal">
        <x-slot name="title">
            {{ __("modules.menu.editSlider") }}
        </x-slot>

        <x-slot name="content">
            @if ($slider)
                @livewire('forms.editSlider', ['slider' => $slider], key(str()->random(50)))
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEditSliderModal', false)" wire:loading.attr="disabled">
                {{ __('app.close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Add Slider Modal -->
    <x-dialog-modal wire:model.live="showAddSliderModal" maxWidth="xl">
        <x-slot name="title">
            @lang('modules.menu.addSlider')
        </x-slot>

        <x-slot name="content">
            @livewire('forms.addSlider')
        </x-slot>

        <x-slot name="footer">
            <x-button-cancel wire:click="$toggle('showAddSliderModal')" wire:loading.attr="disabled">
                {{ __('app.close') }}
            </x-button-cancel>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmDeleteSlider">
        <x-slot name="title">
            @lang('menu.deleteSlider')?
        </x-slot>

        <x-slot name="content">
            @lang('modules.menu.deleteSliderMessage')
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmDeleteSlider')" wire:loading.attr="disabled">
                {{ __('app.cancel') }}
            </x-secondary-button>

            @if ($slider)
            <x-danger-button class="ml-3" wire:click='deleteSlider({{ $slider->id }})' wire:loading.attr="disabled">
                {{ __('app.delete') }}
            </x-danger-button>
            @endif
        </x-slot>
    </x-confirmation-modal>
</div>