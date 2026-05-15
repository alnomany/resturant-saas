<?php

// نلتقط القيم من الرابط
$utm_source = $_GET['utm_source'] ?? 'direct';
$utm_camp = $_GET['utm_camp'] ?? 'none';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="نظام نقاط بيع, منيو الكتروني, مطاعم, طلبات اونلاين, POS, السعودية">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="منيوي — نظام إدارة المطاعم الذكي">
  <meta property="og:description" content="حل متكامل لإدارة المطاعم، الطلبات، الدفع، والتقارير.">
  <meta property="og:image" content="https://example.com/og-image.jpg">
  <meta property="og:url" content="https://example.com">
  <meta name="twitter:card" content="summary_large_image">

  <title>منيوي — حل المطاعم الذكي</title>
  <meta
    name="description"
    content="منيوي: منيو إلكتروني، طلبات أونلاين، ونظام نقاط بيع ذكي للمطاعم في السعودية" />

  <!-- Google Font (Cairo) -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap"
    rel="stylesheet" />

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            cairo: ["Cairo", "sans-serif"],
          },
          colors: {
            primary: "#0ea5a3",
            accent: "#ff7a59",
          },
        },
      },
    };
  </script>

  <!-- GSAP for animations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

  <style>
    html,
    body {
      font-family: cairo;
    }

    /* subtle glass backdrop for header */
    .glass {
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(6px);
    }

    /* subtle card shadow */
    .card-shadow {
      box-shadow: 0 8px 30px rgba(16, 24, 40, 0.14);
    }

    /* price ribbon */
    .ribbon {
      transform: rotate(-12deg);
    }

    /* RTL specific minor tweaks */
    .rtl-flow {
      direction: rtl;
    }

    /* ahmed */
    /* تحريك الشعار بشكل مستمر */
    @keyframes scroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(calc(-250px * 7));
      }

      /* عدد الشعارات × عرض الشعار */
    }

    /* الحركة الأساسية */
    @keyframes scroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(calc(-200px * 7));
      }

      /* القيمة الافتراضية للجوال */
    }

    /* للشاشات الكبيرة (الكمبيوتر) */
    @media (min-width: 768px) {
      @keyframes scroll {
        0% {
          transform: translateX(0);
        }

        100% {
          transform: translateX(calc(-300px * 7));
        }
      }

      .slide {
        width: 300px !important;
      }

      .slider-track {
        width: calc(300px * 14) !important;
      }
    }

    .slider-container {
      overflow: hidden;
      position: relative;
      width: 100%;
      padding: 10px 0;
      direction: ltr;
      /* لضمان اتجاه الحركة من اليمين لليسار بشكل صحيح */
    }

    .slider-container::before,
    .slider-container::after {
      content: "";
      height: 100%;
      position: absolute;
      width: 150px;
      z-index: 2;
      pointer-events: none;
    }

    /* تظليل جانبي لإعطاء تأثير تلاشي عند الأطراف */
    .slider-container::before {
      left: 0;
      top: 0;
      background: linear-gradient(to right, rgba(3, 7, 18, 1) 0%, rgba(3, 7, 18, 0) 100%);
    }

    .slider-container::after {
      right: 0;
      top: 0;
      background: linear-gradient(to left, rgba(3, 7, 18, 1) 0%, rgba(3, 7, 18, 0) 100%);
    }

    .slider-track {
      display: flex;
      /* العرض هنا = (عرض الشعار الجديد 300px * عدد الشعارات الكلي) */
      width: calc(300px * 14);
      animation: scroll 25s linear infinite;
      /* سرعة الحركة (أقل = أسرع) */
    }

    .slide {
      width: 300px;
      /* زدنا العرض من 250 إلى 300 */
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      /* مسافة داخلية */
    }

    .slider-track:hover {
      animation-play-state: paused;
      /* توقف الحركة عند مرور الماوس */
    }
  </style>
</head>

<body
  class="bg-gradient-to-b from-gray-950 via-gray-900 to-gray-800 text-gray-100 leading-relaxed">
  <!-- NAVBAR -->
  <header id="main-header" class="fixed top-0 inset-x-0 z-[100] transition-all duration-500 py-4">
    <div class="container mx-auto px-4 md:px-6">
      <nav class="relative flex items-center justify-between bg-gray-950/20 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/5 shadow-2xl transition-all duration-500 header-nav">

        <div class="flex items-center gap-4 group cursor-pointer">
          <div class="relative">
            <img src="logo.svg" width="45" height="45" class="group-hover:rotate-12 transition-transform duration-300" />
            <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
          </div>
          <div class="flex flex-col">
            <span class="text-xl font-black tracking-tighter text-white">EMLHOR <span class="text-primary text-xs ml-1 font-medium">POS</span></span>
            <span class="text-[10px] text-gray-400 font-medium leading-none">مطعمك.. في جيبك</span>
          </div>
        </div>

        <div class="hidden md:flex items-center gap-8">
          <a href="#features" class="text-sm font-bold text-gray-300 hover:text-primary transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:right-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-full">المميزات</a>
          <a href="#pricing" class="text-sm font-bold text-gray-300 hover:text-primary transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:right-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-full">الأسعار</a>
          <a href="#contact" class="text-sm font-bold text-gray-300 hover:text-primary transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:right-0 after:w-0 after:h-0.5 after:bg-primary after:transition-all hover:after:w-full">تواصل معنا</a>

          <div class="h-6 w-[1px] bg-white/10 mx-2"></div>

          <a href="https://wa.me/966541749461" target="_blank" class="relative group">
            <button class="bg-primary text-gray-900 px-6 py-2.5 rounded-xl font-black text-sm shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
              ابدأ الآن
            </button>
          </a>
        </div>

        <div class="md:hidden">
          <button id="mobile-menu-btn" class="p-2 text-white hover:bg-white/5 rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </nav>
    </div>
  </header>

  <!-- MOBILE MENU -->
  <div
    id="mobile-menu"
    class="fixed top-20 right-4 left-4 bg-gray-900/60 backdrop-blur rounded-xl p-4 hidden z-40">
    <a href="#features" class="block py-2">المميزات</a>
    <a href="#pricing" class="block py-2">الأسعار</a>
    <a href="#contact" class="block py-2">تواصل</a>
    <button
      id="cta-mobile"
      class="mt-3 w-full bg-primary text-gray-900 py-2 rounded-lg">
      ابدأ تجربتك
    </button>
  </div>

  <!-- HERO -->

  <section class="relative min-h-screen flex items-center pt-20 overflow-hidden text-right">

    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-accent/10 rounded-full blur-[100px] -z-10"></div>

    <div class="container mx-auto px-6 relative">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        <div class="hero-content-reveal">
          <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
            </span>
            <span class="text-sm font-medium text-gray-300">نظامنا يتحدث لغة مطعمك 🇸🇦</span>
          </div>

          <h1 class="text-4xl md:text-7xl font-black leading-[1.1] mb-6 text-white">
            حوّل مطعمك إلى <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-accent to-primary bg-[length:200%_auto] animate-gradient-text">
              منظومة ذكية
            </span>
          </h1>

          <p class="text-lg md:text-xl text-gray-400 max-w-xl leading-relaxed mb-10">
            لا تشغل بالك بالتقنية.. تفرّغ لإبداع أطباقك واترك لنا إدارة المنيو، الطلبات، والحجوزات بنظام واحد <span class="text-white font-bold">بسيط لدرجة المذهلة.</span>
          </p>

          <div class="flex flex-wrap gap-5 items-center">
            <a
              href="https://wa.me/966541749461"
              target="_blank"
              class="cta-button group relative px-8 py-4 bg-primary text-gray-900 font-black rounded-2xl overflow-hidden transition-all hover:scale-105 active:scale-95 inline-block">
              <span class="relative z-10 flex items-center gap-2">
                ابدأ تجربتك المجانية
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-[-5px] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
              </span>
              <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
            </a>

            <a href="#demo" class="group flex items-center gap-3 text-white font-bold">
              <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white/10 transition-all">
                <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z" />
                </svg>
              </div>
              شاهد النظام في عمله
            </a>
          </div>

          <div class="mt-12 pt-8 border-t border-white/5 flex items-center gap-8 opacity-60">
            <div class="text-sm">مدعوم من هيئة الزكاة ✅</div>
            <div class="text-sm">سحابة سعودية آمنة ☁️</div>
          </div>
        </div>

        <div class="relative lg:h-[600px] flex items-center justify-center hero-visual-container">
          <div class="relative z-20 w-full max-w-[450px] bg-gradient-to-br from-gray-800 to-gray-950 p-6 rounded-[2.5rem] border border-white/10 shadow-2xl main-card-anim">
            <div class="flex justify-between items-center mb-8">
              <div class="h-8 w-24 bg-white/5 rounded-lg"></div>
              <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                <div class="w-5 h-5 rounded-full bg-primary animate-pulse"></div>
              </div>
            </div>
            <div class="space-y-4">
              <div class="h-24 w-full bg-white/5 rounded-2xl p-4 border border-white/5">
                <div class="flex justify-between text-xs text-gray-500 mb-2"><span>مبيعات اليوم</span><span>+12%</span></div>
                <div class="text-2xl font-black text-white">8,420.00 ر.س</div>
                <div class="w-full h-2 bg-gray-700 rounded-full mt-3 overflow-hidden">
                  <div class="w-3/4 h-full bg-primary rounded-full"></div>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="h-20 bg-white/5 rounded-2xl border border-white/5 p-3">
                  <div class="text-[10px] text-gray-500">نشط الآن</div>
                  <div class="text-xl font-bold">14 طاولة</div>
                </div>
                <div class="h-20 bg-white/5 rounded-2xl border border-white/5 p-3">
                  <div class="text-[10px] text-gray-500">الطلبات</div>
                  <div class="text-xl font-bold">42 طلب</div>
                </div>
              </div>
            </div>
          </div>

          <div class="absolute -top-10 -right-10 z-30 bg-white p-4 rounded-3xl shadow-2xl float-anim-1">
            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-primary">
              <svg class="w-12 h-12 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 3h4v4H3V3zm0 7h4v4H3v-4zm7-7h4v4h-4V3zm7 0h4v4h-4V3zM3 17h4v4H3v-4zm7-7h4v4h-4v-4zm7 0h4v4h-4v-4zm-7 7h4v4h-4v-4zm7 0h4v4h-4v-4z" />
              </svg>
            </div>
            <div class="text-[10px] font-bold text-gray-900 mt-2 text-center">SCAN & PAY</div>
          </div>

          <div class="absolute top-1/2 -left-20 z-30 bg-accent p-4 rounded-2xl shadow-xl float-anim-2 max-w-[180px]">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-xs">!</div>
              <div class="text-white text-[11px] font-bold">طلب جديد: طاولة #9</div>
            </div>
          </div>

          <div class="absolute -bottom-5 -right-5 z-30 bg-gray-900 border border-primary/30 p-4 rounded-2xl shadow-xl float-anim-3">
            <div class="flex items-center gap-3">
              <div class="text-primary font-black text-xl">+50</div>
              <div class="text-gray-400 text-[10px]">نقطة ولاء للعميل</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <style>
    @keyframes gradient-text {
      to {
        background-position: 200% center;
      }
    }

    .animate-gradient-text {
      background-size: 200% auto;
      animation: gradient-text 3s linear infinite;
    }
  </style>

  <section id="features" class="py-24 bg-[#F9FAFB] text-right overflow-hidden">
    <div class="container mx-auto px-6">

      <div class="text-center mb-20 scroll-reveal">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">كل ما تحتاجه لإدارة <span class="text-primary">مطعمك</span></h2>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed">
          من اللحظة التي يحجز فيها العميل طاولته، وحتى خروجه وهو يخطط للعودة مرة أخرى.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        <div class="md:col-span-7 bg-white p-10 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 group feature-card relative overflow-hidden">
          <div class="relative z-10">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">نظام حجز الطاولات الذكي</h3>
            <p class="text-gray-600 leading-relaxed mb-6">نظم صالة مطعمك بذكاء. اسمح لعملائك بحجز طاولاتهم المفضلة مسبقاً عبر الموقع، مع إرسال تأكيدات فورية عبر WhatsApp.</p>
            <div class="flex gap-4">
              <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">خريطة طاولات تفاعلية</span>
              <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">تنبيهات تلقائية</span>
            </div>
          </div>
          <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="md:col-span-5 bg-gradient-to-br from-primary to-emerald-600 p-10 rounded-[2rem] text-white shadow-lg hover:shadow-primary/30 transition-all duration-500 group feature-card relative overflow-hidden">
          <div class="relative z-10">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2z" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">نظام الولاء والمكافآت</h3>
            <p class="text-emerald-50 leading-relaxed mb-8">حوّل العميل العابر إلى عميل دائم. نظام نقاط ذكي، كوبونات مخصصة، وعروض ترويجية مستهدفة تزيد من مبيعاتك.</p>
            <button class="bg-white text-primary font-bold py-3 px-6 rounded-xl hover:bg-gray-100 transition-colors">اكتشف كيف يعمل</button>
          </div>
        </div>

        <div class="md:col-span-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 feature-card text-center">
          <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">QR Menu فائق السرعة</h3>
          <p class="text-gray-500 text-sm">قائمة طعام رقمية تفاعلية بلمسة واحدة، تدعم الصور عالية الدقة والطلب المباشر.</p>
        </div>

        <div class="md:col-span-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 feature-card text-center">
          <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">تقارير فورية دقيقة</h3>
          <p class="text-gray-500 text-sm">اتخذ قراراتك بناءً على أرقام حقيقية. تابع المبيعات، المخزون، وأداء الموظفين بلحظتها.</p>
        </div>

        <div class="md:col-span-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 feature-card text-center">
          <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">نقاط بيع سحابية</h3>
          <p class="text-gray-500 text-sm">نظام POS مرن يعمل على أي جهاز، يدعم الدفع الإلكتروني والربط مع هيئة الزكاة والداخلية.</p>
        </div>

      </div>
    </div>
  </section>
  <section class="py-16 bg-gray-950/50">
    <div class="container mx-auto px-6 mb-10 text-center">
      <h2 class="text-2xl font-bold text-gray-300">شركاء النجاح</h2>
      <div class="w-20 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="slider-container">
      <div class="slider-track">


        <div class="slide">
          <img src="images/lava.png" class="h-22 grayscale  hover:grayscale-0 hover:opacity-100 transition-all duration-300" alt="Partner" />
        </div>
        <div class="slide">
          <img src="images/bur.png" class="h-22 grayscale  hover:grayscale-0 hover:opacity-100 transition-all duration-300" alt="Partner" />
        </div>
        <div class="slide">
          <img src="images/kbab.png" class="h-22 grayscale  hover:grayscale-0 hover:opacity-100 transition-all duration-300" alt="Partner" />
        </div>
        <div class="slide">
          <img src="images/coffe.png" class="h-22 grayscale  hover:grayscale-0 hover:opacity-100 transition-all duration-300" alt="Partner" />
        </div>
        <div class="slide">
          <img src="images/sh.png" class="h-22 grayscale  hover:grayscale-0 hover:opacity-100 transition-all duration-300" alt="Partner" />
        </div>

        <div class="slide"><img src="images/lava.png" class="h-22 grayscale opacity-50 hover:opacity-100 transition-all" /></div>

        <div class="slide"><img src="images/bur.png" class="h-22 grayscale opacity-50 hover:opacity-100 transition-all" /></div>
        <div class="slide"><img src="images/kbab.png" class="h-22 grayscale opacity-50 hover:opacity-100 transition-all" /></div>
        <div class="slide"><img src="images/coffe.png" class="h-22 grayscale opacity-50 hover:opacity-100 transition-all" /></div>
        <div class="slide"><img src="images/sh.png" class="h-22 grayscale opacity-50 hover:opacity-100 transition-all" /></div>
      </div>
    </div>
  </section>

  <!-- PRICING -->
  <span class="text-sm font-bold text-gray-900">فاتورة سنوية <span class="text-green-600 bg-green-100 px-2 py-0.5 rounded text-[10px]">خصم 20%</span></span>
  </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

    <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_10px_50px_-15px_rgba(0,0,0,0.05)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 pricing-card scroll-reveal">
      <h3 class="text-xl font-bold text-gray-900 mb-2">الباقة الأساسية</h3>
      <p class="text-gray-500 text-sm mb-6">للمطاعم الناشئة والشبابية</p>
      <div class="mb-8">
        <span class="text-4xl font-black text-gray-900">45</span>
        <span class="text-gray-500 font-bold">ر.س / شهر</span>
      </div>
      <ul class="space-y-4 mb-10 text-right">
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>نقاط بيع (POS) غير محدودة</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>منيو إلكتروني + QR ذكي</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>نظام استلام من الفرع</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>تقارير فورية دقيقة</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>خدمة الطلبات الداخلية للطاولات</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span> موقع الكتروني</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span> خدمة الاستلام من الفرع</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600 opacity-50">
          <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="line-through">حجوزات الطاولات</span>
        </li>
      </ul>
      <a href="https://wa.me/966541749461">

        <button class="w-full py-4 rounded-2xl border-2 border-gray-100 text-gray-900 font-bold hover:bg-gray-50 transition-colors">ابدأ تجربتك</button>
      </a>
    </div>

    <div class="relative bg-gray-900 p-12 rounded-[3rem] shadow-2xl shadow-primary/20 transform md:scale-110 z-20 pricing-card-featured scroll-reveal">
      <div class="absolute -top-5 inset-x-0 flex justify-center">
        <span class="bg-primary text-gray-900 text-xs font-black px-6 py-2 rounded-full uppercase tracking-tighter shadow-lg">الأكثر طلباً في الرياض</span>
      </div>

      <h3 class="text-2xl font-bold text-white mb-2">الباقة الاحترافية</h3>
      <p class="text-gray-400 text-sm mb-6">للمطاعم الطموحة والمتكاملة</p>
      <div class="mb-8">
        <span class="text-5xl font-black text-primary">95</span>
        <span class="text-gray-400 font-bold">ر.س / شهر</span>
      </div>
      <ul class="space-y-4 mb-10 text-right">
        <li class="flex items-center gap-3 text-white">
          <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="font-medium">جميع ميزات الأساسية</span>
        </li>
        <li class="flex items-center gap-3 text-white">
          <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="font-medium">نظام حجوزات الطاولات</span>
        </li>
        <li class="flex items-center gap-3 text-white">
          <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="font-medium">إدارة المطبخ (KDS)</span>
        </li>
        <li class="flex items-center gap-3 text-white">
          <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="font-medium">نظام الولاء والمكافآت</span>
        </li>
        <li class="flex items-center gap-3 text-white">
          <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span class="font-medium"> ادارة قوائم الانتظار</span>
        </li>

      </ul>

      <a href="https://wa.me/966541749461">
        <button class="w-full py-4 rounded-2xl bg-primary text-gray-900 font-black hover:bg-white transition-all shadow-xl shadow-primary/10">اشترك الآن</button>
      </a>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_10px_50px_-15px_rgba(0,0,0,0.05)] border border-gray-100 transition-all duration-500 hover:-translate-y-2 pricing-card scroll-reveal text-right">
      <h3 class="text-xl font-bold text-gray-900 mb-2">باقة الشركات</h3>
      <p class="text-gray-500 text-sm mb-6">للفروع المتعددة والفرنشايز</p>
      <div class="mb-8">
        <span class="text-3xl font-black text-gray-900">تواصل معنا</span>
      </div>
      <ul class="space-y-4 mb-10">
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>جميع ميزات الباقة الاساسية</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>جميع ميزات الباقة الاحترافية</span>
        </li>


        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>مدير حساب تقني مخصص</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>ربط API مخصص</span>
        </li>
        <li class="flex items-center gap-3 text-gray-600">
          <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
          </svg>
          <span>نظام الفروع </span>
        </li>
      </ul>
      <a href="https://wa.me/966541749461">

        <button class="w-full py-4 rounded-2xl border-2 border-gray-900 text-gray-900 font-bold hover:bg-gray-900 hover:text-white transition-all">تواصل مع المبيعات</button>
      </a>
    </div>

  </div>
  </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="py-20">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
          <h2 class="text-3xl font-extrabold">تواصل معنا</h2>
          <p class="mt-4 text-gray-400">
            هل عندك استفسار أو تحتاج مساعدة في إعداد مطعمك؟ فريق الدعم جاهز.
          </p>

          <div class="mt-6 space-y-4">
            <div class="p-4 bg-gray-900/40 rounded-xl">
              <div class="text-sm text-gray-300">البريد الإلكتروني</div>
              <a
                href="mailto:support@emlhor.com"
                class="font-bold hover:underline">
                support@emlhor.com
              </a>
            </div>

            <div class="p-4 bg-gray-900/40 rounded-xl">
              <div class="text-sm text-gray-300">واتساب الدعم</div>
              <div class="font-bold">
                <a href="https://wa.me/966541749461">966541749461</a>
              </div>
            </div>

            <div class="p-4 bg-gray-900/40 rounded-xl">
              <div class="text-sm text-gray-300">ساعات العمل</div>
              <div class="font-bold">9 صباحًا - 7 مساءً</div>
            </div>
          </div>
        </div>

        <form
          id="contact-form"
          class="p-6 rounded-2xl bg-gray-900/30 card-shadow">
          <label class="text-sm">الاسم</label>
          <input
            required
            class="mt-2 w-full p-3 rounded-lg bg-gray-800 border border-gray-700"
            placeholder="اسمك" />

          <label class="text-sm mt-4">البريد الإلكتروني</label>
          <input
            required
            type="email"
            class="mt-2 w-full p-3 rounded-lg bg-gray-800 border border-gray-700"
            placeholder="name@example.com" />

          <label class="text-sm mt-4">الرسالة</label>
          <textarea
            required
            class="mt-2 w-full p-3 rounded-lg bg-gray-800 border border-gray-700"
            rows="5"
            placeholder="أخبرنا كيف نساعدك..."></textarea>

          <button
            type="submit"
            class="mt-6 w-full bg-accent text-gray-900 py-3 rounded-lg font-bold">
            أرسل
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer
    class="py-10 text-center bg-gray-900 text-gray-400 relative overflow-hidden">
    <div class="container mx-auto px-6">
      <!-- أيقونات التواصل -->
      <div class="flex justify-center gap-6 mb-6">
        <!-- Instagram -->
        <a
          href="https://www.instagram.com/pos.emlhor1/"
          target="_blank"
          class="group relative flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-pink-500 to-yellow-400 text-white shadow-lg transition transform hover:scale-110 hover:shadow-[0_0_20px_rgba(236,72,153,0.8)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            viewBox="0 0 24 24"
            class="w-6 h-6 transition-transform group-hover:rotate-12">
            <path
              d="M7.75 2A5.75 5.75 0 0 0 2 7.75v8.5A5.75 5.75 0 0 0 7.75 22h8.5A5.75 5.75 0 0 0 22 16.25v-8.5A5.75 5.75 0 0 0 16.25 2h-8.5Zm8.5 1.5A4.25 4.25 0 0 1 20.5 7.75v8.5A4.25 4.25 0 0 1 16.25 20.5h-8.5A4.25 4.25 0 0 1 3.5 16.25v-8.5A4.25 4.25 0 0 1 7.75 3.5h8.5ZM12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 1.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7Zm4.25-.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
          </svg>
        </a>

        <!-- TikTok -->
        <a
          href="https://www.tiktok.com/@mubtakiroun"
          target="_blank"
          class="group relative flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-gray-800 to-gray-700 text-white shadow-lg transition transform hover:scale-110 hover:shadow-[0_0_20px_rgba(59,130,246,0.8)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            viewBox="0 0 24 24"
            class="w-6 h-6 transition-transform group-hover:rotate-12">
            <path
              d="M16 8.04a4.97 4.97 0 0 0 3 1.01V6.6a3.29 3.29 0 0 1-3-3.26h-2.1v10.2a2.1 2.1 0 1 1-2.1-2.1c.23 0 .44.04.65.1V8.8a4.21 4.21 0 0 0-.65-.06A4.2 4.2 0 1 0 16 12.94V8.04Z" />
          </svg>
        </a>

        <!-- X (Twitter سابقاً) 
          <a
            href="https://x.com/emlhor"
            target="_blank"
            class="group relative flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-gray-700 to-gray-600 text-white shadow-lg transition transform hover:scale-110 hover:shadow-[0_0_20px_rgba(255,255,255,0.8)]"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 24 24"
              class="w-6 h-6 transition-transform group-hover:rotate-12"
            >
              <path
                d="M18.36 2H21l-6.57 7.5L22 22h-5.94l-4.65-6.06L6.08 22H3.43l7-8L2 2h6l4.18 5.5L18.36 2Zm-1.05 18.33h1.18L8.87 4.24H7.55l9.76 16.09Z"
              />
            </svg>
          </a>
          -->
      </div>
      <!-- قسم التسويق بالعمولة -->
      <div
        class="card mx-auto max-w-xl mb-10 p-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md shadow-lg hover:shadow-cyan-500/20 transition">
        <h3 class="text-lg font-semibold text-white mb-2"> انضم إلى برنامج التسويق بالعمولة</h3>
        <p class="text-gray-400 mb-4 text-sm leading-relaxed">
          ابدأ في كسب عمولة تصل إلى <span class="text-white font-semibold">40%</span> من كل مطعم يشترك عن طريقك!
        </p>
        <a
          href="/affilate.php"
          class="inline-block px-6 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold hover:scale-105 transition-transform duration-300">
          اكتشف التفاصيل
        </a>
      </div>
      <!-- النص -->
      <div class="text-sm text-gray-500 hover:text-gray-300 transition">
        © 2025 Emlhor POS — جميع الحقوق محفوظة.
      </div>
    </div>

    <!-- خلفية متحركة خفيفة -->
    <div
      class="absolute inset-0 opacity-5 bg-[radial-gradient(circle_at_bottom_right,_#00ffa3_0%,_transparent_70%)] animate-pulse pointer-events-none"></div>
  </footer>

  <!-- MODAL / CTA FORM -->
  <div
    id="modal"
    class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-gray-900 rounded-2xl p-6 w-11/12 md:w-1/2">
      <h3 class="text-xl font-bold">ابدأ تجربتك المجانية</h3>
      <p class="mt-2 text-gray-400">
        سجّل الآن وابدأ تجربتك مجانًا!
      </p>
      <form
        id="modal-form"
        class="mt-4 space-y-3"
        action="submit.php"
        method="post">
        <input
          required
          class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700"
          placeholder="اسم المطعم"
          name="restaurant_name" />
        <input
          required
          class="w-full p-3 rounded-lg bg-gray-800 border border-gray-700"
          placeholder="رقم الجوال"
          name="phone" />
        <div class="flex gap-3">
          <a href="https://wa.me/966541749461"> <button
              type="submit"
              class="mt-2 bg-primary text-gray-900 px-4 py-2 rounded-lg font-bold">
              أطلب التجربة
            </button>
          </a>
          <button
            id="modal-close"
            type="button"
            class="mt-2 bg-gray-800 px-4 py-2 rounded-lg">
            إلغاء
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script>
    // Mobile menu toggle
    const mobileBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    mobileBtn?.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });

    // Modal logic
    const modal = document.getElementById("modal");
    const ctaHero = document.getElementById("cta-hero");
    const ctaTop = document.getElementById("cta-top");
    const ctaMobile = document.getElementById("cta-mobile");
    const modalClose = document.getElementById("modal-close");

    [ctaHero, ctaTop, ctaMobile].forEach((btn) => {
      btn?.addEventListener("click", () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
      });
    });
    modalClose?.addEventListener("click", () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    });

    // Simple GSAP entrance animations
    gsap.from("header", {
      y: -50,
      opacity: 0,
      duration: 0.8,
      ease: "power3.out",
    });
    gsap.from("section h1, section h2", {
      y: 20,
      opacity: 0,
      duration: 0.9,
      stagger: 0.12,
      ease: "power3.out",
    });
    gsap.from(".card-shadow", {
      y: 20,
      opacity: 1,
      duration: 1,
      stagger: 0.08,
      ease: "power3.out",
    });

    // Subtle floating animation for hero mockup
    gsap.to(".relative > .absolute", {
      y: -8,
      x: 6,
      duration: 3,
      yoyo: true,
      repeat: -1,
      ease: "sine.inOut",
    });

    // Contact form submit (fake) with feedback
    document
      .getElementById("contact-form")
      ?.addEventListener("submit", (e) => {
        e.preventDefault();
        alert("شكرًا! تم إرسال رسالتك. سنتواصل معك قريبًا.");
        e.target.reset();
      });

    document.getElementById("modal-form")?.addEventListener("submit", async (e) => {
      e.preventDefault(); // نمنع الإرسال التقليدي

      const form = e.target;
      const data = new FormData(form);

      try {
        const response = await fetch('submit.php', {
          method: 'POST',
          body: data
        });

        const text = await response.text();
        // يمكن إظهار رسالة شكر أو محتوى PHP مباشرة
        alert("تم حفظ بياناتك! شكراً لتسجيل مطعمك.");

        // اغلاق المودال
        const modal = document.getElementById("modal");
        if (modal) {
          modal.classList.add("hidden");
          modal.classList.remove("flex");
        }

        form.reset();

      } catch (err) {
        console.error(err);
        alert("حصل خطأ أثناء حفظ البيانات. حاول لاحقاً.");
      }
    });
  </script>
  <a
    href="https://wa.me/966541749461"
    target="_blank"
    class="fixed bottom-6 right-6 bg-green-500 text-white p-3 rounded-full shadow-lg hover:scale-110 transition transform duration-300 animate-bounce"
    aria-label="تواصل عبر واتساب">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      fill="currentColor"
      viewBox="0 0 24 24"
      class="w-7 h-7">
      <path
        d="M20.52 3.48A11.85 11.85 0 0 0 12 0a11.85 11.85 0 0 0-8.52 3.48A11.85 11.85 0 0 0 0 12c0 2.12.56 4.2 1.63 6.03L0 24l6.14-1.6A11.93 11.93 0 0 0 12 24a11.85 11.85 0 0 0 8.52-3.48A11.85 11.85 0 0 0 24 12a11.85 11.85 0 0 0-3.48-8.52ZM12 21.5a9.44 9.44 0 0 1-4.82-1.32l-.34-.2-3.63.95.97-3.54-.22-.36a9.46 9.46 0 0 1-1.46-5A9.5 9.5 0 0 1 12 2.5a9.47 9.47 0 0 1 9.5 9.5A9.47 9.47 0 0 1 12 21.5Zm5.4-7.35-.85-.42c-.44-.22-.93-.48-1.47-.2-.38.2-.62.68-.86 1.03-.13.2-.28.23-.52.13a7.75 7.75 0 0 1-3.65-3.15c-.15-.25-.02-.38.11-.5.3-.27.68-.7.77-1.16.08-.42-.12-.9-.33-1.26-.23-.4-.48-.97-.87-1.18-.4-.22-.9-.1-1.25.16-.57.4-.9 1.04-.94 1.73-.08 1.38.85 2.82 1.77 3.83a9.48 9.48 0 0 0 4.53 3.07c.64.2 1.26.4 1.93.25.7-.16 1.55-.63 1.78-1.3.18-.48.18-.9.12-1.08-.08-.18-.28-.27-.48-.36Z" />
    </svg>
  </a>

  <!-- كود التتبع الخفي -->
  <img id="tracker" src="" style="display:none;">

  <script>
    // نرسل بيانات البداية إلى track.php
    const startTime = Date.now();
    const ipTracker = document.getElementById('tracker');
    const utm_source = "<?= $utm_source ?>";
    const utm_camp = "<?= $utm_camp ?>";

    window.addEventListener("beforeunload", () => {
      const duration = Math.round((Date.now() - startTime) / 1000);
      const trackUrl = `track.php?utm_source=${utm_source}&utm_camp=${utm_camp}&duration=${duration}`;
      navigator.sendBeacon(trackUrl);
    });

    // تسجيل أولي للدخول
    ipTracker.src = `track.php?utm_source=${utm_source}&utm_camp=${utm_camp}`;
    // --- FEATURES SECTION SCROLL ANIMATIONS (GSAP) ---

    // تأكد من تحميل مكتبة ScrollTrigger أولاً
    gsap.registerPlugin(ScrollTrigger);

    // 1. حركة العنوان والوصف عند الظهور
    gsap.from(".scroll-anim-title", {
      y: 30,
      opacity: 0,
      duration: 1,
      ease: "power3.out",
      scrollTrigger: {
        trigger: "#features", // العنصر الذي يفعل الحركة
        start: "top 85%", // يبدأ عندما يصل أعلى القسم إلى 85% من الشاشة
        toggleActions: "play none none reverse" // يلعب الحركة عند الدخول ويعكسها عند الخروج
      }
    });

    // 2. حركة البطاقات Bento Grid (Staggered Animation)
    // تجعل البطاقات تظهر واحدة تلو الأخرى بشكل متتابع
    gsap.from(".feature-card-anim", {
      y: 50,
      opacity: 0,
      duration: 1.2,
      stagger: 0.15, // التأخير بين ظهور كل بطاقة
      ease: "power4.out",
      scrollTrigger: {
        trigger: ".grid-cols-1.md\\:grid-cols-6", // يستهدف الشبكة نفسها
        start: "top 80%",
        toggleActions: "play none none reverse"
      }
    });

    // 3. حركة الأيقونات والمجسمات التوضيحية داخل البطاقات
    gsap.from(".feature-icon-anim, .feature-mockup-anim", {
      scale: 0.8,
      opacity: 0,
      duration: 1,
      delay: 0.4, // تأخير طفيف بعد ظهور البطاقة
      stagger: 0.2,
      ease: "back.out(1.7)", // تأثير ارتداد بسيط
      scrollTrigger: {
        trigger: ".grid-cols-1.md\\:grid-cols-6",
        start: "top 75%",
        toggleActions: "play none none reverse"
      }
    });

    // 4. تأثير Glow الخلفي البسيط عند التمرير
    // يجعل خلفية القسم تلمع قليلاً مع Scroll
    gsap.to(".hover-glow-target", {
      opacity: 0.1,
      scale: 1.05,
      duration: 3,
      yoyo: true, // يعكس الحركة باستمرار
      repeat: -1, // تكرار لا نهائي
      ease: "sine.inOut",
      scrollTrigger: {
        trigger: "#features",
        start: "top bottom", // يبدأ من مجرد دخول القسم للشاشة
        end: "bottom top", // ينتهي عند خروجه
        scrub: true // يربط الحركة مباشرة بالـ Scroll (Scrubaffect)
      }
    });
    //price 

    // --- PRICING SECTION ANIMATIONS ---

    // ظهور الباقات بتتابع (Stagger)
    gsap.from(".pricing-card", {
      y: 60,
      opacity: 0,
      duration: 1,
      stagger: 0.2,
      ease: "power3.out",
      scrollTrigger: {
        trigger: "#pricing",
        start: "top 70%"
      }
    });

    // ميزة خاصة للباقة الاحترافية (تظهر بشكل أقوى)
    gsap.from(".pricing-card-featured", {
      scale: 0.8,
      opacity: 0,
      duration: 1.5,
      delay: 0.5,
      ease: "elastic.out(1, 0.5)", // تأثير مطاطي فخم
      scrollTrigger: {
        trigger: "#pricing",
        start: "top 70%"
      }
    });

    // تحريك الأيقونات داخل الباقات عند التحويم (Hover)
    document.querySelectorAll('.pricing-card, .pricing-card-featured').forEach(card => {
      card.addEventListener('mouseenter', () => {
        gsap.to(card.querySelectorAll('svg'), {
          scale: 1.2,
          rotate: 10,
          duration: 0.3,
          stagger: 0.05
        });
      });
      card.addEventListener('mouseleave', () => {
        gsap.to(card.querySelectorAll('svg'), {
          scale: 1,
          rotate: 0,
          duration: 0.3
        });
      });
    });
    //end price
    //hrep
    // تسجيل الإضافة
    gsap.registerPlugin(ScrollTrigger);

    // 1. دخول المحتوى (Fade & Slide)
    gsap.from(".hero-content-reveal > *", {
      x: 50,
      opacity: 0,
      duration: 1,
      stagger: 0.15,
      ease: "power4.out"
    });

    // 2. حركة البطاقة الرئيسية (3D Tilt)
    gsap.from(".main-card-anim", {
      scale: 0.8,
      rotateY: 20,
      opacity: 0,
      duration: 1.5,
      delay: 0.5,
      ease: "expo.out"
    });

    // 3. تأثير الطفو المستمر (Floating Effect)
    // كل عنصر يتحرك بسرعة واتجاه مختلف ليعطي عمق حقيقي
    gsap.to(".float-anim-1", {
      y: -20,
      duration: 3,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut"
    });

    gsap.to(".float-anim-2", {
      y: 15,
      x: 10,
      duration: 4,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      delay: 0.5
    });

    gsap.to(".float-anim-3", {
      y: -10,
      x: -15,
      duration: 3.5,
      repeat: -1,
      yoyo: true,
      ease: "sine.inOut",
      delay: 1
    });

    // 4. تفاعل الماوس مع الـ Hero (Parallax)
    document.addEventListener("mousemove", (e) => {
      const {
        clientX,
        clientY
      } = e;
      const xPos = (clientX / window.innerWidth - 0.5) * 30;
      const yPos = (clientY / window.innerHeight - 0.5) * 30;

      gsap.to(".hero-visual-container", {
        x: xPos,
        y: yPos,
        duration: 1,
        ease: "power2.out"
      });
    });
    //end hero
    //navbar
    // --- SMART HEADER LOGIC ---
    const header = document.querySelector('#main-header');
    const navInner = document.querySelector('.header-nav');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        // الحالة عند التمرير: خلفية سوداء فخمة وتصغير الحجم
        header.classList.add('py-2');
        header.classList.remove('py-4');
        navInner.classList.replace('bg-gray-950/20', 'bg-gray-950/90');
        navInner.classList.add('shadow-primary/5', 'border-white/10');
        // إضافة أنيميشن دخول بسيط باستخدام GSAP لضمان النعومة
        gsap.to(navInner, {
          scale: 0.98,
          duration: 0.3
        });
      } else {
        // الحالة عند العودة للأعلى: شفاف وواسع
        header.classList.add('py-4');
        header.classList.remove('py-2');
        navInner.classList.replace('bg-gray-950/90', 'bg-gray-950/20');
        navInner.classList.remove('shadow-primary/5', 'border-white/10');
        gsap.to(navInner, {
          scale: 1,
          duration: 0.3
        });
      }
    });

    // أنيميشن الظهور الأول للهيدر عند فتح الصفحة
    gsap.from(header, {
      y: -100,
      opacity: 0,
      duration: 1.2,
      ease: "expo.out",
      delay: 0.2
    });

    //end navbar
  </script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-JQKHBTX28C"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-JQKHBTX28C');
  </script>
  <!-- price -->




</body>

</html>