@extends('layouts.guest')

@section('content')
@if (request('waiting_number'))
    <div class="max-w-md mx-auto mt-6">
        <div class="bg-white shadow-lg rounded-2xl p-6 text-center border border-green-200">
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-10 w-10 text-green-500" 
                     fill="none" viewBox="0 0 24 24" 
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">
                🎉 تم تأكيد حجزك بنجاح
            </h2>
            <p class="text-gray-600">رقم الانتظار الخاص بك هو</p>
            <p class="text-4xl font-bold text-green-600 my-3">
                @php
                $waitingNumber = null;
                //branch_id
                 $currentReservations = \App\Models\Reservation::where('branch_id', $branch_id)
                            ->where('reservation_status', 'Confirmed')
                            ->count();
                 $totalTables = \App\Models\Table::where('branch_id', $branch_id)->count();

                    if ($currentReservations < $totalTables) {
                            $waitingNumber = 0; // مافي انتظار
                        } else {
                            $waitingNumber = ($currentReservations + 1) - $totalTables;
                        }


                  
                @endphp
                {{ $waitingNumber }}
            </p>
            <p class="text-sm text-gray-500">
                احتفظ بهذا الرقم لتأكيد دخولك عند الوصول
            </p>
        </div>
    </div>
@endif

@livewire('shop.bookings')
    
@endsection