<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
<!-- JQuery min js -->
<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap Bundle js -->
<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/ionicons/ionicons.js') }}"></script>
<!-- Moment js -->
<script src="{{ URL::asset('assets/plugins/moment/moment.js') }}"></script>

<!-- Rating js-->
<script src="{{ URL::asset('assets/plugins/rating/jquery.rating-stars.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/rating/jquery.barrating.js') }}"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/p-scroll.js') }}"></script>
<!--Internal Sparkline js -->
<script src="{{ URL::asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<!-- Custom Scroll bar Js-->
<script src="{{ URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<!-- right-sidebar js -->
<script src="{{ URL::asset('assets/plugins/sidebar/sidebar-rtl.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/sidebar/sidebar-custom.js') }}"></script>
<!-- Eva-icons js -->
<script src="{{ URL::asset('assets/js/eva-icons.min.js') }}"></script>
@yield('js')
<!-- Sticky js -->
<script src="{{ URL::asset('assets/js/sticky.js') }}"></script>
<!-- custom js -->
<script src="{{ URL::asset('assets/js/custom.js') }}"></script><!-- Left-menu js-->
<script src="{{ URL::asset('assets/plugins/side-menu/sidemenu.js') }}"></script>
<!-- sidebar session js -->
<script>
    function sidebar_session(setter = false, value = 0) {
        if (setter == true) {
            sessionStorage.setItem("sidebar", value);
        } else {
            status = sessionStorage.getItem("sidebar");
            if (status == 1) {
                document.body.classList.add("sidenav-toggled");
            }
        }

        let canvases = document.getElementsByClassName('chart-line');

        for (let i = 0; i < canvases.length; i++) {
            if (i % 2 == 1) {
                canvases[i].style.width = "auto";
                canvases[i].style.margin = "auto";
            } else {
                canvases[i].style.width = "100%";
            }
        }
    }

    window.onload = sidebar_session;
</script>
@auth
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if (isset($super_admin) || isset($order_control))
        <!-- Pusher -->
        <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
        <script>
            let userHasInteracted = false;
            let pendingNotifications = [];

            // Detect any user interaction
            document.addEventListener('click', function() {
                if (!userHasInteracted) {
                    userHasInteracted = true;

                    // Play any pending notifications
                    pendingNotifications.forEach(notification => {
                        playNotificationSound();
                        toastr.warning(notification.message, notification.title);
                    });
                    pendingNotifications = [];
                }
            });

            function playNotificationSound() {
                if (!userHasInteracted) {
                    return false;
                }

                const audio = document.getElementById('notif-sound');
                if (audio) {
                    audio.currentTime = 0;

                    const playPromise = audio.play();
                    return true;
                }
                return false;
            }

            function initializePusher() {
                var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                    cluster: "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}",
                    enabledTransports: ['ws', 'wss'],
                    disabledTransports: ['xhr_streaming', 'xhr_polling']
                });

                pusher.connection.bind('disconnected', function() {
                    setTimeout(function() {
                        pusher.connect();
                    }, 500);
                });

                var channel = pusher.subscribe('dashboard');

                channel.bind('new-notification', function(data) {
                    @if (in_array(auth()->user()->role_id, ['6', '7']))
                        if (data.role == '{{ auth()->user()->role_id }}' && data.authenticated.includes(
                                {{ auth()->user()->id }})) {
                            if (userHasInteracted) {
                                playNotificationSound();
                                toastr.warning(data.message, data.title);
                            } else {
                                pendingNotifications.push(data);
                                toastr.warning('{{ __('admin.click_to_view') }}', '');
                            }
                        }
                    @else
                        if (data.role === null) {
                            if (userHasInteracted) {
                                playNotificationSound();
                                toastr.warning(data.message, data.title);
                            } else {
                                pendingNotifications.push(data);
                                toastr.warning('{{ __('admin.click_to_view') }}', '');
                            }
                        }
                    @endif
                });

                return pusher;
            }

            var pusher = initializePusher();
        </script>
    @endif
@endauth
