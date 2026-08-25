<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ isRtl() ? 'rtl' : 'ltr' }}">

<head>
    <link rel="manifest" href="{{ url('manifest.json') }}?url={{ urlencode(ltrim(Request::getRequestUri(), '/')) }}&hash={{ $restaurant->hash }}" crossorigin="use-credentials">
    <meta name="theme-color" content="#ffffff">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P553LFRT');</script>
<!-- End Google Tag Manager -->
    @php
    function getFaviconPath($size, $restaurant) {
        $fileName = match($size) {
            '180' => 'apple-touch-icon.png',
            '192' => 'android-chrome-192x192.png',
            '512' => 'android-chrome-512x512.png',
            '16' => 'favicon-16x16.png',
            '32' => 'favicon-32x32.png',
            'ico' => 'favicon.ico'
        };

        $restaurantPath = "user-uploads/favicons/restaurant/{$restaurant->hash}/{$fileName}";
        $defaultPath = "user-uploads/favicons/super-admin/{$fileName}";

        return File::exists(public_path($restaurantPath))
            ? asset($restaurantPath)
            : asset($defaultPath);
    }
    @endphp

    <link rel="apple-touch-icon" sizes="180x180" href="{{ getFaviconPath('180', $restaurant) }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ getFaviconPath('192', $restaurant) }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ getFaviconPath('512', $restaurant) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ getFaviconPath('16', $restaurant) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ getFaviconPath('32', $restaurant) }}">
    <link rel="shortcut icon" href="{{ getFaviconPath('ico', $restaurant) }}">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $restaurant->logoUrl }}">


    <meta name="keyword" content="{{ $restaurant->meta_keyword ?? '' }}">
    <meta name="description" content="{{ $restaurant->meta_description ?? $restaurant->name }}">
    <title>{{ $restaurant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <style>
        :root {
            /* --color-base: 219, 41, 41; */
            --color-base: {{ $restaurant->theme_rgb }};
            --livewire-progress-bar-color: {{ $restaurant->theme_hex }};
        }
    </style>

    @if (File::exists(public_path() . '/css/app-custom.css'))
        <link href="{{ asset('css/app-custom.css') }}" rel="stylesheet">
    @endif

    {{-- Include file for widgets if exist --}}
    @includeIf('sections.custom_script_customer')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
  
        .swiper {
      width: 100%;
      height: 100%;
    position: relative !important;
    z-index: 0 !important;
        isolation: isolate;

    

    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #444;
      display: flex;
      justify-content: center;
      align-items: center;
        position: relative !important;
    z-index: 0 !important;
    }

    .swiper-slide img ,video{
      display: block;
      width: 100%;
      height: 40vh;
      object-fit: cover;
    }
    
*{
  font-family: "Noto Sans Arabic", sans-serif !important;
  font-optical-sizing: auto;
  font-style: normal;
  font-variation-settings:
    "wdth" 100;
}
</style>
</head>

<body class="font-sans antialiased dark:bg-gray-900">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P553LFRT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@livewire('coupon.coupon-popup')

    <div class="mx-auto max-w-lg lg:max-w-screen-xl min-h-svh shadow-md lg:shadow-none">
        @livewire('shopNavigation', ['restaurant' => $restaurant, 'shopBranch' => $shopBranch])
        @livewire('shopDesktopNavigation', ['restaurant' => $restaurant, 'shopBranch' => $shopBranch])
     <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                     @if($restaurant->id == 5)
    <div class="swiper-slide">
                           <img src="{{ asset('images/hereo44.png') }}" alt="Photo2" > 
                    </div>
                    <div class="swiper-slide"> 
                          
<video id="video" class="video" preload="metadata" playsinline="" autoplay="" muted="" loop="" poster="{{ asset('images/cake.jpg') }}">
<source src="{{ asset('images/cacke2.mp4') }}" type="video/mp4"></video>


                    </div>
                    @endif
                    @if($restaurant->id != 5)
                    <video id="video" class="video" preload="metadata" playsinline="" autoplay="" muted="" loop="" poster="{{ asset('images/hero2.jpg') }}">
<source src="{{ asset('images/pizza.mp4') }}" type="video/mp4"></video>
                    <div class="swiper-slide">
                        <img src="{{ asset('images/hero1.png') }}" alt="Photo" >
                    </div>
                    <div class="swiper-slide">
                           <img src="{{ asset('images/hero2.jpg') }}" alt="Photo2" > 
                    </div>
                    @endif
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            
        <div class="flex mt-4 overflow-hidden  dark:bg-gray-900">
              <!-- Swiper -->
           
            <div id="main-content" class="w-full h-full overflow-y-auto dark:bg-gray-900">
                <main>
                    @yield('content')

                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>

    </div>
    @stack('modals')

    <footer class="aani-footer" dir="{{ isRtl() ? 'rtl' : 'ltr' }}">
        <style>
            .aani-footer {
                position: relative;
                background: #ffffff;
                border-top: 1px solid #eeeeee;
                color: #171717;
                overflow: hidden;
            }

            .aani-footer * {
                box-sizing: border-box;
            }

            .aani-footer__inner {
                width: min(1180px, calc(100% - 48px));
                margin: 0 auto;
                padding: 56px 0 28px;
            }

            .aani-footer__grid {
                display: grid;
                grid-template-columns: 1.45fr 1fr 1fr 1fr;
                gap: 52px;
                align-items: start;
            }

            .aani-footer__brand {
                text-align: start;
            }

            .aani-footer__logo {
                display: inline-flex;
                align-items: center;
                justify-content: flex-start;
                min-height: 58px;
                margin-bottom: 18px;
            }

            .aani-footer__logo img {
                display: block;
                width: auto;
                max-width: 190px;
                max-height: 62px;
                object-fit: contain;
            }

            .aani-footer__brand-name {
                margin: 0 0 12px;
                color: #4d2196;
                font-size: 27px;
                font-weight: 700;
                letter-spacing: -.4px;
            }

            .aani-footer__description {
                margin: 0;
                max-width: 390px;
                color: #333333;
                font-size: 14px;
                line-height: 2;
                font-weight: 400;
            }

            .aani-footer__title {
                margin: 2px 0 19px;
                color: #171717;
                font-size: 17px;
                font-weight: 700;
                line-height: 1.5;
            }

            .aani-footer__links {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .aani-footer__links a {
                width: fit-content;
                color: #242424;
                font-size: 14px;
                line-height: 1.7;
                text-decoration: none;
                transition: color .2s ease, transform .2s ease;
            }

            .aani-footer__links a:hover {
                color: #5d2aa8;
                transform: translateX(-2px);
            }

            [dir="ltr"] .aani-footer__links a:hover {
                transform: translateX(2px);
            }

            .aani-footer__contact {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .aani-footer__contact-item {
                display: flex;
                align-items: center;
                gap: 11px;
                color: #242424;
                font-size: 14px;
                line-height: 1.6;
            }

            .aani-footer__contact-icon {
                width: 38px;
                height: 38px;
                flex: 0 0 38px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #e4e0ea;
                border-radius: 50%;
                color: #4d2196;
                background: #ffffff;
            }

            .aani-footer__contact-icon svg {
                width: 17px;
                height: 17px;
            }

            .aani-footer__contact a {
                color: inherit;
                text-decoration: none;
            }

            .aani-footer__contact a:hover {
                color: #5d2aa8;
            }

            .aani-footer__language {
                margin-bottom: 25px;
            }

            .aani-footer__language-label {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 11px;
                color: #171717;
                font-size: 17px;
                font-weight: 700;
            }

            .aani-footer__language-label svg {
                width: 19px;
                height: 19px;
                color: #4d2196;
            }

            .aani-footer__language-control {
                display: inline-flex;
                align-items: center;
                min-height: 45px;
                padding: 5px 12px;
                border: 1px solid #e5e1ea;
                border-radius: 12px;
                background: #fff;
                box-shadow: 0 4px 14px rgba(31, 18, 54, .05);
            }

            .aani-footer__language-control > * {
                margin: 0 !important;
            }

            .aani-footer__language-control select,
            .aani-footer__language-control button {
                border: 0 !important;
                outline: 0 !important;
                background: transparent !important;
                color: #252525 !important;
                font-family: "Noto Sans Arabic", sans-serif !important;
                font-size: 13px !important;
                font-weight: 600 !important;
                cursor: pointer;
            }

            .aani-footer__language-control button {
                padding: 5px 2px !important;
            }

            .aani-footer__socials {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 9px;
                margin-top: 22px;
            }

            .aani-footer__social {
                width: 38px;
                height: 38px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #e5e1ea;
                border-radius: 50%;
                color: #202020;
                background: #fff;
                text-decoration: none;
                transition: all .2s ease;
            }

            .aani-footer__social:hover {
                color: #ffffff;
                background: #4d2196;
                border-color: #4d2196;
                transform: translateY(-2px);
            }

            .aani-footer__social svg {
                width: 17px;
                height: 17px;
                fill: currentColor;
            }

            .aani-footer__bottom {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                margin-top: 45px;
                padding-top: 21px;
                border-top: 1px solid #eeeeee;
            }

            .aani-footer__copyright {
                margin: 0;
                color: #777777;
                font-size: 12px;
                line-height: 1.8;
            }

            .aani-footer__copyright strong {
                color: #4d2196;
                font-weight: 700;
            }

            .aani-footer__payments {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                flex-wrap: wrap;
                gap: 7px;
            }

            .aani-footer__payment {
                min-width: 48px;
                height: 27px;
                padding: 0 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #e8e8e8;
                border-radius: 5px;
                background: #ffffff;
                color: #555555;
                font-family: Arial, sans-serif !important;
                font-size: 9px;
                font-weight: 800;
                letter-spacing: -.2px;
            }

            @media (max-width: 900px) {
                .aani-footer__grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 38px 32px;
                }
            }

            @media (max-width: 640px) {
                .aani-footer__inner {
                    width: min(100% - 32px, 520px);
                    padding: 38px 0 22px;
                }

                .aani-footer__grid {
                    grid-template-columns: 1fr;
                    gap: 30px;
                }

                .aani-footer__brand {
                    text-align: center;
                }

                .aani-footer__logo {
                    justify-content: center;
                }

                .aani-footer__description {
                    margin-inline: auto;
                }

                .aani-footer__title {
                    margin-bottom: 13px;
                }

                .aani-footer__bottom {
                    flex-direction: column;
                    text-align: center;
                    margin-top: 32px;
                }

                .aani-footer__payments {
                    justify-content: center;
                }
            }
        </style>

        @php
            $aaniIsArabic = app()->getLocale() === 'ar';
        @endphp

        <div class="aani-footer__inner">
            <div class="aani-footer__grid">

                {{-- Brand --}}
                <div class="aani-footer__brand">
                    <div class="aani-footer__logo">
                        @if ($restaurant->logoUrl)
                            <img src="{{ $restaurant->logoUrl }}" alt="{{ $restaurant->name }}">
                        @else
                            <div class="aani-footer__brand-name">{{ $restaurant->name }}</div>
                        @endif
                    </div>

                    @if ($restaurant->meta_description)
                        <p class="aani-footer__description">
                            {{ $restaurant->meta_description }}
                        </p>
                    @endif

                    <div class="aani-footer__socials">
                        @if ($restaurant->facebook_link)
                            <a class="aani-footer__social" href="{{ $restaurant->facebook_link }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($restaurant->instagram_link)
                            <a class="aani-footer__social" href="{{ $restaurant->instagram_link }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-1.857-.465a4.902 4.902 0 01-1.153-1.772 4.902 4.902 0 01-.465-2.427c-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.137 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.218-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($restaurant->twitter_link)
                            <a class="aani-footer__social" href="{{ $restaurant->twitter_link }}" target="_blank" rel="noopener noreferrer" aria-label="X">
                                <svg viewBox="0 0 50 50" aria-hidden="true">
                                    <path d="M 6.9199219 6 L 21.136719 26.726562 L 6.2285156 44 L 9.40625 44 L 22.544922 28.777344 L 32.986328 44 L 43 44 L 28.123047 22.3125 L 42.203125 6 L 39.027344 6 L 26.716797 20.261719 L 16.933594 6 L 6.9199219 6 z"/>
                                </svg>
                            </a>
                        @endif

                        @if ($restaurant->yelp_link)
                            <a class="aani-footer__social" href="{{ $restaurant->yelp_link }}" target="_blank" rel="noopener noreferrer" aria-label="Yelp">
                                <svg viewBox="0 0 30 30" aria-hidden="true">
                                    <path d="M13.961 22.279c.246-.273.601-.444.995-.444.739 0 1.338.599 1.338 1.338 0 .016 0 .032-.001.048l-.237 6.483c-.027.719-.616 1.293-1.34 1.293-.077 0-.153-.006-.226-.019-1.763-.303-3.331-.962-4.69-1.902-.351-.245-.578-.647-.578-1.102 0-.346.131-.661.346-.898l4.345-4.829zM12.853 20.434l-6.301 1.572c-.097.025-.208.039-.322.039-.687 0-1.253-.517-1.332-1.183-.046-.389-.073-.839-.073-1.295 0-1.324.223-2.597.635-3.781.183-.534.681-.911 1.267-.911.214 0 .417.05.596.14l5.833 2.848c.45.221.754.677.754 1.203 0 .623-.427 1.147-1.004 1.294l-.009.002z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Important links --}}
                <div>
                    <h3 class="aani-footer__title">{{ $aaniIsArabic ? 'روابط مهمة' : 'Important Links' }}</h3>
                    <nav class="aani-footer__links" aria-label="{{ $aaniIsArabic ? 'روابط مهمة' : 'Important Links' }}">
                        <a href="{{ route('about', [$restaurant->hash]) }}">{{ $aaniIsArabic ? 'من نحن' : 'About Us' }}</a>

                        <a href="{{ route('terms', [$restaurant->hash]) }}">{{ $aaniIsArabic ? 'سياسة الاستخدام والخصوصية' : 'Terms & Privacy Policy' }}</a>
                        <a href="{{ route('returnscondition', [$restaurant->hash]) }}">{{ $aaniIsArabic ? 'الشروط والأحكام وسياسة الاستبدال والاسترجاع' : 'Return & Exchange Policy' }}</a>
                    </nav>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="aani-footer__title">{{ $aaniIsArabic ? 'تواصل معنا' : 'Contact Us' }}</h3>
                    <div class="aani-footer__contact">
                        @if ($restaurant->phone)
                            <div class="aani-footer__contact-item">
                                <span class="aani-footer__contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                </span>
                                <a href="tel:{{ $restaurant->phone }}">{{ $restaurant->phone }}</a>
                            </div>
                        @endif

                        @if ($restaurant->email)
                            <div class="aani-footer__contact-item">
                                <span class="aani-footer__contact-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path d="m3 7 9 6 9-6"/>
                                    </svg>
                                </span>
                                <a href="mailto:{{ $restaurant->email }}">{{ $restaurant->email }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Language / App area --}}
                <div>
                    <div class="aani-footer__language">
                        <h3 class="aani-footer__language-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9S14.5 18.3 12 21c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/>
                            </svg>
                            {{ $aaniIsArabic ? 'اللغة' : 'Language' }}
                        </h3>

                        @if (languages()->count() > 1)
                            <div class="aani-footer__language-control">
                                @livewire('shop.languageSwitcher')
                            </div>
                        @endif
                    </div>

                    <!--<div>
                        <h3 class="aani-footer__title">{{ $aaniIsArabic ? 'حمّل التطبيق' : 'Download App' }}</h3>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            <span class="aani-footer__payment" style="height:38px;min-width:118px;font-size:10px;">Google Play</span>
                            <span class="aani-footer__payment" style="height:38px;min-width:118px;font-size:10px;">App Store</span>
                        </div>
                    </div>-->
                </div>
            </div>

            <div class="aani-footer__bottom">
                <p class="aani-footer__copyright">
                    &copy; {{ now()->year }}
                    <strong>{{ $restaurant->name }}</strong>.
                    @lang('app.allRightsReserved')
                </p>

                <div class="aani-footer__payments" aria-label="{{ $aaniIsArabic ? 'طرق الدفع' : 'Payment methods' }}">
                    <span class="aani-footer__payment">mada</span>
                    <span class="aani-footer__payment">VISA</span>
                    <span class="aani-footer__payment">Mastercard</span>
                    <span class="aani-footer__payment">STC pay</span>
                    <span class="aani-footer__payment">tabby</span>
                    <span class="aani-footer__payment"> Pay</span>
                </div>
            </div>
        </div>
    </footer>

   {{-- أيقونة واتساب --}}


<livewire:whatsapp-button.whatsapp-button :restaurantId="$restaurant->id" />    
    @livewireScripts


    @include('layouts.update-uri')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}" defer data-navigate-track></script>

    @if ($restaurant->paymentGateways->razorpay_status)
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif

    @if ($restaurant->paymentGateways->stripe_status)
        <script src="https://js.stripe.com/v3/"></script>

        <form action="{{ route('stripe.order_payment') }}" method="POST" id="order-payment-form" class="hidden">
            @csrf

            <input type="hidden" id="order_payment" name="order_payment">

            <div class="form-row">
                <label for="card-element">
                    Credit or debit card
                </label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display Element errors. -->
                <div id="card-errors" role="alert"></div>
            </div>

            <button>Submit Payment</button>
        </form>


        <script>
            const stripe = Stripe('{{ $restaurant->paymentGateways->stripe_key }}');
            const elements = stripe.elements({
                currency: '{{ strtolower($restaurant->currency->currency_code) }}',
            });
        </script>
    @endif
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('service-worker.js') }}')
                    .then(registration => {
                        console.log('Service Worker registered:', registration);
                    })
                    .catch(error => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
        self.addEventListener("fetch", (event) => {
            if (event.request.mode === "navigate") {
                event.respondWith(
                    fetch(event.request.url)
                );
            }
        });
    </script>

    <script>
        @if ($restaurant->is_pwa_install_alert_show == 1)
            var isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            var isInStandaloneMode = ('standalone' in window.navigator) && window.navigator.standalone;
            var deferredPrompt;

            window.addEventListener("beforeinstallprompt", (event) => {
                event.preventDefault(); // Prevent default install prompt
                deferredPrompt = event; // Store the event for later use

                // Prevent showing again if user has dismissed in this tab
                if (!sessionStorage.getItem("pwaDismissed")) {
                    ['scroll', 'click'].forEach(evt => {
                        window.addEventListener(evt, showInstallPrompt, { once: true });
                    });
                }
            });

            function showInstallPrompt() {
                if (deferredPrompt) {
                    deferredPrompt.prompt(); // Show the install prompt

                    deferredPrompt.userChoice.then(({ outcome }) => {
                        console.log(`User ${outcome === 'accepted' ? 'accepted' : 'dismissed'} the PWA install`);

                        if (outcome === 'dismissed') {
                            sessionStorage.setItem("pwaDismissed", "true"); // Prevent showing again in this session
                        }

                        deferredPrompt = null;
                    });
                }
            }

            // Handle iOS specific add to home screen prompt
            if (isIOS && !isInStandaloneMode) {
                // Check if prompt was shown in last 24 hours
                const lastPrompt = localStorage.getItem('iosPromptLastShown');
                const now = new Date().getTime();

                if (!lastPrompt || (now - parseInt(lastPrompt)) > 24 * 60 * 60 * 1000) {
                    ['scroll', 'click'].forEach(evt => {
                        window.addEventListener(evt, showIOSInstallInstructions, { once: true });
                    });
                }
            }

            function showIOSInstallInstructions() {
                if (document.getElementById('iosInstallInstructions')) return;

                // Store the current timestamp when showing the prompt
                localStorage.setItem('iosPromptLastShown', new Date().getTime());

                const instructions = document.createElement('div');
                instructions.id = 'iosInstallInstructions';
                instructions.innerHTML = `
                    <div style="position: fixed; bottom: 10px; left: 10px; right: 10px; background: #fff; padding:
                 <div style="position: fixed; bottom: 10px; left: 10px; right: 10px; background: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-align: center; z-index: 1000;">
                    <p class="flex relative">@lang('messages.installAppInstruction')
                        <img class="absolute right-0 left-auto mr-5" src="{{ asset('img/share-ios.svg') }}" alt="Share Icon" style="width: 20px; vertical-align: middle;">

                    </p>
                    @lang('messages.addToHomeScreen').
                    <button id="closeInstructions" class="block text-center mx-auto" style="margin-top: 10px; padding: 5px 10px;">@lang('app.close')</button>
                </div>
            `;
            document.body.appendChild(instructions);

            // Add click event to close button
            document.getElementById('closeInstructions').addEventListener('click', function () {
                document.getElementById('iosInstallInstructions').remove();
            });
        }

    @endif

</script>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      loop: true, // يكرر السلايدر
  autoplay: {
    delay: 3000, // الوقت بين كل سلايد (بالملي ثانية) - هنا 3 ثواني
    disableOnInteraction: false, // يستمر بعد ما المستخدم يحرك السلايدر
  },
      pagination: {
        el: ".swiper-pagination",
        type: "progressbar",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

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


    <x-livewire-alert::flash />
    @include('sections.pusher-script')

    @stack('scripts')
    <script>
    document.addEventListener('livewire:init', () => {

    Livewire.on('gtm-add-to-cart', (event) => {

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
            event: 'add_to_cart',
            ecommerce: {
                currency: 'SAR',
                value: event.price,
                items: [{
                    item_id: event.item_id,
                    item_name: event.item_name,
                    price: event.price,
                    quantity: event.quantity
                }]
            }
        });

        console.log('add_to_cart fired');
    });
    Livewire.on('gtm-view-cart', (event) => {

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
            event: 'view_cart',
            ecommerce: event
        });

        console.log('view_cart fired');
    });
    Livewire.on('gtm-customer-identified', () => {

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
            event: 'customer_identified'
        });

        console.log('customer_identified fired');

    });
    Livewire.on('gtm-begin-checkout', (event) => {

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
            event: 'begin_checkout',
            ecommerce: event
        });

        console.log('begin_checkout fired');

    });
    Livewire.on('gtm-add-shipping-info', (event) => {

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push({
            event: 'add_shipping_info',
            ecommerce: event
        });

        console.log('add_shipping_info fired');

    });

});
</script>
</body>

</html>
