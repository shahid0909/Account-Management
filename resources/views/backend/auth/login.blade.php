<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Log In </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Myra Studio" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('backend/images/favicon.ico')}}">

    <link href="{{asset('backend/css/style.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{asset('backend/js/config.js')}}"></script>
</head>

<body class=" d-flex justify-content-center align-items-center min-vh-100 p-5" style="background-color:  #0c4a6e">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-md-5">
            <div class="card">
                <div class="card-body p-4">

                    <div class="text-center w-75 mx-auto auth-logo mb-4">
                        <a href="index.html" class="logo-dark">
{{--                            <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>--}}
                            <span class="fw-bold font-size-24">Account Management</span>
                        </a>

                        <a href="index.html" class="logo-light">
                            <span>Account Management</span>
                            <span><img src="assets/images/logo-light.png" alt="" height="22"></span>
                        </a>
                    </div>

                    <form action="#">

                        <div class="form-group mb-3">
                            <label class="form-label" for="emailaddress">Email address</label>
                            <input class="form-control" type="email" name="email" id="email" required="" placeholder="Enter your email">
                        </div>

                        <div class="form-group mb-3 position-relative">
                            <label class="form-label" for="password">Password</label>

                            <input class="form-control pe-5"
                                   type="password"
                                   id="password"
                                   name="password"
                                   placeholder="Enter your password"
                                   required>

                            <i class="mdi mdi-eye-outline position-absolute"
                               id="togglePassword"
                               style="top: 38px; right: 15px; cursor: pointer;"></i>
                        </div>

                        <div class="form-group mb-3">
                            <div class="">
                                <input class="form-check-input" type="checkbox" id="checkbox-signin" checked>
                                <label class="form-check-label ms-2" for="checkbox-signin">Remember me</label>
                            </div>
                        </div>



                    </form>
                    <div class="form-group mb-0 text-center">
                        <button class="btn btn-primary w-100" type="submit" id="submit-btn"> Log In </button>
                    </div>
                </div> <!-- end card-body -->
            </div>
            <!-- end card -->

{{--            <div class="row mt-3">--}}
{{--                <div class="col-12 text-center">--}}
{{--                    <p class="text-white-50"> <a href="pages-register.html" class="text-white-50 ms-1">Forgot your password?</a></p>--}}
{{--                    <p class="text-white-50">Don't have an account? <a href="pages-register.html" class="text-white font-weight-medium ms-1">Sign Up</a></p>--}}
{{--                </div> <!-- end col -->--}}
{{--            </div>--}}
            <!-- end row -->

        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>

<!-- App js -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/js/vendor.min.js')}}"></script>
<script src="{{asset('backend/js/app.js')}}"></script>


<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        this.classList.toggle('mdi-eye-outline');
        this.classList.toggle('mdi-eye-off-outline');
    });


    $('#submit-btn').on('click', function () {
        let email = $('#email').val();
        let password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: '/login-post',  // Adjust the URL as needed for your backend
            data: {
                email: email,
                password: password,
                _token: '{{csrf_token()}}'
            },
            success: function (response) {
                console.log(response)
                if (response.status == 'error') {

                    swal.fire({

                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                        showConfirmButton: true,

                    })

                } else {
                    window.location.href = '{{ route('dashboard') }}';
                }

                {{--                    --}}
            },

            error: function (xhr, status, error) {
                swal.fire({

                    text: xhr.responseJSON.message,
                    icon: "error",
                    showCancelButton: false,
                    showConfirmButton: true,

                })


            }
        });

    });

</script>
</body>

</html>
