
@extends('layouts.guest')

@section('content')

<section class="bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 dir-rtl" dir="rtl">
  <div class="max-w-4xl mx-auto">
    @lang('modules.settings.aboutUsSettings')
    <!-- Hero Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 sm:p-12 mb-8 text-center">
      <span class="inline-block px-4 py-1.5 bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 font-semibold text-xs rounded-full mb-4">
        ✨ قصة شغف ولذة
      </span>
      <h1 class="text-3xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
        من نحن — لافالوش
      </h1>
      <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
        نحن لا نُقدم مجرد حلى وقهوة.. نحن نبتكر لحظات من السعادة اليومية والمذاق الذي يُحفر في الذاكرة.
      </p>
    </div>

    <!-- Story Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10 text-right space-y-8 text-gray-700 dark:text-gray-300">

      <!-- Story Section -->
      <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
          عن لافالوش (LAVA & LUSH)
        </h2>
        <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed">
          بدأت رحلة <strong class="text-gray-900 dark:text-white">لافالوش</strong> من شغف حقيقي بتقديم تجربة استثنائية لمُحبي الحلى والقهوة المختصة. نحرص على إعداد كعكات التشيز كيك الغنية والكوكيز الهش الطازج يومياً، باستخدام أجود المكونات الطبيعية والمختارة بعناية فائقة لضمان طعم لا يُنسى في كل قطعة.
        </p>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Values Grid -->
      <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          لماذا يثق بنا عملاؤنا؟
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
          <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-100 dark:border-gray-700/50 space-y-2">
            <div class="text-2xl">🍰</div>
            <h3 class="font-bold text-gray-900 dark:text-white">جودة بلا المساومة</h3>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
              نحضر منتجاتنا يومياً بخلطاتنا الخاصة ومكونات عالية الجودة لنضمن لك النكهة القوية والقوام المثالي.
            </p>
          </div>

          <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-100 dark:border-gray-700/50 space-y-2">
            <div class="text-2xl">☕</div>
            <h3 class="font-bold text-gray-900 dark:text-white">قهوة موزونة بعناية</h3>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
              محاصيل قهوة مختارة ومحموصة بدقة لتتكامل بانسجام تام مع أصناف الحلى المختلفة.
            </p>
          </div>

          <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-100 dark:border-gray-700/50 space-y-2">
            <div class="text-2xl">🛡️</div>
            <h3 class="font-bold text-gray-900 dark:text-white">معايير سلامة صارمة</h3>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
              نلتزم بأعلى معايير النظافة والسلامة الغذائية والتغليف المحكم ليصلك الطلب بحالته الأصلية.
            </p>
          </div>

          <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-100 dark:border-gray-700/50 space-y-2">
            <div class="text-2xl">🚀</div>
            <h3 class="font-bold text-gray-900 dark:text-white">خدمة توصيل سريعة</h3>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
              نهتم بالتفاصيل اللوجستية لضمان وصول طلبك طازجاً وفي أسرع وقت ممكن.
            </p>
          </div>
        </div>
      </div>

      <!-- Trust Assurance Box -->
      <div class="p-6 bg-red-50 dark:bg-red-950/30 border-r-4 border-red-500 rounded-xl space-y-2">
        <h3 class="text-lg font-bold text-red-900 dark:text-red-200">
          وعدنا لك
        </h3>
        <p class="text-sm text-red-800 dark:text-red-300 leading-relaxed">
          في <strong class="font-bold">لافالوش</strong>، رضاك هو محركنا الأول. إن لم تعش التجربة بالمستوى الذي تتوقعه، نحن هنا دائماً لسماع ملاحظاتك ومعالجة أي استفسار فوراً عبر قنوات الدعم المباشرة.
        </p>
      </div>

    </div>

  </div>
</section>

@endsection