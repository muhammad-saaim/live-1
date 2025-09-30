<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome - Matchology</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        /* Page-scoped: keep everything within the viewport width */
        html,
        body,
        .page-wrapper {
            overflow-x: hidden;
        }
        /* Smooth in-page anchor scrolling */
        html { scroll-behavior: smooth; }
        /* Ensure anchors aren't hidden under the header */
        section[id] { scroll-margin-top: 90px; }

        /* In case decorative absolute elements extend beyond container */
        .section---hero,
        .header-wrapper-edited.nav {
            overflow: hidden;
        }

        /* Center content inside fluid containers */
        .header-wrapper-edited .container-default-edited,
        .section---hero .container---benefits-features-1,
        .section---product .container-default,
        .section---team .container-default {
            max-width: 1320px;
            /* Bootstrap ~xl */
            margin-left: auto;
            margin-right: auto;
            padding-left: 12px;
            padding-right: 12px;
            width: 100%;
        }

        /* Header sizing + responsiveness */
        .header-content-wrapper-edited {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .hamburger-menu-wrapper-edited { display: none; }
        .header-nav-menu-wrapper-edited.nav-menu { display: block; }
        .header-wrapper-edited.nav {
            position: relative;
            z-index: 1200;
            overflow: visible;
            min-height: 56px;
            padding: 8px 12px;
            box-sizing: border-box;
        }
        /* Make sure the image logo is not hidden by a 'hide' utility */
        .header-logo-edited.hide { display: inline-block !important; }
        /* Prefer static image logo; hide video logo (optional) */
        .header-left-side-edited .video-logo { display: none !important; }
        /* Logo/video size inside header */
        .header-left-side-edited .video-logo { height: 40px; width: auto; }
        .header-logo-edited { height: 40px; width: auto; }

        @media (min-width: 768px) {
            .header-wrapper-edited.nav { min-height: 64px; padding: 10px 14px; }
            .header-left-side-edited .video-logo, .header-logo-edited { height: 44px; }
        }
        @media (min-width: 1024px) {
            .header-wrapper-edited.nav { min-height: 72px; padding: 12px 16px; }
            .header-left-side-edited .video-logo, .header-logo-edited { height: 48px; }
        }
        /* Slightly tighter link padding so more items fit */
        .header-nav-menu-list-edited .header-nav-link-edited { padding: 8px 10px; display: inline-block; }

        /* Mobile/tablet up to 1023px */
        @media (max-width: 1023px) {
            .header-nav-menu-wrapper-edited.nav-menu {
                display: none !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                background: #ffffff;
                padding: 12px 16px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.08);
                z-index: 1000;
                pointer-events: auto;
                overflow-x: hidden;
            }
            .header-nav-menu-wrapper-edited.nav-menu.is-open { display: block !important; }
            /* Fallback: also open when root has nav-open */
            .nav-open .header-nav-menu-wrapper-edited.nav-menu { display: block !important; }
            .hamburger-menu-wrapper-edited {
                display: block;
                cursor: pointer;
                position: relative;
                z-index: 1100; /* above dropdown */
                width: 44px; height: 44px; /* larger hit area */
                -webkit-tap-highlight-color: transparent;
            }
            .hamburger-menu-icon-edited { pointer-events: none; }
            .header-right-side-edited { display: flex; align-items: center; gap: 12px; }
            /* Hide right-side duplicates on mobile (use dropdown versions instead) */
            .header-right-side-edited .header-nav-link-edited.login,
            .header-right-side-edited .btn-primary-edited.small.header-btn-hidde-on-mb-edited.button { display: none !important; }
            /* Stack nav items vertically on mobile/tablet and make links obvious */
            .header-nav-menu-list-edited { display: flex; flex-direction: column; gap: 10px; margin: 0; padding: 8px 0; }
            .header-nav-menu-list-edited li { list-style: none; }
            .header-nav-menu-list-edited a {
                display: block;
                padding: 14px 12px; /* larger tap target */
                font-size: 18px;    /* larger text */
                color: #111;
                text-decoration: none;
            }
            .header-nav-menu-list-edited a:hover { background: #f3f4f6; border-radius: 6px; }

            /* Slightly larger hamburger bars for 1024-1279 widths */
            .hamburger-menu-bar-edited { height: 3px; width: 28px; }
            .hamburger-menu-bar-edited.top { margin-bottom: 6px; }

            /* Mobile login link/icon alignment & sizing */
            .div-block---mobile-menu { border-top: 1px solid #eee; margin-top: 8px; padding-top: 8px; }
            .header-nav-link-edited.login { display: flex; align-items: center; gap: 8px; }
            .header-nav-link-login-icon-edited { font-size: 20px; line-height: 1; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; }
            .text-block-edited.mobile { display: inline-block; font-size: 16px; }
            /* Ensure the mobile login button is full-width friendly */
            .header-btn-hidde-on-mb-edited.mobile.button { margin-top: 8px; width: 100%; text-align: center; }
        }

        /* Desktop 1024px and above: always show full menu */
        @media (min-width: 1024px) {
            .header-nav-menu-wrapper-edited.nav-menu { display: block !important; position: static; box-shadow: none; padding: 0; }
            .hamburger-menu-wrapper-edited { display: none !important; }
            /* Hide the mobile-only login block on desktop */
            .div-block---mobile-menu { display: none !important; }
        }

        /* Tablet/Small desktop: 1024px–1279px tighten header text and spacing */
        @media (min-width: 1024px) and (max-width: 1279px) {
            .header-content-wrapper-edited { gap: 10px; }
            .header-nav-menu-list-edited { column-gap: 8px; row-gap: 0; }
            .header-nav-menu-list-edited .header-nav-link-edited {
                font-size: 14px;
                padding: 6px 8px;
            }
            .header-right-side-edited .btn-primary-edited.small { font-size: 14px; padding: 8px 10px; }
            .text-block-edited { font-size: 14px; }
        }

        /* Ensure the register/login form visually centers on large screens */
        .register---login-form {
            margin-left: auto;
            margin-right: auto;
        }

        /* Icons inside inputs (page-scoped) */
        .with-icon-left {
            position: relative;
            display: block;
        }

        .with-icon-left>input {
            padding-left: 55px;
        }

        .input-icon-left {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neutral--600);
            pointer-events: none;
            font-size: 1rem;
            line-height: 1;
        }

        /* Contact/Login form input styling */
        .request-demo-form-block-edited input[type="text"],
        .request-demo-form-block-edited input[type="email"],
        .request-demo-form-block-edited input[type="password"],
        .contact-form-grid input[type="text"],
        .contact-form-grid input[type="email"],
        .contact-form-grid input[type="password"],
        .input-edited {
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            background-color: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        /* Hover: blue border */
        .with-icon-left:hover .input-edited,
        .request-demo-form-block-edited input[type="text"]:hover,
        .request-demo-form-block-edited input[type="email"]:hover,
        .request-demo-form-block-edited input[type="password"]:hover,
        .contact-form-grid input[type="text"]:hover,
        .contact-form-grid input[type="email"]:hover,
        .contact-form-grid input[type="password"]:hover {
            border-color: #3B82F6;
            /* blue-500 */
        }

        .request-demo-form-block-edited input[type="text"]:focus,
        .request-demo-form-block-edited input[type="email"]:focus,
        .request-demo-form-block-edited input[type="password"]:focus,
        .contact-form-grid input[type="text"]:focus,
        .contact-form-grid input[type="email"]:focus,
        .contact-form-grid input[type="password"]:focus,
        .input-edited:focus {
            outline: none;
            border-color: #10B981;
            /* emerald-500 */
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        /* Icon tinting on hover/focus (images approximated via CSS filters) */
        .with-icon-left:hover .input-icon-left {
            filter: hue-rotate(210deg) saturate(2);
        }

        /* Strong green tint on active (focus) */
        .with-icon-left:focus-within .input-icon-left {
            filter: invert(39%) sepia(79%) saturate(512%) hue-rotate(110deg) brightness(95%) contrast(90%);
        }

        /* Force-show any elements hidden by Webflow inline styles without relying on JS */
        [testing],
        .w-form,
        .request-demo-form-block-edited,
        .div---regsiter-form,
        .header-wrapper-edited,
        .section---hero,
        .section---product,
        .section---team,
        .section---story {
            opacity: 1 !important;
            transform: none !important;
        }

        /* Last-resort neutralizers for any inline styles Webflow added */
        [style*="opacity:0"],
        [style*="opacity: 0"] {
            opacity: 1 !important;
        }

        [style*="translate3d"],
        [style*="scale3d"] {
            transform: none !important;
        }

        [style*="visibility:hidden"],
        [style*="visibility: hidden"] {
            visibility: visible !important;
        }

        /* Hero image sizing: make image visibly larger on this page only */
        .section---hero .image-wrapper.border-radius-24px {
            width: 100%;
            height: clamp(420px, 60vh, 780px);
        }
        /* Expand the hero image column to full width (override template caps) */
        .section---hero .inner-container-edited._583px,
        .section---hero .inner-container-edited._598px,
        .section---hero .inner-container-edited._614px {
            max-width: 100% !important;
            width: 100% !important;
            justify-self: stretch !important;
        }
        .section---hero .inner-container.mg-left-auto {
            max-width: 100% !important;
            width: 100% !important;
        }
        /* Ensure the grid lets the image column grow */
        .section---hero .grid---ben-fea-application {
            align-items: stretch;
        }
        /* Make the image column wider than the text column on large screens */
        @media (min-width: 1024px) {
            .section---hero .grid---ben-fea-application.text-left-default.v2 {
                grid-template-columns: 0.7fr 1.3fr !important;
            }
        }
        /* Stronger overrides for the specific hero image wrapper */
        .section---hero .hero-img-wrapper {
            width: 100% !important;
            height: clamp(520px, 70vh, 600px) !important;
            max-height: none !important;
            /* margin-top: 24px; default top spacing */
        }
        @media (min-width: 768px) {
            /* .section---hero .hero-img-wrapper { margin-top: 40px; } */
        }
        @media (min-width: 1280px) {
            /* .section---hero .hero-img-wrapper { margin-top: 60px; } */
        }
        .section---hero .hero-img-wrapper > img.image.cover {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block;
        }
        /* Ensure the image fills the wrapper height */
        .section---hero .image-wrapper.border-radius-24px .image.cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ========== Testing section (image2) sizing ========== */
        /* Favor the image column width */
        @media (min-width: 1024px) {
            .section---product .grid---ben-fea-application.text-right-default.v2 {
                grid-template-columns: 1.2fr 0.8fr !important;
            }
        }
        /* Larger, responsive height for the image wrapper */
        .section---product .product-img-wrapper {
            width: 100% !important;
            height: clamp(380px, 55vh, 720px) !important;
            max-height: none !important;
        }
        .section---product .product-img-wrapper > img.image.cover {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block;
        }
        /* Enforce wider width for login email/password inputs on large screens */
        @media (min-width: 1024px) {
            #login-form input#email.input-edited,
            #login-form input#password.input-edited {
                width: 400px !important;
                max-width: none !important;
                box-sizing: border-box;
                display: block;
                flex: 0 0 400px !important;
            }
        }
        @media (min-width: 1280px) {
            #login-form input#email.input-edited,
            #login-form input#password.input-edited {
                width: 400px !important;
                max-width: none !important;
                flex: 0 0 400px !important;
            }
        }
    </style>
</head>

<body>

    <div class="page-wrapper container-fluid">
        <div testing="71dcfd35-b766-230b-e445-5910837f3122" data-animation="default" data-collapse="medium"
            data-duration="400" data-easing="ease" data-easing2="ease" role="banner" class="header-wrapper-edited nav">
            <div class="container-default-edited container-fluid">
                <div class="header-content-wrapper-edited">
                    <div class="header-left-side-edited"><a href="/" aria-current="page"
                            class="header-logo-link-edited right-mg nav-brand current">    <img src="{{ asset('assets/media/logos/logo1.png') }}" class="h-12 max-w-full" style="max-width: 250px;" alt="Matchology Logo" />

                        </a>
                        <nav role="navigation"
                            id="primaryNavMenu"
                            class="header-nav-menu-wrapper-edited transparent-bg home-margin nav-menu">
                            <ul role="list" class="header-nav-menu-list-edited height-edited homepage-height">
                                <li class="header-nav-list-item-edited left"><a href="#Testing"
                                        class="header-nav-link-edited nav-link">Testing</a></li>
                                <li class="header-nav-list-item-edited left"><a href="#Analysis"
                                        class="header-nav-link-edited nav-link">Analysis</a></li>
                                <li class="header-nav-list-item-edited left"><a href="#Training"
                                        class="header-nav-link-edited nav-link">Training</a></li>
                                <li class="header-nav-list-item-edited left"><a href="#Matching"
                                        class="header-nav-link-edited nav-link">Matching</a></li>
                                <li class="header-nav-list-item-edited left"><a href="#Who-for"
                                        class="header-nav-link-edited nav-link">Solutions</a></li>
                            </ul>
                            <div class="div-block---mobile-menu"><a href="/maintenance" target="_blank"
                                    class="header-nav-link-edited login align-center inline-block">
                                    <div
                                        class="line-rounded-icon-edited header-nav-link-login-icon-edited spcing-fixed">
                                        </div>
                                    <div class="text-block-edited mobile">Login</div>
                                </a>
                                @auth
                                <a href="{{ route('dashboard') }}"
                                    class="btn-primary-edited small header-btn-hidde-on-mb-edited mobile button">Go to Dashboard</a>
                                @else
                                <a href="{{ route('register') }}"
                                    class="btn-primary-edited small header-btn-hidde-on-mb-edited mobile button">Get
                                    started</a>
                                @endauth
                            </div>
                        </nav>
                    </div>
                    <div class="header-right-side-edited"><a href="/maintenance" target="_blank"
                            class="header-nav-link-edited login inline-block">
                            <div class="line-rounded-icon-edited header-nav-link-login-icon-edited"></div>
                            <div class="text-block-edited">Login</div>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="btn-primary-edited small header-btn-hidde-on-mb-edited button">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}"
                               class="btn-primary-edited small header-btn-hidde-on-mb-edited button">Get Started</a>
                        @endauth
                        <div class="hamburger-menu-wrapper-edited nav-button" role="button" tabindex="0" aria-controls="primaryNavMenu" aria-expanded="false">
                            <div class="hamburger-menu-icon-edited">
                                <div class="hamburger-menu-bar-edited top"></div>
                                <div class="hamburger-menu-bar-edited bottom"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="Hero" class="section---hero">
            <div class="container---benefits-features-1 container-fluid">
                <div class="inner-container-edited _600px---tablet center">
                    <div class="inner-container-edited _500px---mbl center">
                        <div class="layout-grid grid---ben-fea-application text-left-default v2 bottom-105px">
                            <div testing="82ff1b0b-0e36-dc3c-5a11-d6200489a758"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _598px _100---tablet">
                                <div class="text-center-tablet">
                                    <div class="inner-container-edited _500px---tablet center">
                                        <div class="inner-container-edited _400px---mbl center">
                                            <h2 class="heading-2---section">Register for a Journey Towards Lasting
                                                Happiness</h2>
                                        </div>
                                    </div>
                                    <p class="paragraph---section tablet-center hide">Academic Window provides many
                                        detailed reports for students, teachers, counselors, and school admins. Move
                                        from interpreting data to taking action and improving student outcomes.</p>
                                </div>
                                <div testing="05d23fd6-6806-61c4-5413-9547e915eb42"
                                    style="opacity:0;-webkit-transform:translate3d(0, 0, 0) scale3d(0.92, 0.92, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(0.92, 0.92, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(0.92, 0.92, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(0.92, 0.92, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
                                    class="div---regsiter-form">
                                    <div testing="05d23fd6-6806-61c4-5413-9547e915eb43"
                                        style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                        class="request-demo-form-block-edited edited form">
                                        <form id="login-form" name="login-form" action="{{ route('login') }}"
                                            method="POST" class="contact-form-grid">
                                            @csrf

                                            <div class="register---login-form">
                                                <!-- Email / Phone Field -->
                                                <div class="div-block---demo-form-field position-relative">
                                                    <label for="email" class="field-label-edited">Email or Phone</label>
                                                    <div class="with-icon-left">
                                                        <img src="{{ asset('images/message.svg') }}" alt="Email"
                                                            class="input-icon-left" width="20" height="20" />
                                                        <input class="input-edited icon-left email input lg:w-[400px]"
                                                            maxlength="256" name="email" data-name="Email/Phone number"
                                                            placeholder="Your email/phone number" type="text" id="email"
                                                            value="{{ old('email') }}" required />
                                                    </div>
                                                    @error('email')
                                                        <div class="error text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Password Field -->
                                                <div class="div-block---demo-form-field position-relative">
                                                    <label for="password" class="field-label-edited">Password</label>
                                                    <div class="with-icon-left">
                                                        <img src="{{ asset('images/password.svg') }}" alt="Password"
                                                            class="input-icon-left" width="20" height="20" />
                                                        <input class="input-edited icon-left password input lg:w-[400px]"
                                                            maxlength="256" name="password" data-name="Password"
                                                            placeholder="Your password" type="password" id="password"
                                                            required />
                                                    </div>
                                                    @error('password')
                                                        <div class="error text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <!-- Forgot password link -->
                                                    <a href="{{ route('password.request') }}"
                                                        class="fogot-password-link">Forgot password?</a>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <input type="submit" data-wait="Please wait..."
                                                class="btn-primary-edited sign-in-btn button" value="Sign In" />

                                            <!-- Divider -->
                                            <div class="register-divider">
                                                <div class="divider dark"></div>
                                                <div class="general-text register">OR</div>
                                                <div class="divider dark"></div>
                                            </div>
                                        </form>

                                        <div class="success-message form-done">
                                            <div class="hide">
                                                <div class="line-rounded-icon success-message-check large color-edited">
                                                    </div>
                                                <div class="bold-text---dark">Your message has been submitted. <br />We
                                                    will get back to you within 24-48 hours.</div>
                                            </div>
                                        </div>
                                        <div class="error-message form-fail">
                                            <div class="bold-text---red">Oops! Something went wrong.</div>
                                        </div>
                                    </div>
                                    @auth
                                        <a href="{{ route('dashboard') }}"
                                           class="btn-primary-edited register-btn button">Go to Dashboard</a>
                                    @else
                                        <a href="{{ route('register') }}"
                                           class="btn-primary-edited register-btn button">Join
                                           Matchology Now</a>
                                    @endauth
                                </div>
                            </div>
                            <div id="node82ff1b0b-0e36-dc3c-5a11-d6200489a779-dcc7cf43"
                                testing="82ff1b0b-0e36-dc3c-5a11-d6200489a779"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _583px _100---tablet">
                                <div testing="82ff1b0b-0e36-dc3c-5a11-d6200489a77a" class="position-relative bigger">
                                    <div>
                                        <div class="inner-container mg-left-auto">
                                            <div class="image-wrapper border-radius-24px hero-img-wrapper"><img src="images/image1.png"
                                                    alt="image1" class="image cover" loading="eager"
                                                    sizes="(max-width: 991px) 100vw, (max-width: 1279px) 70vw, 874.5px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="Testing" class="section---product pd-200px">
            <div class="container-default pd-top container-fluid">
                <div class="div-block---product">
                    <div>
                        <div class="layout-grid grid---ben-fea-application text-right-default v2">
                            <div id="node36f240dd-7a6d-4443-c107-e9a9bdbc19c7-dcc7cf43"
                                testing="36f240dd-7a6d-4443-c107-e9a9bdbc19c7"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _614px product edited">
                                <div testing="36f240dd-7a6d-4443-c107-e9a9bdbc19c8"
                                    class="position-relative bigger-2">
                                    <div class="pd-bottom-34px">
                                        <div class="inner-container">
                                            <div class="image-wrapper border-radius-24px product-img-wrapper"><img src="images/image2.png"
                                                    alt="image2" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 100vw, (max-width: 767px) 86vw, (max-width: 991px) 85vw, (max-width: 1439px) 45vw, 589px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="node36f240dd-7a6d-4443-c107-e9a9bdbc19d0-dcc7cf43"
                                testing="36f240dd-7a6d-4443-c107-e9a9bdbc19d0"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _598px _100---tablet product-spacing-right">
                                <div class="text-center-tablet">
                                    <div class="inner-container _500px---tablet center">
                                        <div class="inner-container _400px---mbl center">
                                            <h2 class="heading-2---section">Are You Ready for a Lifelong Commitment?
                                            </h2>
                                        </div>
                                    </div>
                                    <p class="paragraph---section tablet-center"><em>Before you say &#x27;I do,&#x27;
                                            take our comprehensive readiness test. Assess your emotional and practical
                                            preparedness for the beautiful journey of marriage. Gain insights to ensure
                                            you step into this lifelong commitment with confidence.</em></p>
                                    <div class="div---subtext">
                                        <div class="sub-text">Discover Your Readiness</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="Analysis" class="section---product pd-200px">
            <div class="container-default pd-top container">
                <div class="div-block---product">
                    <div>
                        <div
                            class="layout-grid grid---ben-fea-application text-left-default v2 bottom-105px tablet-center edited">
                            <div testing="b7457577-e68e-bb34-8a59-eb3c692107c8"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _598px _100---tablet product-spacing-left">
                                <div class="text-center-tablet">
                                    <div class="inner-container-edited _500px---tablet center">
                                        <div class="inner-container-edited _400px---mbl center">
                                            <h2 class="heading-2---section">Discover Your True Self for Lasting Love
                                            </h2>
                                        </div>
                                    </div>
                                    <p class="paragraph---section tablet-center"><em>Our in-depth personality analysis
                                            goes beyond the surface, helping you uncover the unique traits that define
                                            you. This self-discovery journey is key to finding the best partner and
                                            creating a lifetime of happiness. Let&#x27;s dive into the depths of your
                                            personality together.</em></p>
                                    <div class="div---subtext longer">
                                        <div class="sub-text">Begin Your Journey to Self-Discovery</div>
                                    </div>
                                </div>
                            </div>
                            <div id="w-node-b7457577-e68e-bb34-8a59-eb3c692107d3-dcc7cf43"
                                testing="b7457577-e68e-bb34-8a59-eb3c692107d3"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="inner-container-edited _583px _100---tablet product edited">
                                <div testing="b7457577-e68e-bb34-8a59-eb3c692107d4"
                                    class="position-relative bigger-2">
                                    <div class="pd-bottom-43px">
                                        <div class="inner-container">
                                            <div class="image-wrapper border-radius-24px product-img-wrapper"><img src="images/image3.png"
                                                    alt="image3" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 100vw, (max-width: 767px) 86vw, (max-width: 991px) 85vw, (max-width: 1439px) 45vw, 590px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="Training" class="section---team hero v12">
            <div class="container-default container-fluid">
                <div class="position-relative z-index-1">
                    <div class="inner-container _806px center">
                        <div class="inner-container _600px---tablet center">
                            <div class="position-relative">
                                <div class="flex-horizontal align-center justify-center">
                                    <div class="position-relative z-index-1">
                                        <div class="text-center">
                                            <div class="mg-bottom-12px">
                                                <div testing="a435fd68-94a2-f7e1-1820-2a9948849858"
                                                    style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                    class="sub-text blue edited">TRAINING</div>
                                            </div>
                                            <h1 testing="a435fd68-94a2-f7e1-1820-2a994884985a"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="heading-2---section">Empower Yourself for a Fulfilling Marriage
                                            </h1>
                                            <p testing="a435fd68-94a2-f7e1-1820-2a994884985c"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="paragraph---section dark"><em>Access personalized training to
                                                    overcome challenges and build a strong foundation for a joyful life
                                                    together.</em></p><a href="#wf-form-Register-Form"
                                                class="btn-primary-edited general button">Start Your Empowerment
                                                Training</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute hero-v12-bg">
                <div class="position-absolute circle-bg-button-default-edited edited"></div>
            </div>
        </section>
        <section id="Matching" class="section---story hero v12 edited">
            <div class="container-default container">
                <div class="position-relative z-index-1">
                    <div class="inner-container _806px center">
                        <div class="inner-container _600px---tablet center">
                            <div class="position-relative">
                                <div class="flex-horizontal align-center justify-center">
                                    <div class="position-relative z-index-1">
                                        <div class="text-center">
                                            <h1 testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93714"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="heading-2---section bigger-2">Meet Your Perfect Match<br />with
                                                Matchology</h1>
                                            <p testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93716"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="paragraph---section dark"><em class="italic-text">Let our
                                                    advanced matching algorithm guide you to candidates who not only
                                                    align with your personality but complement it, ensuring a match made
                                                    for lasting happiness. Explore the possibilities of genuine
                                                    connections and meaningful relationships with Matchology.</em></p><a
                                                href="#wf-form-Register-Form"
                                                class="btn-primary-edited general orange button">Explore Matches</a>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93718" style="opacity:0"
                                        class="position-absolute integration-hero-bg---03">
                                        <div class="image-wrapper"><img src="images/shape.svg" alt="shape"
                                                class="image cover" loading="eager" />
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9371b" style="opacity:0"
                                        class="position-absolute integration-hero-bg---04">
                                        <div class="image-wrapper"><img src="images/shape.svg" alt="shape"
                                                class="image cover" loading="eager" />
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9371e" style="opacity:0"
                                        class="position-absolute integration-hero-icon---01 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l1.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93722" style="opacity:0"
                                        class="position-absolute integration-hero-icon---02 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l2.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93726" style="opacity:0"
                                        class="position-absolute integration-hero-icon---03 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l3.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9372a" style="opacity:0"
                                        class="position-absolute integration-hero-icon---04 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l4.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9372e" style="opacity:0"
                                        class="position-absolute integration-hero-icon---05 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l5.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93732" style="opacity:0"
                                        class="position-absolute integration-hero-icon---06 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l6.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93736" style="opacity:0"
                                        class="position-absolute integration-hero-icon---07 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/l7.png"
                                                    alt="l1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9373a" style="opacity:0"
                                        class="position-absolute integration-hero-icon---08 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r1.png"
                                                    alt="r1" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9373e" style="opacity:0"
                                        class="position-absolute integration-hero-icon---09 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r2.png"
                                                    alt="r2" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93742" style="opacity:0"
                                        class="position-absolute integration-hero-icon---10 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r3.png"
                                                    alt="r3" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db93746" style="opacity:0"
                                        class="position-absolute integration-hero-icon---11 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r4.png"
                                                    alt="r4" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9374a" style="opacity:0"
                                        class="position-absolute integration-hero-icon---12 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r5.png"
                                                    alt="r5" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                    <div testing="9a6d3b67-46ca-64b5-ff5e-e91b8db9374e" style="opacity:0"
                                        class="position-absolute integration-hero-icon---13 bigger">
                                        <div class="position-absolute full">
                                            <div class="image-wrapper border-radius-18px"><img src="images/r6.png"
                                                    alt="r5" class="image cover" loading="eager"
                                                    sizes="(max-width: 479px) 117px, (max-width: 767px) 24vw, (max-width: 991px) 15vw, 145px" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="Who-for" class="section---product pd-edited">
            <div class="container-default container">
                <div class="position-relative z-index-1">
                    <div class="inner-container _806px center">
                        <div class="inner-container _600px---tablet center">
                            <div class="position-relative">
                                <div class="flex-horizontal align-center justify-center">
                                    <div class="position-relative z-index-1">
                                        <div class="text-center">
                                            <h1 testing="f96efd2b-e1f6-2653-83f8-ca3f382a173b"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="heading-2---section">Tailored Solutions for Every Relationship
                                            </h1>
                                            <p testing="f96efd2b-e1f6-2653-83f8-ca3f382a173d"
                                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                class="paragraph---section dark"><em>Perfect for those seeking suitable
                                                    partners, couples facing challenges, and parents navigating their
                                                    children&#x27;s struggles.</em></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner-container _500px---tablet center mg-edited">
                    <div class="inner-container _400px---mbl center product-card">
                        <div class="layout-grid grid---product gap-40px _1-col-tablet rdited">
                            <div id="node0e75b9fd-3baa-f3bf-0952-c57632c71a20-dcc7cf43"
                                testing="0e75b9fd-3baa-f3bf-0952-c57632c71a20"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="card">
                                <div class="image-wrapper card-feature-v2-image bg-color"><img src="images/card1.png"
                                        alt="card1" class="image cover" loading="eager"
                                        sizes="(max-width: 479px) 83vw, (max-width: 767px) 43vw, (max-width: 991px) 29vw, (max-width: 1439px) 22vw, 288px" />
                                </div>
                                <div class="card-feature-v2-content edited">
                                    <h3 class="heading-3---element edited">Individuals Seeking Suitable Candidates</h3>
                                    <p class="paragraph---section edited"><em>Discover the joy of companionship.
                                            Matchology caters to singles on the journey to find a compatible life
                                            partner, ensuring a harmonious and fulfilling union.</em></p>
                                </div>
                            </div>
                            <div id="node0e75b9fd-3baa-f3bf-0952-c57632c71a28-dcc7cf43"
                                testing="0e75b9fd-3baa-f3bf-0952-c57632c71a28"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="card">
                                <div class="image-wrapper card-feature-v2-image bg-color"><img src="images/card2.png"
                                        alt="card2" class="image cover" loading="eager"
                                        sizes="(max-width: 479px) 83vw, (max-width: 767px) 43vw, (max-width: 991px) 29vw, (max-width: 1439px) 22vw, 288px" />
                                </div>
                                <div class="card-feature-v2-content edited">
                                    <h3 class="heading-3---element edited">Couples Struggling with Relationship Issues
                                    </h3>
                                    <p class="paragraph---section edited"><em>Navigate the complexities of love with
                                            Matchology. Our tailored solutions offer support and guidance to couples
                                            facing challenges, fostering stronger and happier relationships.</em></p>
                                </div>
                            </div>
                            <div id="w-node-d8309eb8-f81b-2ed8-6df2-2ea4bfc6806f-dcc7cf43"
                                testing="d8309eb8-f81b-2ed8-6df2-2ea4bfc6806f"
                                style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                class="card">
                                <div class="image-wrapper card-feature-v2-image bg-color"><img src="images/card3.png"
                                        alt="card3" class="image cover" loading="eager"
                                        sizes="(max-width: 479px) 83vw, (max-width: 767px) 43vw, (max-width: 991px) 29vw, (max-width: 1439px) 22vw, 288px" />
                                </div>
                                <div class="card-feature-v2-content edited">
                                    <h3 class="heading-3---element edited">Couples Facing Challenges with their Kids
                                    </h3>
                                    <p class="paragraph---section edited"><em>For parents navigating the intricate path
                                            of family life, Matchology provides personalized assistance. Overcome
                                            challenges, strengthen family bonds, and create a nurturing environment for
                                            your children.</em></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="Contact" class="section---contact pd-144px pd-top-0px overflow-hidden">
            <div class="container---contact mg container">
                <div class="inner-container _600px---tablet center">
                    <div class="inner-container _500px---mbl center center-fix">
                        <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4d3-dcc7cf43"
                            testing="fb13afda-0694-e672-070b-75f531a8a4d3"
                            style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                            class="inner-container _686px width-100 _100---tablet">
                            <div testing="fb4d2841-70b3-fb57-e355-6d05887daa28" style="opacity:0"
                                class="position-absolute integration-hero-bg---01 bigger">
                                <div class="image-wrapper"><img src="images/shape.svg" alt="shape" class="image cover"
                                        loading="eager" />
                                </div>
                            </div>
                            <div testing="ff83df47-7be5-6dff-e964-1f7ca5f138e4" style="opacity:0"
                                class="position-absolute integration-hero-bg---02 bigger">
                                <div class="image-wrapper"><img src="images/shape.svg" alt="shape" class="image cover"
                                        loading="eager" />
                                </div>
                            </div>
                            <div class="position-relative">
                                <div class="position-relative contact-form-wrapper">
                                    <div class="position-relative z-index-1">
                                        <div class="card contact-form">
                                            <div class="mg-bottom-28px">
                                                <div class="inner-container-edited _699px center">
                                                    <div testing="fb13afda-0694-e672-070b-75f531a8a4da"
                                                        style="-webkit-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 10%, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                                        class="text-center">
                                                        <div class="inner-container-edited _613px center">
                                                            <div class="inner-container-edited _500px---tablet center">
                                                                <div class="inner-container-edited _400px---mbl center">
                                                                    <div class="mg-bottom-10px">
                                                                        <div class="text---subheading-section blue">
                                                                            CONTACT US</div>
                                                                    </div>
                                                                    <h2 class="heading-2---section">Ask Any Questions
                                                                    </h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="paragraph---section center mg">We are more than happy
                                                            to learn more about your unique needs and how we can support
                                                            you.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="contact-form-block form">
                                                <form id="contact-form" name="contact-form" action=""
                                                    class="contact-form-grid">

                                                    <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4e7-dcc7cf43">
                                                        <label for="First-Name" class="field-label-edited mg">First
                                                            Name</label><input class="input-edited input"
                                                            maxlength="256" name="First-Name" data-name="First Name"
                                                            placeholder="John " type="text" id="First-Name"
                                                            required="" />
                                                    </div>
                                                    <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4eb-dcc7cf43">
                                                        <label for="Last-Name" class="field-label-edited mg">Last
                                                            Name</label><input class="input-edited input"
                                                            maxlength="256" name="Last-Name" data-name="Last Name"
                                                            placeholder="Carter" type="text" id="Last-Name"
                                                            required="" />
                                                    </div>
                                                    <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4ef-dcc7cf43">
                                                        <label for="email" class="field-label-edited mg">Email
                                                            Address</label><input class="input-edited input"
                                                            maxlength="256" name="email" data-name="Email"
                                                            placeholder="example@email.com" type="email" id="email"
                                                            required="" />
                                                    </div>
                                                    <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4f3-dcc7cf43">
                                                        <label for="phone"
                                                            class="field-label-edited mg">Phone</label><input
                                                            class="input-edited input" maxlength="256" name="phone"
                                                            data-name="Phone" placeholder="(123) 456 - 789" type="tel"
                                                            id="phone" required="" />
                                                    </div>
                                                    <div id="w-node-fb13afda-0694-e672-070b-75f531a8a4f7-dcc7cf43"
                                                        class="text-area-wrapper">
                                                        <div class="div-block---contact-field"><label for="Country"
                                                                class="field-label-edited mg">Country</label><select
                                                                id="Country" name="Country" data-name="Country"
                                                                required="" class="input-edited text-fix select">
                                                                <option value="">Select country</option>
                                                                <option value="Afghanistan">Afghanistan</option>
                                                                <option value="Akrotiri">Akrotiri</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antarctica">Antarctica</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda
                                                                </option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Ashmore and Cartier Islands">Ashmore and
                                                                    Cartier Islands</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Bassas da India">Bassas da India</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bosnia and Herzegovina">Bosnia and
                                                                    Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Bouvet Island">Bouvet Island</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Territory">British
                                                                    Indian Ocean Territory</option>
                                                                <option value="British Virgin Islands">British Virgin
                                                                    Islands</option>
                                                                <option value="Brunei">Brunei</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burma">Burma</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African
                                                                    Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island
                                                                </option>
                                                                <option value="Clipperton Island">Clipperton Island
                                                                </option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling)
                                                                    Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Democratic Republic of the Congo">
                                                                    Democratic Republic of the Congo</option>
                                                                <option value="Republic of the Cook Islands">Republic of
                                                                    the Cook Islands</option>
                                                                <option value="Coral Sea Islands">Coral Sea Islands
                                                                </option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote d&#x27;Ivoire">Cote d&#x27;Ivoire
                                                                </option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Dhekelia">Dhekelia</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic
                                                                </option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea
                                                                </option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Europa Island">Europa Island</option>
                                                                <option value="Falkland Islands (Islas Malvinas)">
                                                                    Falkland Islands (Islas Malvinas)</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia
                                                                </option>
                                                                <option value="French Southern and Antarctic Lands">
                                                                    French Southern and Antarctic Lands</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Gaza Strip">Gaza Strip</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Glorioso Islands">Glorioso Islands
                                                                </option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guernsey">Guernsey</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Heard Island and McDonald Islands">Heard
                                                                    Island and McDonald Islands</option>
                                                                <option value="Holy See (Vatican City)">Holy See
                                                                    (Vatican City)</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran">Iran</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Jan Mayen">Jan Mayen</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jersey">Jersey</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Juan de Nova Island">Juan de Nova Island
                                                                </option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea, North">Korea, North</option>
                                                                <option value="Korea, South">Korea, South</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Laos">Laos</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macau">Macau</option>
                                                                <option value="Macedonia">Macedonia</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands
                                                                </option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Micronesia">Micronesia</option>
                                                                <option value="Moldova">Moldova</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montenegro">Montenegro</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Namibia">Namibia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Navassa Island">Navassa Island</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherlands">Netherlands</option>
                                                                <option value="Netherlands Antilles">Netherlands
                                                                    Antilles</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Northern Mariana Islands">Northern
                                                                    Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea
                                                                </option>
                                                                <option value="Paracel Islands">Paracel Islands</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Philippines">Philippines</option>
                                                                <option value="Pitcairn Islands">Pitcairn Islands
                                                                </option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Helena">Saint Helena</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and
                                                                    Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre
                                                                    and Miquelon</option>
                                                                <option value="Saint Vincent and the Grenadines">Saint
                                                                    Vincent and the Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome and Principe">Sao Tome and
                                                                    Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Serbia">Serbia</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option
                                                                    value="South Georgia and the South Sandwich Islands">
                                                                    South Georgia and the South Sandwich Islands
                                                                </option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Spratly Islands">Spratly Islands</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard">Svalbard</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syria">Syria</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania">Tanzania</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-Leste">Timor-Leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago
                                                                </option>
                                                                <option value="Tromelin Island">Tromelin Island</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and
                                                                    Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab
                                                                    Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Virgin Islands">Virgin Islands</option>
                                                                <option value="Wake Island">Wake Island</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna
                                                                </option>
                                                                <option value="West Bank">West Bank</option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                            </select></div><label for="message"
                                                            class="field-label-edited mg">How Can We Help
                                                            You?</label><textarea id="message" name="message"
                                                            maxlength="5000" data-name="Message"
                                                            placeholder="Please type your message here..." required=""
                                                            class="text-area-edited input"></textarea>
                                                    </div><input type="submit" data-wait="Please wait..."
                                                        id="w-node-fb13afda-0694-e672-070b-75f531a8a4ff-dcc7cf43"
                                                        class="btn-primary-edited color button" value="Submit" />
                                                </form>
                                                <div class="success-message form-done">
                                                    <div>
                                                        <div class="line-rounded-icon success-message-check large">
                                                        </div>
                                                        <div>Your message has been submitted. <br />We will get back to
                                                            you within 24-48 hours.</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div testing="fb13afda-0694-e672-070b-75f531a8a50b"
                                        style="-webkit-transform:translate3d(0, 80%, 0) scale3d(0.5, 0.5, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 80%, 0) scale3d(0.5, 0.5, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 80%, 0) scale3d(0.5, 0.5, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 80%, 0) scale3d(0.5, 0.5, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0"
                                        class="position-absolute contact-form-shape color-accent-edited-green mg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="w-full bg-ml-color-sega px-2 mt-auto">
    <div class="w-full max-w-screen-xl mx-auto">
        <ul class="w-full flex flex-wrap items-center py-3 text-sm font-medium text-black sm:mb-0 sm:justify-around">
            <li><a href="#Testing" class="hover:underline me-4 md:me-6">Testing</a></li>
            <li><a href="#Analysis" class="hover:underline me-4 md:me-6">Analysis</a></li>
            <li><a href="#Training" class="hover:underline me-4 md:me-6">Training</a></li>
            <li><a href="#Matching" class="hover:underline me-4 md:me-6">Matching</a></li>
            <li><a href="#Who-for" class="hover:underline">Solutions</a></li>
        </ul>
        <hr class="border-black sm:mx-auto" />
         <div class="w-full max-w-screen-xl mx-auto">
        <div class="flex items-center justify-between mt-4">
            <a href="#  " class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('assets/media/logos/logo3.png') }}" class="h-8" alt="Matchology Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Matchology</span>
            </a>
            <ul class="flex flex-wrap sm:justify-end items-right">
    <li>
        <a href="#" class="hover:underline text-[22px] me-4 md:me-6">Privacy Policy</a>
    </li>
    <li>
        <a href="#" class="hover:underline text-[22px] me-1">Terms of Service</a>
    </li>
</ul>

        </div>
        <span class="block text-sm text-gray-500 sm:text-right dark:text-gray-400 text-right">
            <a href="#"> © 2024 Matchology™ . All Rights Reserved.</a>
        </span>
    </div>
    </div>

    </div>

</footer>

    </div>
    <!-- Webflow/legacy scripts removed to avoid re-applying hidden styles. Page renders without them. -->
    <script>
        // Header mobile menu toggle (scoped to this page)
        (function(){
            const menuSelector = '.header-nav-menu-wrapper-edited.nav-menu';
            const buttonSelector = '.hamburger-menu-wrapper-edited';
            const menu = document.querySelector(menuSelector);
            const btn = document.querySelector(buttonSelector);
            if (!menu || !btn) return;

            function setExpanded(expanded) {
                btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                menu.classList.toggle('is-open', !!expanded);
                document.documentElement.classList.toggle('nav-open', !!expanded);
            }

            function toggleMenu(e){
                if (e) { e.preventDefault(); e.stopPropagation(); }
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                setExpanded(!expanded);
            }

            btn.addEventListener('click', toggleMenu);
            btn.addEventListener('keydown', function(e){
                if (e.key === 'Enter' || e.key === ' '){ toggleMenu(e); }
            });

            // Close on link click (mobile)
            menu.addEventListener('click', function(e){
                const a = e.target.closest('a');
                if (a) setExpanded(false);
            });

            // Close on outside click
            document.addEventListener('click', function(e){
                if (e.target.closest(buttonSelector) || e.target.closest(menuSelector)) return;
                setExpanded(false);
            });
        })();
    </script>
</body>

</html>
