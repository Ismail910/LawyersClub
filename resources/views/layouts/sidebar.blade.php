<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                @php
                    $user = Auth::guard('admin')->user() ?? Auth::guard('accountant')->user() ?? Auth::guard('hr')->user();
                @endphp

                <!-- Dashboard -->
                <li>
                    <a href="/" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>

                <!-- Categories (Admin Only) -->
                @if($user && $user->user_type == 'admin')
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-category"></i>
                        <span>@lang('translation.Categories')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('categories.index') }}">@lang('translation.AllCategories')</a></li>
                        <li><a href="{{ route('categories.create') }}">@lang('translation.AddNewCategory')</a></li>
                    </ul>
                </li>
                @endif

                <!-- Budgets (Admin & Accountant) -->
                @if($user && in_array($user->user_type, ['admin', 'accountant']))
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-money"></i>
                        <span>@lang('translation.Budgets')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('budgets.index') }}">@lang('translation.AllBudgets')</a></li>
                        <li><a href="{{ route('budgets.create') }}">@lang('translation.AddNewBudget')</a></li>
                        <li><a href="{{ route('budget.statistics.view') }}">📊 @lang('translation.BudgetStatistics')</a></li> <!-- ✅ إحصائيات الايرادات -->
                    </ul>
                </li>

                <li>
                    <a href="{{ route('archived-budgets.index') }}" class="waves-effect">
                        <i class="bx bx-archive"></i>
                        <span>@lang('translation.ArchivedBudgets')</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span>@lang('translation.Invoices')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('invoices.index') }}">@lang('translation.AllInvoices')</a></li>
                        <li><a href="{{ route('invoices.create') }}">@lang('translation.AddNewInvoice')</a></li>
                        <li><a href="{{ route('invoice.statistics.view') }}">📊 @lang('translation.InvoiceStatistics')</a></li> <!-- ✅ إحصائيات صرف -->
                    </ul>
                </li>


                <li>
                    <a href="{{ route('archived-invoices.index') }}" class="waves-effect">
                        <i class="bx bx-archive"></i>
                        <span>@lang('translation.ArchivedInvoices')</span>
                    </a>
                </li>
                @endif

                <!-- HR (HR Only) -->
                @if($user && in_array($user->user_type, ['admin', 'hr','accountant']))
                <!-- Members Section -->
                <li>
                    <a href="javascript:void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user"></i>
                        <span>@lang('translation.Members')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('hr.members.index') }}">@lang('translation.AllMembers')</a></li>
                        <li><a href="{{ route('hr.members.create') }}">@lang('translation.AddNewMember')</a></li>
                        <li><a href="{{ route('sequences.member') }}">@lang('translation.sequences')</a></li>
                    </ul>
                </li>

             
            @endif


            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-user font-size-16 align-middle me-1"></i>
                    <span>@lang('translation.Profile')</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ route('profile.show') }}">@lang('translation.Profile')</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                    <span>@lang('translation.Logout')</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="javascript:void();"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            @lang('translation.Logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>





            </ul>
        </div>
    </div>
</div>
