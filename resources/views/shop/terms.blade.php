@extends('layouts.guest')

@section('content')

<section class="bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 dir-rtl" dir="rtl">
  <div class="max-w-4xl mx-auto">
    
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10 mb-8 text-right">
      <div class="inline-flex items-center justify-center p-3 bg-red-50 dark:bg-red-950 text-red-600 dark:text-red-400 rounded-xl mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
        سياسة الخصوصية والشروط والأحكام
      </h1>
      <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        شركة لافالوش (LAVA & LUSH) • آخر تحديث: {{ date('Y/m/d') }}
      </p>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10 text-right space-y-8 text-gray-700 dark:text-gray-300">

      <!-- Intro -->
      <p class="text-lg leading-relaxed text-gray-600 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 pb-6">
        في <span class="font-bold text-gray-900 dark:text-white">لافالوش (LAVA & LUSH)</span> نولي خصوصية عملائنا أعلى درجات الأهمية، ونلتزم بحماية كافة البيانات الشخصية المُجمّعة. توضح هذه السياسة آلية الجمع والاستخدام والتخزين لضمان تجربة تسوق آمنة وموثوقة.
      </p>

      <!-- Section 1 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          1. البيانات التي نقوم بجمعها
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
          عند تصفحك للموقع أو إتمام طلب شراء، قد نجمع البيانات التالية لخدمتك بشكل أفضل:
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
          <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700/50 text-sm">
            📌 <strong class="text-gray-900 dark:text-white">المعلومات الشخصية:</strong> الاسم، البريد، ورقم الهاتف.
          </div>
          <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700/50 text-sm">
            📍 <strong class="text-gray-900 dark:text-white">العناوين:</strong> عنوان التوصيل والفوترة.
          </div>
          <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700/50 text-sm">
            💳 <strong class="text-gray-900 dark:text-white">البيانات المالية:</strong> تفاصيل الدفع وسجل الطلبات.
          </div>
          <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700/50 text-sm">
            💻 <strong class="text-gray-900 dark:text-white">معلومات تقنية:</strong> عنوان IP، المتصفح، ونوع الجهاز.
          </div>
        </div>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 2 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          2. كيف نستخدم معلوماتك؟
        </h2>
        <ul class="space-y-2 text-sm leading-relaxed pr-2">
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>معالجة الطلبات وإتمام عمليات التوصيل والشحن.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>التواصل المباشر معك بشأن مستجدات طلبك أو الاستفسارات.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>تحسين أداء المتجر الإلكتروني وتجربة المستخدم.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>إرسال التحديثات والعروض الترويجية (في حال الموافقة).</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>تعزيز الحماية البرمجية وتفادي عمليات الاحتيال.</span>
          </li>
        </ul>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 3 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          3. حماية وأمن البيانات
        </h2>
        <p class="text-sm leading-relaxed">
          نطبق أعلى معايير الأمان التقني والتنظيمي عبر مشفرات SSL المعتمدة، وحصر الوصول للبيانات على الفنيين المورّدين المباشرين، مراعاةً لحماية الخصوصية.
        </p>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 4 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          4. مشاركة البيانات مع أطراف ثالثة
        </h2>
        <p class="text-sm leading-relaxed">
          لا نبيع أو نؤجر معلوماتك بتاتاً. تتم مشاركة البيانات فقط مع شركات الشحن ومزودي بوابات الدفع الإلكتروني لإتمام الخدمة، أو للجهات الرسمية بحسب الأنظمة.
        </p>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 5 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          5. ملفات تعريف الارتباط (Cookies)
        </h2>
        <p class="text-sm leading-relaxed">
          نستخدم الكوكيز لتسهيل التصفح وتذكر تفضيلاتك في الزيارات القادمة. يمكنك إيقافها دائماً عبر إعدادات متصفحك.
        </p>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 6 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          6. حقوقك كعميل
        </h2>
        <p class="text-sm leading-relaxed">
          يحق لك الاطلاع على بياناتك المخزنة، طلب تعديلها، طلب حذفها نهائياً، أو إلغاء الاشتراك في القوائم البريدية والتسويقية في أي وقت.
        </p>
      </div>

      <!-- Highlighted Notice Box (Section 7: Terms Update) -->
      <div class="p-5 bg-amber-50 dark:bg-amber-950/40 border-r-4 border-amber-500 rounded-xl space-y-2">
        <h2 class="text-lg font-bold text-amber-900 dark:text-amber-200">
          7. تعديل الشروط والأحكام والسياسة
        </h2>
        <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">
          تحتفظ شركة <strong class="font-bold">لافالوش (LAVA & LUSH)</strong> بالحق الكامل في تعديل، تحديث، أو تغيير كافة بنود هذه الاتفاقية والسياسات بشكل مستمر وفي أي وقت وفق ما تقتضيه مصلحة العمل والأنظمة المعمول بها. وتصبح هذه التعديلات نافذة بمجرد نشرها على هذه الصفحة، ويعتبر استمرارك في استخدام الموقع موافقة صريحة منك على السياسة المحدثة.
        </p>
      </div>

      <!-- Section 8 -->
      <div class="space-y-3 pt-2">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          8. التواصل معنا
        </h2>
        <p class="text-sm leading-relaxed">
          لأي استفسارات بخصوص الخصوصية والشروط والأحكام، يسرنا تواصلك مع فريق خدمة العملاء في <strong class="text-gray-900 dark:text-white">لافالوش</strong> من خلال وسائل التواصل المتاحة في المتجر.
        </p>
      </div>

    </div>

  </div>
</section>

@endsection