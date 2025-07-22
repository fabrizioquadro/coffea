<!DOCTYPE html>
@php
$user = auth()->user();
if($user->imagem){
    $avatar = "<img src='/public/img/users/".$user->imagem."?".date("ymdhis")."' alt class='w-px-40 h-auto rounded-circle' />";
}
else{
    $avatar = "<span class='avatar-initial rounded-circle bg-label-secondary'>".substr($user->nome,0,2)."</span>";
}
$alertas = App\Models\Alerta::where('user_id', $user->id)->where('visualizacao','Não')->get();
@endphp
<html lang="pt-BR" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('/public/template') }}/" data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>Supporto Trading Company - Sistema de Pedido de Compras</title>
        <meta name="description" content="Supporto Trading Company - Sistema de Pedido de Compras" />
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('/public/img/logo_supporto.png') }}" />

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
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/apex-charts/apex-charts.css') }}" />
        <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/swiper/swiper.css') }}" />

        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
        <link rel="stylesheet" href="{{ asset('/public/css/bootstrap-combobox.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('/public/template/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('/public/template/vendor/js/template-customizer.js') }}"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('/public/template/js/config.js') }}"></script>

    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="{{ route('dashboard') }}" class="app-brand-link">
                            <img src="{{ asset('/public/img/logo_supporto.png') }}" style='height: 50px'>
                        </a>
                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z" fill="currentColor" fill-opacity="0.6" />
                                <path d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z" fill="currentColor" fill-opacity="0.38" />
                            </svg>
                        </a>
                    </div>
                    <div class="menu-inner-shadow"></div>
                    <ul class="menu-inner py-1">
                        <li class="menu-header fw-medium mt-4">
                            <span class="menu-header-text">Administrativo</span>
                        </li>
                        <!-- Dashboards -->
                        <li class="menu-item">
                            <a href="{{ route('dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>
                        @if($user->perfil->administrador)
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <i class="menu-icon tf-icons mdi mdi-folder-plus-outline"></i>
                                    <div data-i18n="Cadastros">Cadastros</div>
                                </a>
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('unidades') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-home-city"></i>
                                            <div data-i18n="Unidades">Unidades</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('setores') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-sitemap"></i>
                                            <div data-i18n="Setores">Setores</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('perfis') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-format-list-bulleted-type"></i>
                                            <div data-i18n="Perfis">Perfis</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('usuarios') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-account-group"></i>
                                            <div data-i18n="Usuários">Usuários</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('operacoes') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-folder-open-outline"></i>
                                            <div data-i18n="Operações">Operações</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('fornecedores') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-bank"></i>
                                            <div data-i18n="Fornecedores">Fornecedores</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('grupos') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-sitemap"></i>
                                            <div data-i18n="Grupos Itens">Grupos Itens</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('itens') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-clipboard-list-outline"></i>
                                            <div data-i18n="Itens">Itens</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('contas') }}" class="menu-link">
                                            <i class="menu-icon tf-icons mdi mdi-cash"></i>
                                            <div data-i18n="Contas">Contas</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if($user->perfil->administrador || $user->perfil->criar || $user->perfil->preparar_compra)
                            <li class="menu-item">
                                <a href="{{ route('pedidos') }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-receipt-text-edit-outline"></i>
                                    <div data-i18n="Pedidos">Pedidos</div>
                                </a>
                            </li>
                        @endif
                        @if($user->perfil->administrador || $user->perfil->moderar ||$user->perfil->aprovar || $user->perfil->preparar_compra)
                            <li class="menu-item">
                                <a href="{{ route('requisicoes') }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-receipt-text-check"></i>
                                    <div data-i18n="Requisições">Requisições</div>
                                </a>
                            </li>
                        @endif
                        @if($user->perfil->administrador || $user->perfil->moderar || $user->perfil->confirmar_recebimento)
                            <li class="menu-item">
                                <a href="{{ route('compras') }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-receipt-text-send-outline"></i>
                                    <div data-i18n="Compras">Compras</div>
                                </a>
                            </li>
                        @endif
                        @if($user->perfil->administrador || $user->perfil->moderar)
                            <li class="menu-item">
                                <a href="{{ route('finalizados') }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-check-bold"></i>
                                    <div data-i18n="Finalizados">Finalizados</div>
                                </a>
                            </li>
                        @endif
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons mdi mdi-link-variant"></i>
                                <div data-i18n="Outros">Outros</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a target="_blank" href="https://sistema.sisagil.com" class="menu-link">
                                        <div data-i18n="Sisagil">Sisagil</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a target="_blank" href="https://coffea.wobrasil.com.br" class="menu-link">
                                        <div data-i18n="Estoque">Estoque</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </aside>
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="mdi mdi-menu mdi-24px"></i>
                            </a>
                        </div>
                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- Style Switcher -->
                                <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
                                    <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-24px"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                                <span class="align-middle"><i class="mdi mdi-weather-sunny me-2"></i>Light</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                                <span class="align-middle"><i class="mdi mdi-weather-night me-2"></i>Dark</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                                <span class="align-middle"><i class="mdi mdi-monitor me-2"></i>System</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- / Style Switcher-->

                                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                                    <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="mdi mdi-bell-outline mdi-24px"></i>
                                        @if($alertas->count() > 0)
                                            <span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end py-0">
                                        <li class="dropdown-menu-header border-bottom">
                                            <div class="dropdown-header d-flex align-items-center py-3">
                                                <h6 class="mb-0 me-auto">Alertas</h6>
                                                <span class="badge rounded-pill bg-label-primary">{{ $alertas->count() }} novos alertas</span>
                                            </div>
                                        </li>
                                        <li class="dropdown-notifications-list scrollable-container">
                                            <ul class="list-group list-group-flush">
                                                @foreach($alertas as $alerta)
                                                @php
                                                if($alerta->origem == "pedidos"){
                                                    $link = route('pedidos.acessar', $alerta->requisicao_id);
                                                }
                                                elseif($alerta->origem == "requisicao"){
                                                    $link = route('requisicoes.acessar', $alerta->requisicao_id);
                                                }
                                                elseif($alerta->origem == "compra"){
                                                    $link = route('compras.acessar', $alerta->requisicao_id);
                                                }
                                                @endphp
                                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <a href="{{ $link }}">
                                                        <div class="d-flex gap-2">
                                                            <div class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                                <h6 class="mb-1 text-truncate">Pedido codigo {{ $alerta->requisicao_id }}</h6>
                                                                <small class="text-truncate text-body">{{ $alerta->mensagem }}</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            {!! $avatar !!}
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        {!! $avatar !!}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">{{ $user->nome }}</span>
                                                    <small class="text-muted">{{ $user->perfil->descricao }}</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('perfil') }}">
                                                <i class="mdi mdi-account-outline me-2"></i>
                                                <span class="align-middle">Perfil</span>
                                              </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('alterar_senha') }}">
                                                <i class="mdi mdi-lock-reset me-2"></i>
                                                <span class="align-middle">Alterar Senha</span>
                                              </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}">
                                                <i class="mdi mdi-logout me-2"></i>
                                                <span class="align-middle">Sair</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>
                    </nav>
                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('conteudo')
                        </div>
                        <!-- / Content -->

                        <!-- Footer -->
                        <footer class="content-footer footer bg-footer-theme">
                            <div class="container-xxl">
                                <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                                    <div class="mb-2 mb-md-0">
                                        ©{{ date('Y') }}, Supporto Trading Company - Sistema de Pedido de Compras
                                    </div>
                                </div>
                            </div>
                        </footer>
                        <!-- / Footer -->
                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="{{ asset('/public/template/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/i18n/i18n.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/typeahead-js/typeahead.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/js/menu.js') }}"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="{{ asset('/public/template/vendor/libs/apex-charts/apexcharts.js') }}"></script>
        <script src="{{ asset('/public/template/vendor/libs/swiper/swiper.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('/public/template/js/main.js') }}"></script>
        <script src="{{ asset('/public/js/script.js') }}"></script>
        <script src="{{ asset('/public/js/bootstrap-combobox.js') }}"></script>
        <script>
            testaDark = window.Helpers.isDarkStyle();
            console.log(testaDark);
        </script>
    </body>
</html>
