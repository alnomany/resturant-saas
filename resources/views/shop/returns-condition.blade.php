@extends('layouts.guest')

@section('content')

<section class="bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 dir-rtl" dir="rtl">
  <div class="max-w-4xl mx-auto">
    
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10 mb-8 text-right">
      <div class="inline-flex items-center justify-center p-3 bg-red-50 dark:bg-red-950 text-red-600 dark:text-red-400 rounded-xl mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
        الشروط والأحكام وسياسة الاستبدال والاسترجاع
      </h1>
      <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        متجر لافالوش (LAVA & LUSH) • آخر تحديث: {{ date('Y/m/d') }}
      </p>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-10 text-right space-y-8 text-gray-700 dark:text-gray-300">

      <!-- Intro -->
      <p class="text-lg leading-relaxed text-gray-600 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700 pb-6">
        أهلاً بك في متجر <span class="font-bold text-gray-900 dark:text-white">لافالوش (LAVA & LUSH)</span>. تُحدد هذه الشروط والأحكام القواعد واللوائح التنظيمية لاستخدام متجرنا وشراء المنتجات (الحلى والقهوة والمشاريب). باستخدامك للموقع أو الشراء منه، فإنك توافق التامة على الالتزام بهذه الشروط.
      </p>

      <!-- Important Notice Box (Allergens & Food Safety) -->
      <div class="p-5 bg-red-50 dark:bg-red-950/40 border-r-4 border-red-500 rounded-xl space-y-2">
        <h2 class="text-lg font-bold text-red-900 dark:text-red-200 flex items-center gap-2">
          ⚠️ إخلاء مسؤولية مهم (الحساسية الغذائية)
        </h2>
        <p class="text-sm text-red-800 dark:text-red-300 leading-relaxed">
          تحتوي معظم منتجاتنا من الحلى والقهوة على مسببات الحساسية مثل (المكسرات، الحليب والألبان، الجلوتين، البيض، والسمسم). يتحمل العميل المسؤولية الكاملة عن مراجعة مكونات المنتج والتأكد من ملاءمتها حال وجود أي حالة صحية أو حساسية غذائية قبل إتمام الطلب.
        </p>
      </div>

      <!-- Section 1 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          1. الطلبات والتعديل والإلغاء
        </h2>
        <ul class="space-y-2 text-sm leading-relaxed pr-2">
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>نظراً لكون المنتجات طازجة ويتم تحضيرها فور الطلب، يُسمح بإلغاء أو تعديل الطلب خلال <strong>15 دقيقة فقط</strong> من وقت إرساله.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>في حال البدء في تحضير الطلب أو تسليمه للمندوب، لا يمكن إلغاء الطلب أو استرداد المبالغ المدفوعة.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>يحتفظ المتجر بحق رفض أو إلغاء أي طلب لأسباب تشغيلية أو عدم توفر المواد، مع إعادة المبلغ كاملاً للعميل.</span>
          </li>
        </ul>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 2: Return Policy for Food -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          2. سياسة الاستبدال والاسترجاع
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
          وفقاً للأنظمة واللوائح الخاصة بحماية المستهلك للمنتجات الاستهلاكية وسريعة التلف (الأغذية والمشروبات):
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
          <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700/50 text-sm">
            ❌ <strong class="text-gray-900 dark:text-white block mb-1">عدم الاسترجاع للأغذية:</strong>
            لا يحق للعميل استرجاع أو استبدال المنتجات الغذائية بعد خروجها للتوصيل حرصاً على الصحة والسلامة العامة.
          </div>
          <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700/50 text-sm">
            ✅ <strong class="text-gray-900 dark:text-white block mb-1">حالات الاستثناء والتلويض:</strong>
            يتم التعويض (إعادة الطلب أو كود خصم/استرداد) فقط في حال وصول المنتجات تالفة أو وجود خطأ بالطلب.
          </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
          * يُشترط للإبلاغ عن أي تلف أو خطأ التنسيق التواصل مع خدمة العملاء وتزويدنا بصورة للمنتج خلال <strong>ساعة واحدة</strong> كحد أقصى من وقت الاستلام.
        </p>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 3 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          3. الشحن والتوصيل
        </h2>
        <ul class="space-y-2 text-sm leading-relaxed pr-2">
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>يلتزم العميل بتزويدنا بعنوان دقيق ورقم جوال يعمل بوضوح لتسهيل عمل مندوب التوصيل.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>في حال عدم تجاوب العميل مع المندوب أو التأخر عن استلام الطلب لأكثر من 15 دقيقة، يُعتبر الطلب ملغياً ولا يحق للعميل المطالبة بالتعويض نظراً لتلف المنتجات.</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-red-500 font-bold">•</span>
            <span>نعمل جاهدين للتوصيل في الوقت المحدد، وقد تحدث تأخيرات بسيطة ناتجة عن الظروف الجوية أو الازدحام المروري.</span>
          </li>
        </ul>
      </div>

      <hr class="border-gray-100 dark:border-gray-700" />

      <!-- Section 4 -->
      <div class="space-y-3">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          4. الأسعار والمدفوعات
        </h2>
        <p class="text-sm leading-relaxed">
          جميع الأسعار الموضحة على المتجر الإلكتروني تشمل ضريبة القيمة المضافة (VAT) المعمول بها في المملكة العربية السعودية. ونوفر وسائل دفع إلكترونية آمنة ومعتمدة.
        </p>
      </div>

      <!-- Dynamic Terms Notice Box -->
      <div class="p-5 bg-amber-50 dark:bg-amber-950/40 border-r-4 border-amber-500 rounded-xl space-y-2">
        <h2 class="text-lg font-bold text-amber-900 dark:text-amber-200">
          5. تحديث الشروط والأحكام
        </h2>
        <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">
          تحتفظ إدارة <strong class="font-bold">لافالوش (LAVA & LUSH)</strong> بالحق في تعديل أو تحديث هذه الشروط والأحكام وسياسات المتجر بشكل مستمر دون إشعار مسبق. وتعتبر التحديثات سارية فور نشرها على هذه الصفحة، واستمرارك في الشراء يعبر عن موافقتك الصريحة على الشروط المحدثة.
        </p>
      </div>

      <!-- Section 6 -->
      <div class="space-y-3 pt-2">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
          6. التواصل والدعم
        </h2>
        <p class="text-sm leading-relaxed">
          فريق <strong class="text-gray-900 dark:text-white">لافالوش</strong> في خدمتكم دائماً. لأي استفسار أو شكوى بخصوص الطلبات، يمكنك التواصل معنا عبر قنوات الدعم المتاحة على المتجر.
        </p>
      </div>

    </div>

  </div>
</section>

@endsection