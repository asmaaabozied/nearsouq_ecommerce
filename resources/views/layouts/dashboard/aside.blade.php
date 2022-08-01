<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{route('dashboard.welcome')}}"><i data-feather="home"></i>
                        <span>@lang('site.dashboard')</span></a>
                </li>

  @if (auth()->user())

                @if (auth()->user()->hasPermission('read_users'))
                <li>
                    <a href="{{route('dashboard.users.index')}}"><i data-feather="users"></i>
                        <span>@lang('site.users')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_roles'))
                <li>
                    <a href="{{route('dashboard.roles.index')}}"><i data-feather="package"></i>
                        <span>@lang('site.roles')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_shops'))
                <li>

                    <a href="{{route('dashboard.shops.index')}}"><i data-feather="file-text"></i>
                        <span>@lang('site.shop')</span></a>
                </li>
                    <li>
                    <a href="{{route('dashboard.brances')}}"><i data-feather="grid"></i>
                        <span>@lang('site.brances')</span></a>
                </li>

                @endif
                   @if (auth()->user()->hasPermission('read_options'))

                <li>
                    <a href="{{route('dashboard.options.index')}}"><i data-feather="layout"></i>
                        <span>@lang('site.options')</span></a>
                </li>
                  @endif
                   @if (auth()->user()->hasPermission('read_products'))
                <li>
                    <a href="{{route('dashboard.products.index')}}"><i data-feather="layers"></i>
                        <span>@lang('site.products')</span></a>
                </li>
                    <li>
                    <a href="{{route('dashboard.files_product')}}"><i data-feather="file-text"></i>
                        <span>@lang('site.files')</span></a>
                </li>

                 @endif



                @if (auth()->user()->hasPermission('read_pages'))
                <li>
                    <a href="{{route('dashboard.pages.index')}}"><i data-feather="credit-card"></i>
                        <span>@lang('site.pages')</span></a>
                </li>


                 @endif
                @if (auth()->user()->hasPermission('read_transactions'))
                <li>
                    <a href="{{route('dashboard.Transaction.index')}}"><i data-feather="award"></i>
                        <span>@lang('site.Transaction')</span></a>
                </li>


                 @endif
                @if (auth()->user()->hasPermission('read_orders'))

                <li class="submenu">
                        <a href="#"><i data-feather="columns"></i> <span> @lang('site.orders')</span> <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{route('dashboard.order.index','DELIVERED')}}"><i class="fas fa-shipping-fast"></i>   @lang('site.delivered')</a></li>
                            <li><a href="{{route('dashboard.order.index','NOT_DELIVERED')}}"><i class="fas fa-exclamation"></i>  @lang('site.not_delivered')</a></li>
                            <li><a href="{{route('dashboard.order.index','RETURNED')}}"><i class="fas fa-redo-alt"></i>  @lang('site.returned')</a></li>
                            <li><a href="{{route('dashboard.order.index','CANCELED')}}"><i class="far fa-window-close"></i>  @lang('site.cancelled')</a></li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->hasPermission('read_malls'))
                <li>
                    <a href="{{route('dashboard.malls.index')}}"><i data-feather="clipboard"></i>
                        <span>@lang('site.malls')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_categories'))
                <li>
                    <a href="{{route('dashboard.categories.index')}}"><i data-feather="grid"></i>
                        <span>@lang('site.categories')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_banners'))
                <li>
                    <a href="{{route('dashboard.banners.index')}}"><i data-feather="alert-octagon"></i>
                        <span>@lang('site.banners')</span></a>
                </li>
                @endif
                
                
                
                      @if (auth()->user()->hasPermission('read_reasons'))
                <li>
                    <a href="{{route('dashboard.reasons.index')}}"><i data-feather="alert-octagon"></i>
                        <span>@lang('site.reasons')</span></a>
                </li>
                @endif
                
                
                @if (auth()->user()->hasPermission('create_notifications'))
                <li>
                    <a href="{{route('dashboard.notifications.create')}}"><i data-feather="map-pin"></i>
                        <span>@lang('site.notifications')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_settings'))
                <li>
                    <a href="{{route('dashboard.settings.index')}}"><i data-feather="settings"></i>
                        <span>@lang('site.settings')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_wallets'))
                <li>
                    <a href="{{route('dashboard.wallets.index')}}"><i data-feather="file-text"></i>
                        <span>@lang('site.wallets')</span></a>
                </li>
                @endif


                    @if (auth()->user()->hasPermission('read_versions'))
                <li>
                    <a href="{{route('dashboard.versions.index')}}"><i data-feather="columns"></i>
                        <span>@lang('site.versions')</span></a>
                </li>
                @endif
                @if (auth()->user()->hasPermission('read_userReports'))
                <li>
                    <a href="{{route('dashboard.userReports.index')}}"><i data-feather="pie-chart"></i>
                        <span>@lang('site.userReports')</span></a>
                </li>
                @endif


                     @if (auth()->user()->hasPermission('read_settings'))
                        <li>
                            <a href="{{route('dashboard.shop_settings.edit',1)}}"><i data-feather="pie-chart"></i>
                                <span>@lang('site.shop_settings')</span></a>
                        </li>
                    @endif
                @if (auth()->user()->hasPermission('read_users'))
                <li>
                    <a href="{{route('dashboard.deliveries.index')}}"><i data-feather="users"></i>
                        <span>@lang('site.deliveries')</span></a>
                </li>
                @endif
                
                    @if (auth()->user()->hasPermission('read_users'))
                <li>
                    <a href="{{route('dashboard.deliverycost.index')}}"><i data-feather="map-pin"></i>
                        <span>@lang('site.deliverycost')</span></a>
                </li>
                @endif


{{--                    @if (auth()->user()->hasPermission('read_users'))--}}
{{--                        <li>--}}
{{--                            <a href="{{route('dashboard.usersReports')}}"><i data-feather="pie-chart"></i>--}}
{{--                                <span>@lang('site.userReports')</span></a>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                    @if (auth()->user()->hasPermission('read_shops'))--}}
{{--                        <li>--}}
{{--                            <a href=""><i data-feather="pie-chart"></i>--}}
{{--                                <span>@lang('site.ShopsReports')</span></a>--}}
{{--                        </li>--}}
{{--                    @endif--}}

                            @if (auth()->user()->hasPermission('read_shops'))

                    <li class="submenu">
                        <a href="#"><i data-feather="pie-chart"></i> <span> @lang('site.reports')</span> <span class="menu-arrow"></span></a>
                        <ul>
                            @if (auth()->user()->hasPermission('read_shops'))
                            <li><a href="{{route('dashboard.ShopsReports')}}"><i data-feather="bar-chart-2"></i>   @lang('site.ShopsReports')</a></li>
                            @endif
                                @if (auth()->user()->hasPermission('read_users'))
                            <li><a href="{{route('dashboard.usersReports')}}"><i data-feather="bar-chart-2"></i>  @lang('site.userReports')</a></li>
                                @endif

                                @if (auth()->user()->hasPermission('read_products'))
                            <li><a href="{{route('dashboard.ProductsReports')}}"><i data-feather="bar-chart-2"></i>  @lang('site.ProductsReports')</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('read_orders'))
                            <li><a href="{{route('dashboard.OrdersReports')}}"><i data-feather="bar-chart-2"></i>  @lang('site.OrdersReports')</a></li>
                                @endif
                        </ul>
                    </li>
                       @endif
   @endif

            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
