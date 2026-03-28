<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#26235c">
    <title>المنقذ الشامل – حمّل التطبيق الآن</title>
    <meta name="description"
        content="حمّل تطبيق المنقذ الشامل الآن على iOS وأندرويد. اطلب فنيين موثوقين بكل سهولة، وتابع طلبك خطوة بخطوة، مع دفع آمن وتقييمات حقيقية.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="{{ asset('landing-page/css/style.css') }}?v={{ filemtime(public_path('landing-page/css/style.css')) }}">
</head>

<body>
    <header>
        <nav class="container nav">
            <div class="brand">
                <img src="{{ asset('landing-page/img/logo.png') }}" width="150">
            </div>
            <div class="nav-cta">
                <a class="btn-minor" href="#download" title="روابط التحميل">تحميل التطبيق</a>
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero -->
        <section class="container hero" id="home">
            <div class="reveal show">
                <span class="pill">كل خدماتك بصيانة واحدة</span>
                <h2>تبغى فني سريع وثقة؟ كلّه عندنا</h2>
                <p class="lead">من السباكة والكهرباء للمكيفات والنجارة… اطلب الخدمة بدقيقتين وبتوصلك عروض أسعار من
                    فنيين موثوقين، وتابع طلبك لين يخلص، والدفع آمن.</p>
                <div class="cta" id="download">
                    <a class="store" target="_blank" rel="noopener" href="{{ $setting->ios_app_link }}"
                        aria-label="App Store"><img width="130" src="{{ asset('landing-page/img/app.png') }}"></a>
                    <a class="store ghost" target="_blank" rel="noopener" href="{{ $setting->android_app_link }}"
                        aria-label="Google Play"><img width="130"
                            src="{{ asset('landing-page/img/google.png') }}"></a>
                </div>
                <div class="trust">
                    <span class="item"><span class="badge">سريع التنفيذ</span> <span>تعبّي الطلب وتوصلك
                            العروض</span></span>
                    <span class="item"><span class="badge">فنيين موثوقين</span> <span>تقييمات حقيقية
                            وضمان</span></span>
                </div>
            </div>
            <div class="device reveal show" aria-hidden="true">
                <div class="status">المنقذ الشامل • شغّال</div>
                <div class="screen">
                    <div class="chip">
                        <strong>ابدأ خلال ثواني</strong>
                        <small>سجّل، اطلب الخدمة، والباقي علينا</small>
                    </div>
                </div>
            </div>
        </section>

        <!-- Primary features -->
        <section class="container" id="features">
            <div class="section-title">
                <h3>ليش تختار المنقذ الشامل؟</h3>
            </div>
            <div class="grid">
                <article class="card reveal show">
                    <div class="icon">⚡</div>
                    <h4>سرعة واستقرار</h4>
                    <p>التطبيق خفيف ويستجيب بسرعة حتى مع اتصال ضعيف.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🎯</div>
                    <h4>واجهة سهلة</h4>
                    <p>كل شيء واضح وخطوتين وتكون مخلّص طلبك.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🔒</div>
                    <h4>خصوصية وأمان</h4>
                    <p>حماية بياناتك بالدفع الآمن والتشفير.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🧩</div>
                    <h4>كل الأدوات بمكان واحد</h4>
                    <p>من طلب الخدمة للتقييم — رحلة مكتملة داخل التطبيق.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">📲</div>
                    <h4>على كل الأجهزة</h4>
                    <p>iOS وأندرويد بتجربة موحّدة وسلسة.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🛠️</div>
                    <h4>تحديثات مستمرة</h4>
                    <p>نطوّر باستمرار اعتمادًا على ملاحظاتكم.</p>
                </article>
            </div>
        </section>

        <!-- NEW: About (Saudi colloquial) -->
        <section class="container" id="about">
            <div class="section-title">
                <h3>عن التطبيق – وش يميّزنا؟</h3>
            </div>
            <div class="grid">
                <article class="card reveal show" style="grid-column:span 7">
                    <h4>تلقى الفني الصح بسرعة</h4>
                    <p>بدال ما تضيع وقتك بالاتصالات، المنقذ الشامل يوصّلك بفنيين متمرّسين بالسباكة، الكهرباء، المكيفات،
                        والسيارات وغيرها. تحط التفاصيل، وتجيك عروض أسعار تنافسية، وتختار الأنسب لك.</p>
                    <div class="chips" style="margin-top:12px">
                        <span class="chip">بنشر</span>
                        <span class="chip">بطاريات</span>
                        <span class="chip">سحب سيارة</span>
                        <span class="chip">سباكة</span>
                        <span class="chip">كهرباء</span>

                        <span class="chip">مكيفات</span>

                        <span class="chip">جبس</span>

                        <span class="chip">تنظيف</span>
                        <span class="chip">نقل عفش</span>
                        <span class="chip">حدادة</span>
                        <span class="chip">ميكانيكا سيارات</span>

                    </div>
                </article>
                <aside class="card reveal show" style="grid-column:span 5">
                    <h4>أرقام تثبت الجودة</h4>
                    <div class="counters">
                        <div class="counter">
                            <div class="num" data-target="12500">12500</div>
                            <div class="label">طلبات مكتملة</div>
                        </div>
                        <div class="counter">
                            <div class="num" data-target="4.8" data-decimals="1">4.8</div>
                            <div class="label">متوسط التقييم /5</div>
                        </div>
                        <div class="counter">
                            <div class="num" data-target="25">25</div>
                            <div class="label">متوسط وقت الوصول (دقيقة)</div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- NEW: How it works (Saudi colloquial) -->
        <section class="container" id="how">
            <div class="section-title">
                <h3>كيف يشتغل التطبيق؟</h3>
            </div>
            <div class="steps">
                <div class="step reveal show">
                    <div class="num">1</div>
                    <h4>اختر الخدمة</h4>
                    <p>من عشرات الفئات — تلاقي اللي يناسبك.</p>
                </div>
                <div class="step reveal show">
                    <div class="num">2</div>
                    <h4>حدد التفاصيل</h4>
                    <p>وصف بسيط، موقعك، والوقت اللي يناسبك.</p>
                </div>
                <div class="step reveal show">
                    <div class="num">3</div>
                    <h4>توصلك العروض</h4>
                    <p>قارن الأسعار والتقييمات وخذ قرارك.</p>
                </div>
                <div class="step reveal show">
                    <div class="num">4</div>
                    <h4>تنفيذ وتقييم</h4>
                    <p>تابع التنفيذ داخل التطبيق وقيّم الفني.</p>
                </div>
            </div>
        </section>

        <!-- NEW: Users find-all section -->
        <section class="container" id="for-users">
            <div class="section-title">
                <h3>لك أنت – كل خدماتك بمكان واحد</h3>
            </div>
            <div class="grid">
                <article class="card reveal show">
                    <div class="icon">🏠</div>
                    <h4>صيانة البيت بالكامل</h4>
                    <p>من السباكة والكهرباء للدهانات والجبس — خدمة محترفة وسريعة.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🚗</div>
                    <h4>خدمات السيارات</h4>
                    <p>بنشر، بطاريات، سحب سيارة — نجيك وين ما كنت.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">❄️</div>
                    <h4>مكيفات</h4>
                    <p>تنظيف، تعبئة فريون، تركيب وصيانة.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🧼</div>
                    <h4>تنظيف ونقل</h4>
                    <p>تنظيف عميق ونقل عفش مع تغليف.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🪚</div>
                    <h4>نجارة وحدادة</h4>
                    <p>تفصيل، تركيب وصيانة بشكل متقن.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🪟</div>
                    <h4>ألمنيوم وزجاج</h4>
                    <p>تصليح واستبدال بسرعة وأسعار مناسبة.</p>
                </article>
            </div>
        </section>

        <!-- NEW: Technicians earnings section -->
        <section class="container" id="for-techs">
            <div class="section-title">
                <h3>للفنيين – زد دخلك معانا</h3>
            </div>
            <div class="grid">
                <article class="card reveal show" style="grid-column:span 7">
                    <h4>طلبات جاهزة توصلك يوميًا</h4>
                    <p>سجّل كفني وابدأ تستقبل طلبات قريبة منك، وتابع مواعيدك من مكان واحد. بدون تعقيد ولا عمولات مخفية.
                    </p>
                    <ul style="margin:.5rem 0 0; padding-inline-start:1.2rem; color:var(--muted)">
                        <li>جدولة ذكية وتنبيهات فورية.</li>
                        <li>ملف شخصي وتقييمات ترفع ظهورك.</li>
                        <li>مدفوعات آمنة وتحويلات سريعة.</li>
                    </ul>
                    <div class="cta" style="margin-top:10px">
                        <a class="store" href="#"
                            onclick="alert('قريبًا: صفحة تسجيل الفنيين'); return false;">سجّل كفني</a>
                        <a class="store ghost" href="#for-techs">تعرّف أكثر</a>
                    </div>
                </article>
                <aside class="card reveal show" style="grid-column:span 5">
                    <h4>وش تستفيد؟</h4>
                    <div class="counters">
                        <div class="counter">
                            <div class="num" data-target="35">35</div>
                            <div class="label">متوسط الطلبات/أسبوع</div>
                        </div>
                        <div class="counter">
                            <div class="num" data-target="92">92</div>
                            <div class="label">٪ نسبة رضا العملاء</div>
                        </div>
                        <div class="counter">
                            <div class="num" data-target="18">18</div>
                            <div class="label">مدّة قبول العرض (دقيقة)</div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- Expanded features -->
        <section class="container" id="xfeatures">
            <div class="section-title">
                <h3>مزايا أكثر… تجربة أقوى</h3>
            </div>
            <div class="grid">
                <article class="card reveal show">
                    <div class="icon">💬</div>
                    <h4>مراسلة داخل التطبيق</h4>
                    <p>توضح المطلوب وتتفق على التفاصيل مع الفني.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🔔</div>
                    <h4>تنبيهات فورية</h4>
                    <p>تعرف وش يصير على طلبك لحظة بلحظة.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">📍</div>
                    <h4>تحديد موقع دقيق</h4>
                    <p>خرائط مدمجة لوصول أسرع.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">⭐</div>
                    <h4>تقييمات شفافة</h4>
                    <p>تشوف آراء العملاء قبل الاختيار.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">💳</div>
                    <h4>خيارات دفع مرنة</h4>
                    <p>وسائل دفع متعددة — براحتك.</p>
                </article>
                <article class="card reveal show">
                    <div class="icon">🛡️</div>
                    <h4>أمان عالي</h4>
                    <p>حماية لبياناتك ومعايير أمان محدثة.</p>
                </article>
            </div>
        </section>

        <!-- Screenshots (placeholders) -->
        <section class="container" id="screens">
            <div class="section-title">
                <h3>لمحات من داخل التطبيق</h3>
            </div>
            <div class="screens" aria-label="لقطات شاشة (أماكن مخصصة للصور)">

                <div class="shot reveal show"
                    style="background: url('{{ asset('landing-page/app/Artboard3.png') }}') no-repeat ; background-size: cover;">
                    واجهة الطلب
                </div>
                <div class="shot reveal show"
                    style="background: url('{{ asset('landing-page/app/Artboard4.png') }}') no-repeat ; background-size: cover;">
                    قائمة العروض
                </div>
                <div class="shot reveal show"
                    style="background: url('{{ asset('landing-page/app/Artboard5.png') }}') no-repeat ; background-size: cover;">
                    دردشة مع الفني
                </div>
                <div class="shot reveal show"
                    style="background: url('{{ asset('landing-page/app/Artboard6.png') }}') no-repeat ; background-size: cover;">
                    تتبع الوصول
                </div>
                <div class="shot reveal show"
                    style="background: url('{{ asset('landing-page/app/Artboard7.png') }}') no-repeat ; background-size: cover;">
                    صفحة التقييم
                </div>

            </div>
        </section>

        <!-- FAQ (15) -->
        <section class="container" id="faq">
            <div class="section-title">
                <h3>أسئلة كثير تجينا</h3>
            </div>
            <details class="reveal show">
                <summary>هل التطبيق مجاني؟</summary>
                <p>إي نعم، التحميل والاستخدام مجاني. بعض الميزات الإضافية ممكن تكون اختيارية.</p>
            </details>
            <details class="reveal show">
                <summary>كيف أطلب خدمة؟</summary>
                <p>تختار الفئة، تكتب التفاصيل، وتوصلك عروض من فنيين معتمدين.</p>
            </details>
            <details class="reveal show">
                <summary>الفنيين موثوقين؟</summary>
                <p>أكيد، نتحقق من الهوية والخبرة قبل قبولهم.</p>
            </details>
            <details class="reveal show">
                <summary>وش طرق الدفع؟</summary>
                <p>إلكتروني آمن أو عند الاستلام — اللي يريحك.</p>
            </details>
            <details class="reveal show">
                <summary>أقدر أحدد وقت الزيارة؟</summary>
                <p>تحدد الوقت المناسب لك أثناء إنشاء الطلب.</p>
            </details>
            <details class="reveal show">
                <summary>في خدمات للسيارات؟</summary>
                <p>نعم، من البنشر للبطاريات وحتى سحب السيارة.</p>
            </details>
            <details class="reveal show">
                <summary>أقدر ألغِي الطلب؟</summary>
                <p>تقدر تلغيه قبل ما يبدأ التنفيذ.</p>
            </details>
            <details class="reveal show">
                <summary>تأخّر الفني، وش أسوي؟</summary>
                <p>تتواصل مع الدعم من داخل التطبيق ونحلّها فورًا.</p>
            </details>
            <details class="reveal show">
                <summary>أقدر أكلم الفني قبل أوافق؟</summary>
                <p>نعم، تراسل الفني داخل التطبيق وتستفسر.</p>
            </details>
            <details class="reveal show">
                <summary>كيف تُحسب الأسعار؟</summary>
                <p>بناءً على الوصف والموقع والوقت، ويبان لك كل شيء قبل التأكيد.</p>
            </details>
            <details class="reveal show">
                <summary>في رسوم مخفية؟</summary>
                <p>أبد، السعر واضح والبنود موضحة.</p>
            </details>
            <details class="reveal show">
                <summary>لغات التطبيق؟</summary>
                <p>العربي جاهز، والإنجليزي قريب.</p>
            </details>
            <details class="reveal show">
                <summary>في ضمان على الأعمال؟</summary>
                <p>بعض الخدمات عليها ضمان ويظهر لك عند الطلب.</p>
            </details>
            <details class="reveal show">
                <summary>كيف أقدم شكوى؟</summary>
                <p>من قسم المساعدة ترفع طلب ويتابعك الفريق.</p>
            </details>
            <details class="reveal show">
                <summary>البيانات محفوظة؟</summary>
                <p>نستخدم تشفير ومعايير أمان حديثة لحماية بياناتك.</p>
            </details>
        </section>

        <!-- CTA Band -->
        <section class="container">
            <div class="cta-band reveal show">
                <div>
                    <span class="pill" style="background:rgba(147,206,240,.22); color:#fff">جاهز تبدأ؟</span>
                    <h3>حمّل المنقذ الشامل وخلك مرتاح — الخدمة تيجيك</h3>
                    <p style="opacity:.92">روابط رسمية وآمنة من المتاجر المعتمدة.</p>
                </div>
                <div class="actions">
                    <a class="store" target="_blank" rel="noopener" href="{{ $setting->ios_app_link }}"
                        aria-label="App Store"><img width="130"
                            src="{{ asset('landing-page/img/app.png') }}"></a>
                    <a class="store ghost" target="_blank" rel="noopener" href="{{ $setting->android_app_link }}"
                        aria-label="Google Play"><img width="130"
                            src="{{ asset('landing-page/img/google.png') }}"></a>
                </div>
            </div>
        </section>
    </main>

    <footer class="container">
        <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap; justify-content:space-between">
            <p style="margin:0">© <span id="year">2025</span> المنقذ الشامل. جميع الحقوق محفوظة.</p>
            <div class="cta">
                <a class="store" target="_blank" rel="noopener" href="{{ $setting->ios_app_link }}"
                    aria-label="App Store"><img width="130" src="{{ asset('landing-page/img/app.png') }}"></a>
                <a class="store ghost" target="_blank" rel="noopener" href="{{ $setting->android_app_link }}"
                    aria-label="Google Play"><img width="130"
                        src="{{ asset('landing-page/img/google.png') }}"></a>
            </div>
        </div>
    </footer>

    <!-- Floating download tray shown after scroll -->
    <div class="float show" id="floatBar" role="region" aria-label="شريط تحميل عائم"
        style="width: fit-content; margin:auto">
        <div class="tray">
            <a class="store" target="_blank" rel="noopener" href="{{ $setting->ios_app_link }}"
                aria-label="App Store"><img width="130" src="{{ asset('landing-page/img/app.png') }}"></a>
            <a class="store ghost" target="_blank" rel="noopener" href="{{ $setting->android_app_link }}"
                aria-label="Google Play"><img width="130" src="{{ asset('landing-page/img/google.png') }}"></a>
        </div>
    </div>

    <!-- Structured data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "MobileApplication",
            "name": "المنقذ الشامل",
            "operatingSystem": "iOS, Android",
            "applicationCategory": "ServiceApplication",
            "offers": {"@type":"Offer","price":"0","priceCurrency":"SAR"},
            "installUrl": [
            "{{ $setting->ios_app_link }}",
            "{{ $setting->android_app_link}}"
            ]
        }
    </script>

    <script>
        // سنة تلقائية في الفوتر
        document.getElementById('year').textContent = new Date().getFullYear();

        // Reveal on scroll
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('show');
                    io.unobserve(e.target)
                }
            })
        }, {
            threshold: .12
        });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));

        // Counters
        const counters = document.querySelectorAll('.counter .num');
        counters.forEach(el => {
            const target = parseFloat(el.getAttribute('data-target'));
            const decimals = parseInt(el.getAttribute('data-decimals') || 0, 10);
            let current = 0;
            const steps = 48;
            let i = 0;
            const inc = target / steps;
            const tick = () => {
                i++;
                current = Math.min(target, current + inc);
                el.textContent = current.toFixed(decimals);
                if (i < steps) requestAnimationFrame(tick);
            };
            const onShow = new IntersectionObserver((ents) => {
                ents.forEach(ent => {
                    if (ent.isIntersecting) {
                        tick();
                        onShow.unobserve(el)
                    }
                })
            }, {
                threshold: .4
            });
            onShow.observe(el);
        });

        // Floating bar after scroll
        const floatBar = document.getElementById('floatBar');
        window.addEventListener('scroll', () => {
            const y = window.scrollY || document.documentElement.scrollTop;
            if (y > 300) {
                floatBar.classList.remove('hide-on-top');
                floatBar.classList.add('show');
            } else {
                floatBar.classList.add('hide-on-top');
                floatBar.classList.remove('show');
            }
        });
    </script>
</body>

</html>
