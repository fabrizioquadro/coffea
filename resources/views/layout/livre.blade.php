<!DOCTYPE html>

<html lang="pt-BR" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('/public/template') }}/" data-template="horizontal-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>Supporto Trading Company - Sistema de Pedido de Compras</title>
        <meta name="description" content="Supporto Trading Company - Sistema de Pedido de Compras" />
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('/public/img/Supporto_Alta.png') }}" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/fonts/materialdesignicons.css') }}" />
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/fonts/flag-icons.css') }}" />
        <!-- Menu waves for no-customizer fix -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/node-waves/node-waves.css') }}" />
        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('/public/template/css/demo.css') }}" />
        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/typeahead-js/typeahead.css') }}" />
        <!-- Vendor -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/pages/page-auth.css') }}" />
        <!-- Helpers -->
        <script src="{{ asset('/public/template/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('/public/template/vendor/js/template-customizer.js') }}"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('/public/template/js/config.js') }}"></script>

        <style media="screen">
            .btn-primary{
                background-color: #41af64 !important;
                border-color: #41af64 !important;
            }

            .btn-primary:hover{
                background-color: #3d8353 !important;
                border-color: #3d8353 !important;
            }
        </style>
    </head>
    <body>
        <!-- Sections:Start -->
        <section class="section-py bg-body first-section-pt">
            <div class="container">
                @yield('conteudo')
            </div>
        </section>
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{ asset('/public/template/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/node-waves/node-waves.js') }}"></script>

        <script src="{{ asset('/public/template/vendor/js/front-main.js') }}"></script>
    </body>
</html>
