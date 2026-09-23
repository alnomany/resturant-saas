{{----}}
            @if($usePoints)
                <div class="pt-3 border-t border-amber-200/60 mt-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">الخصم المطبق من النقاط:</span>
                        <span class="font-bold text-emerald-600">-{{ number_format($pointsDiscount, 2) }} {{ $this->restaurant->currency->currency_code ?? 'ر.س' }}</span>
                    </div>
                </div>
            @endif