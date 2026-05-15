<?php

namespace App\Livewire\Shop;

use Carbon\Carbon;
use App\Models\Table;
use App\Models\Branch;
use Livewire\Component;
use Carbon\CarbonPeriod;
use App\Models\Reservation;
use App\Models\ReservationSetting;
use App\Events\ReservationReceived;
use Illuminate\Support\Facades\Crypt;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Notifications\ReservationConfirmation;

class BookATable extends Component
{
    use LivewireAlert;

    protected $listeners = ['setCustomer' => '$refresh'];

    public $date;
    public $numberOfGuests = 1;
    public $slotType;
    public $specialRequest;
    public $restaurant;
    public $availableTimeSlots = [];
    public $shopBranch;

    public $disabledDates = [];
    public $availableSlotTypes = [];
    public $disabledSlotTypes = [];
    public $timeSlots = [];
    public $latestReservation;

    // Slot types as constants
    private const SLOT_TYPES = ['Breakfast', 'Lunch', 'Dinner'];

    public function mount()
    {
        if (!$this->restaurant) {
            return $this->redirect(route('home'));
        }
        // Eager load branches
        $this->restaurant->loadMissing('branches');
        $this->date = $this->now()->format('Y-m-d');
        $this->slotType = $this->getDefaultSlotType();


        // مثال: تحديد العميل عن طريق auth user
        $this->latestReservation = Reservation::where('customer_id', 2)
            ->latest()
            ->first();


        $this->shopBranch = $this->getBranch();

        $this->refreshAvailabilityData();
    }

    private function now()
    {
        return now()->timezone(timezone());
    }

    private function getDefaultSlotType()
    {
        $hour = $this->now()->format('H');
        return (intval($hour) >= 17) ? 'Dinner' : ((intval($hour) >= 12) ? 'Lunch' : 'Breakfast');
    }

    private function getBranch()
    {
        $branchId = request()->branch;
        if ($branchId && $branchId != '') {
            return Branch::find($branchId);
        }
        return $this->restaurant->branches->first();
    }

    private function getDayOfWeek($date = null)
    {
        return Carbon::parse($date ?? $this->date)->format('l');
    }

    private function refreshAvailabilityData()
    {
        $this->loadAvailabilityData();
        $this->loadAvailableTimeSlots();
    }

    public function loadAvailabilityData()
    {
        $allSettings = ReservationSetting::where('branch_id', $this->shopBranch?->id)
            ->where('available', 1)
            ->get();

        $this->availableSlotTypes = [];
        foreach ($allSettings as $setting) {
            $this->availableSlotTypes[$setting->slot_type][] = $setting->day_of_week;
        }

        $this->disabledDates = $this->getDisabledDates();
        $this->disabledSlotTypes = $this->getDisabledSlotTypes();
    }

    private function getDisabledDates()
    {
        $startOfWeek = $this->now();
        $endOfWeek = $startOfWeek->copy()->addDays(6);
        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);

        $disabledDates = [];
        foreach ($period as $date) {
            $dayOfWeek = $date->format('l');
            if (!$this->isAnySlotAvailableOnDay($dayOfWeek)) {
                $disabledDates[] = $date->format('Y-m-d');
            }
        }
        return $disabledDates;
    }

    private function isAnySlotAvailableOnDay($dayOfWeek)
    {
        foreach ($this->availableSlotTypes as $slotDays) {
            if (in_array($dayOfWeek, $slotDays)) {
                return true;
            }
        }
        return false;
    }

    private function getDisabledSlotTypes()
    {
        $dayOfWeek = $this->getDayOfWeek();
        $disabledSlotTypes = [];
        foreach (self::SLOT_TYPES as $type) {
            if (!$this->isSlotTypeAvailableForDay($type, $dayOfWeek)) {
                $disabledSlotTypes[] = $type;
            }
        }
        return $disabledSlotTypes;
    }

    private function isSlotTypeAvailableForDay($slotType, $dayOfWeek)
    {
        return isset($this->availableSlotTypes[$slotType]) &&
            in_array($dayOfWeek, $this->availableSlotTypes[$slotType]);
    }

    public function loadAvailableTimeSlots()
    {
        $this->timeSlots = [];

        if (!$this->date || !$this->slotType) {
            return;
        }

        $dayOfWeek = $this->getDayOfWeek();

        $settings = ReservationSetting::where([
            ['day_of_week', $dayOfWeek],
            ['slot_type', $this->slotType],
            ['branch_id', $this->shopBranch?->id],
        ])->first();

        if (!$settings) {
            return;
        }

        $startTime = Carbon::parse($settings->time_slot_start);
        $endTime = Carbon::parse($settings->time_slot_end);
        $slotDifference = (int)$settings->time_slot_difference;

        while ($startTime->lte($endTime)) {
            $this->timeSlots[] = $startTime->format('H:i:s');
            $startTime->addMinutes($slotDifference);
        }
    }

    public function isSlotTypeAvailable($type = null)
    {
        $slotType = $type ?? $this->slotType;
        $selectedDay = $this->getDayOfWeek();
        return $this->isSlotTypeAvailableForDay($slotType, $selectedDay);
    }

    public function isTimeSlotPast($timeSlot)
    {
        $isToday = $this->date == $this->now()->format('Y-m-d');
        $currentTime = $this->now()->format('H:i:s');
        return $isToday && $timeSlot <= $currentTime;
    }

    public function isTimeSlotDisabled($timeSlot)
    {
        return $this->isTimeSlotPast($timeSlot) || !$this->isSlotTypeAvailable();
    }

    public function setReservationDate($selectedDate)
    {
        $this->date = $selectedDate;
        $this->refreshAvailabilityData();
    }

    public function setReservationGuest($noOfGuests)
    {
        $this->numberOfGuests = $noOfGuests;
    }

    public function setReservationSlotType($type)
    {
        $this->slotType = $type;
        $this->refreshAvailabilityData();
    }

    public function submitReservation()
    {
        $this->validate([
            'availableTimeSlots' => 'required',
        ]);

        if (!$this->isSlotTypeAvailable()) {
            $this->alert('error', __('messages.slotTypeNotAvailable'), [
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }

        if ($this->isTimeSlotPast($this->availableTimeSlots)) {
            $this->alert('error', __('messages.pastTimeSlot'), [
                'toast' => true,
                'position' => 'top-end',
            ]);
            return;
        }
        // اجمالي عدد الطاولات
        $totalTables = Table::count();
        // عدد الحجوزات الحالية (مؤكدة أو جارية)
        $currentReservations = Reservation::where('reservation_status', 'Confirmed')->count();
        // حساب رقم الانتظار مباشرة
        if ($currentReservations < $totalTables) {
            $waitingNumber = 0; // مافي انتظار
        } else {
            $waitingNumber = ($currentReservations + 1) - $totalTables;
        }

        $reservation = Reservation::create([
            'reservation_date_time' => $this->date . ' ' . $this->availableTimeSlots,
            'customer_id' => customer()->id,
            'branch_id' => $this->shopBranch->id,
            'party_size' => $this->numberOfGuests,
            'reservation_slot_type' => $this->slotType,
            'reservation_status' => $this->restaurant->default_table_reservation_status,
            'waiting_number' => $waitingNumber,

            'special_requests' => $this->specialRequest
        ]);

        try {
            customer()->notify(new ReservationConfirmation($reservation));
        } catch (\Exception $e) {
            \Log::error('Error sending reservation confirmation email: ' . $e->getMessage());
        }

        ReservationReceived::dispatch($reservation);

        $this->alert('success', __('messages.reservationConfirmed'), [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);
        $encryptedNumber = Crypt::encryptString($reservation->waiting_number);


        // إعادة التوجيه مع رقم الانتظار
        $this->redirect(
            route('my_bookings', [
                'hash' => $this->restaurant->hash,
                'waiting_number' => $encryptedNumber
            ]),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.shop.book-a-table');
    }
}
