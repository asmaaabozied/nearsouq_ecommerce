<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset(auth()->user()->getImagePathAttribute()) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>@lang('site.title')</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>


        <ul class="sidebar-menu" data-widget="tree">

            <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>@lang('site.management') @lang('site.persons')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:none">
                    @if (auth()->user()->hasPermission('read_roles'))
                        <li><a href="{{ route('dashboard.roles.index') }}"><i class="fa fa-sliders"></i><span>@lang('site.roles')</span></a></li>
                    @endif
                    @if (auth()->user()->hasPermission('read_admins'))
                        <li><a href="{{ route('dashboard.users.index') }}"><i class="fa fa-cogs"></i><span>@lang('site.admins')</span></a></li>
                    @endif
{{--                    @if (auth()->user()->hasPermission('read_users'))--}}
{{--                        <li><a href="{{ route('dashboard.manage_users.index') }}"><i class="fa fa-users"></i><span>@lang('site.users')</span></a></li>--}}
{{--                    @endif--}}
                </ul>
            </li>

{{--            @if (auth()->user()->hasPermission('read_news'))--}}
{{--                <li><a href="{{ route('dashboard.news_categories.index') }}"><i class="fa fa-newspaper-o"></i><span>@lang('site.news')</span></a></li>--}}
{{--            @endif--}}
{{--            @if (auth()->user()->hasPermission('read_consultations'))--}}
{{--                <li><a href="{{ route('dashboard.consultations.index') }}"><i class="fa fa-comment-o"></i><span>@lang('site.consultations')</span></a></li>--}}
{{--            @endif--}}

        @if (auth()->user()->hasPermission('read_tags'))
                <li><a href="{{ route('dashboard.tags.index') }}"><i class="fa fa-th"></i><span>@lang('site.tag')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read_geographies'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-globe"></i>
                        <span>@lang('site.geography')</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
{{--                    <ul class="treeview-menu" style="display:none">--}}
{{--                        <li><a href="{{ route('dashboard.countries.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.countries')</span></a></li>--}}
{{--                        <li><a href="{{ route('dashboard.cities.index') }}"><i class="fa fa-building-o"></i><span>@lang('site.cities')</span></a></li>--}}
{{--                    </ul>--}}
                </li>
            @endif

{{--            <li><a href="{{ route('dashboard.pages.index') }}"><i class="fa fa-newspaper-o"></i><span>@lang('site.pages')</span></a></li>--}}
{{--            <li><a href="{{ route('dashboard.massages.index') }}"><i class="fa fa-envelope"></i><span>@lang('site.massages')</span></a></li>--}}
{{--            <li><a href="{{ route('dashboard.notification.index') }}"><i class="fa fa-envelope"></i><span>@lang('site.notification')</span></a></li>--}}
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                {{--<i class="fa fa-pie-chart"></i>--}}
                {{--<span>الخرائط</span>--}}
                {{--<span class="pull-right-container">--}}
                {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                {{--<li>--}}
                {{--<a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            @if (auth()->user()->hasPermission('read_catogery'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-globe"></i>
                        <span>@lang('site.geography')</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                                        <ul class="treeview-menu" style="display:none">
                                            <li><a href="{{ route('dashboard.catogery.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.countries')</span></a></li>
                                        </ul>
                </li>
            @endif
            {{--      @if (auth()->user()->hasRole('super_admin'))

                                 <li><a href="{{ route('dashboard.contact') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.contact')

                         <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                            @php  $cout=\Modules\Contact\Entities\Contact::where('read_at',0)->count()

                          @endphp
                                             {{$cout}}

                     </span>

         </span></a></li>

                 @endif --}}


            {{--         @if (auth()->user()->hasRole('super_admin'))

                        <li><a href="{{ route('dashboard.setting') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.setting')

                            <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                        </span>

            </span></a></li>

                    @endif --}}


        {{--            @if (auth()->user()->hasRole('super_admin'))--}}

{{--                <li><a href="{{ route('dashboard.categories') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.catogery')--}}

{{--                    <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">--}}

{{--                </span>--}}

{{--    </span></a></li>--}}

{{--            @endif--}}


        </ul>

    </section>

</aside>

