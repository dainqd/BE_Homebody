<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Start Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.home') ? 'active' : 'collapsed' }}"
               href="{{ route('admin.home') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <!-- Start Category Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.categories.*') ? '' : 'collapsed' }}"
               data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav"
                class="nav-content collapse {{ Request::routeIs('admin.categories.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.categories.list') || Request::routeIs('admin.categories.detail') ? 'active' : '' }}"
                       href="{{ route('admin.categories.list') }}">
                        <i class="bi bi-circle"></i><span>List Category</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.categories.create') ? 'active' : '' }}"
                       href="{{ route('admin.categories.create') }}">
                        <i class="bi bi-circle"></i><span>Create Category</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Category Nav -->

        <!-- Start Products Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.partner.register.*') ? '' : 'collapsed' }}"
               data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Partner Register</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav"
                class="nav-content collapse {{ Request::routeIs('admin.partner.register.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.partner.register.list') || Request::routeIs('admin.partner.register.detail') ? 'active' : '' }}"
                       href="{{ route('admin.partner.register.list') }}">
                        <i class="bi bi-circle"></i><span>List Partner Register</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Products Nav -->

        <!-- Start Orders Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.orders.*') ? '' : 'collapsed' }}" data-bs-target="#tables-nav"
               data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Order</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse {{ Request::routeIs('admin.orders.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.orders.list') ? 'active' : '' }}" href="">
                        <i class="bi bi-circle"></i><span>List Order</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Orders Nav -->

        <!-- Start Coupons Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.coupons.*') ? '' : 'collapsed' }}"
               data-bs-target="#coupons-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-receipt"></i><span>Coupons</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="coupons-nav" class="nav-content collapse {{ Request::routeIs('admin.coupons.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.coupons.list') || Request::routeIs('admin.coupons.detail') ? 'active' : '' }}"
                       href="{{ route('admin.coupons.list') }}">
                        <i class="bi bi-circle"></i><span>List Coupons</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.coupons.create') ? 'active' : '' }}"
                       href="{{ route('admin.coupons.create') }}">
                        <i class="bi bi-circle"></i><span>Create Coupon</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Coupons Nav -->

        <!-- Start Contacts Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.contacts.*') ? '' : 'collapsed' }}"
               data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Contact</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse {{ Request::routeIs('admin.contacts.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.contacts.list') || Request::routeIs('admin.contacts.detail') ? 'active' : '' }}"
                       href="{{ route('admin.contacts.list') }}">
                        <i class="bi bi-circle"></i><span>List Contact</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Contacts Nav -->

        <!-- Start User Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.users.*') ? '' : 'collapsed' }}" data-bs-target="#icons-nav"
               data-bs-toggle="collapse" href="#">
                <i class="bi bi-person"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse {{ Request::routeIs('admin.users.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.users.list') || Request::routeIs('admin.users.detail') ? 'active' : '' }}"
                       href="{{ route('admin.users.list') }}">
                        <i class="bi bi-circle"></i><span>List User</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.users.create') ? 'active' : '' }}"
                       href="{{ route('admin.users.create') }}">
                        <i class="bi bi-circle"></i><span>Create User</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End User Nav -->

        <!-- Start Revenue Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.revenues.*') ? 'active' : 'collapsed' }}" href="">
                <i class="bi bi-currency-dollar"></i>
                <span>Revenue</span>
            </a>
        </li>
        <!-- End Revenue Nav -->

        <!-- Start Setting Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.app.*') ? '' : 'collapsed' }}"
               data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="setting-nav"
                class="nav-content collapse {{ Request::routeIs('admin.app.*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ Request::routeIs('admin.app.setting') ? 'active' : '' }}"
                       href="{{ route('admin.app.setting') }}">
                        <i class="bi bi-circle"></i><span>App setting</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Request::routeIs('admin.app.term.and.policies.*') ? 'active' : '' }}"
                       href="{{ route('admin.app.term.and.policies.list') }}">
                        <i class="bi bi-circle"></i><span>Term and policies</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Setting Nav -->

        <li class="nav-heading">Pages</li>

        <!--Start Profile Page Nav -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.profile.*') ? 'active' : 'collapsed' }}" href="">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>
        <!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.qna.*') ? 'active' : 'collapsed' }}" href="#">
                <i class="bi bi-question-circle"></i>
                <span>Q&A</span>
            </a>
        </li>
        <!-- End F.A.Q Page Nav -->

    </ul>

</aside>
