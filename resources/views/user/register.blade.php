<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
</head>

<body>
<!-- Content -->

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <div class="app-brand justify-content-center">
                        <a href="" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-body fw-bolder">INSCRIPTION</span>
                        </a>
                    </div>
                    <form id="formAuthentication" class="mb-3" action="{{ url('user/register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-4" >
                                <label for="nom" class="form-label">Nom</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="id_nom"
                                    name="nom"
                                    value="{{ old('nom') }}"
                                    autofocus
                                />
                                @error('nom')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="dtn" class="form-label">Date de naissance</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="id_dtn"
                                    name="dtn"
                                    value="{{ old('dtn') }}"
                                />
                                @error('nom')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="contact" class="form-label">Contact</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="id_contact"
                                    name="contact"
                                    value="{{ old('contact') }}"
                                />
                                @error('contact')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div>

                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" />
                            @error('email')
                            <div class="fex">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pseudo" class="form-label">Pseudo</label>
                            <input
                                type="text"
                                class="form-control"
                                id="id_pseudo"
                                name="pseudo"
                                value="{{ old('pseudo') }}"
                            />
                            @error('pseudo')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Mot de passe</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password"
                                />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                            <div class="fex">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Confirmer mot de passe</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="confirm_password"
                                    class="form-control"
                                    name="password_confirmation"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password_confirmation"
                                />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password_confirmation')
                                <div class="fex">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary d-grid w-100">Sign up</button>
                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{ url('/') }}">
                            <span>Sign in instead</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
</div>

<!-- / Content -->
</body>
</html>

