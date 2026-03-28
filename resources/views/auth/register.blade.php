<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __('admin.register_store') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" href="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}"
        type="image/x-icon" />

    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #93cef0;
            --dark: #28265c;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
            padding: 30px;
        }

        .register-card h3 {
            color: var(--dark);
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(147, 206, 240, .4);
        }

        .btn-main {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            border: none;
            color: #fff;
            padding: 12px 40px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-main:hover {
            opacity: .9;
        }

        .register-logo {
            max-width: 90px;
            height: auto;
        }

        .invalid-feedback {
            display: flex;
        }

        input[type=email] {
            direction: rtl;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="register-card">
                    <div class="text-center mb-4">
                        <img src="{{ $setting ? $setting->logoLink : URL::asset('assets/img/favicon.png') }}"
                            alt="Logo" class="mb-3 register-logo">

                        <h3 class="mb-0">{{ __('admin.register_store') }}</h3>
                    </div>

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <span class="alert-inner--icon">
                                <i class="fe fe-slash"></i>
                            </span>
                            <span class="alert-inner--text">
                                {{ session('error') }}
                            </span>
                        </div>
                    @endif

                    <form class="row g-3" action="{{ route('signUp') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <label class="form-label" for="image">{{ __('admin.image') }}</label>
                            <input type="file" class="form-control" @error('image') is-invalid @enderror
                                id="image" name="image">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="name">{{ __('admin.name') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('name') }}"
                                @error('name') is-invalid @enderror id="name" name="name"
                                placeholder="{{ __('admin.name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">{{ __('admin.email') }}</label>
                            <input type="email" class="form-control" value="{{ old('email') }}"
                                @error('email') is-invalid @enderror id="email" name="email"
                                placeholder="{{ __('admin.email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">{{ __('admin.phone') }} <span
                                        class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control" value="{{ old('phone') }}"
                                    @error('phone') is-invalid @enderror id="phone" name="phone"
                                    placeholder="{{ __('admin.phone') }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="city_id">{{ __('admin.cities') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <select class="form-select" @error('city_id') is-invalid @enderror id="city_id"
                                name="city_id" required>
                                <option value="" disabled selected>{{ __('admin.choose_city') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="password">{{ __('admin.password') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="password" class="form-control" @error('password') is-invalid @enderror
                                id="password" name="password" placeholder="{{ __('admin.password') }}" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="category_id">{{ __('admin.categories') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <select class="form-select" @error('category_id') is-invalid @enderror id="category_id"
                                name="category_id" required>
                                <option value="" disabled selected>{{ __('admin.choose_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="latitude">{{ __('admin.latitude') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('latitude') }}"
                                @error('latitude') is-invalid @enderror id="latitude" name="latitude"
                                placeholder="{{ __('admin.latitude') }}" required>
                            @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="longitude">{{ __('admin.longitude') }} <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('longitude') }}"
                                @error('longitude') is-invalid @enderror id="longitude" name="longitude"
                                placeholder="{{ __('admin.longitude') }}" required>
                            @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @foreach ($languages as $language)
                            <div class="col-12">
                                <label class="form-label"
                                    for="address_{{ $language }}">{{ __('admin.address') }}
                                    ({{ __("admin.$language") }})
                                    <span class="text-danger fw-bolder">*</span></label>
                                <input type="text" class="form-control" value="{{ old("address_$language") }}"
                                    @error('address_{{ $language }}') is-invalid @enderror
                                    id="address_{{ $language }}" name="address_{{ $language }}"
                                    placeholder="{{ __('admin.address') }}" required>
                                @error('address_{{ $language }}')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach

                        <div class="col-12">
                            <label class="form-label" for="cover_image">{{ __('admin.cover_image') }}</label>
                            <input type="file" class="form-control" @error('cover_image') is-invalid @enderror
                                id="cover_image" name="cover_image">
                            @error('cover_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="commercial_registration">صورة السجل التجاري <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="file" class="form-control"
                                @error('commercial_registration') is-invalid @enderror id="commercial_registration"
                                name="commercial_registration" required>
                            @error('commercial_registration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="col-12">
                            <label class="form-label" for="license"> صورة الرخصة <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="file" class="form-control" @error('license') is-invalid @enderror
                                id="license" name="license" required>
                            @error('license')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>





                        <div class="col-md-6">
                            <label class="form-label" for="bank_name">اسم البنك <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('bank_name') }}"
                                @error('bank_name') is-invalid @enderror id="bank_name" name="bank_name"
                                placeholder="اسم البنك" required>
                            @error('bank_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="account_holder_name">اسم صاحب الحساب <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('account_holder_name') }}"
                                @error('account_holder_name') is-invalid @enderror id="account_holder_name"
                                name="account_holder_name" placeholder="اسم صاحب الحساب" required>
                            @error('account_holder_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="IBAN">رقم IBAN <span
                                    class="text-danger fw-bolder">*</span></label>
                            <input type="text" class="form-control" value="{{ old('IBAN') }}"
                                @error('IBAN') is-invalid @enderror id="IBAN" name="IBAN"
                                placeholder="رقم IBAN" required>
                            @error('IBAN')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                    id="terms" name="terms" value="1">

                                <label class="form-check-label" for="terms">
                                    أوافق على
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">
                                        الشروط والأحكام
                                    </a>
                                </label>

                                @error('terms')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-main">
                                {{ __('admin.register') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">الشروط والأحكام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    {!! $storeTerms->store_terms ?? 'لا توجد شروط متاحة حالياً' !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        إغلاق
                    </button>

                    <button type="button" class="btn btn-primary" id="acceptTerms">
                        أوافق على الشروط
                    </button>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const termsCheckbox = document.getElementById('terms');
        const submitBtn = document.getElementById('submitBtn');
        const acceptBtn = document.getElementById('acceptTerms');
        const termsModal = new bootstrap.Modal(document.getElementById('termsModal'));

        // تفعيل/تعطيل زر التسجيل
        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });

        // عند الضغط على "أوافق" داخل المودال
        acceptBtn.addEventListener('click', function() {
            termsCheckbox.checked = true;
            submitBtn.disabled = false;
            termsModal.hide();
        });
    </script>

</body>

</html>
