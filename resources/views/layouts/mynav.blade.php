<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <!-- Logo-->
            <div>

                <a href="{{route('dashboard')}}" class="logo">
                    <img src="/assets/images/logo.png" alt="" height="26">
                </a>

            </div>
            <!-- End Logo-->

            <div class="menu-extras topbar-custom navbar p-0">

                <ul class="list-inline d-none d-lg-block mb-0">
                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                           role="button"
                           aria-haspopup="false" aria-expanded="false">
                            Create New <i class="mdi mdi-plus"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated">
                            <a class="dropdown-item" href="/addfaq">Faq</a>
                            <a class="dropdown-item" href="/addplan">Plan</a>
                            <a class="dropdown-item" href="/addpermission">Permission</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">SMS Pricing</a>
                        </div>
                    </li>
                    <li class="list-inline-item notification-list">
                        <a href="/faq" class="nav-link waves-effect">
                            Faq
                        </a>
                    </li>

                </ul>

                <!-- Search input -->
                <div class="search-wrap" id="search-wrap">
                    <div class="search-bar">
                        <input class="search-input" type="search" placeholder="Search" />
                        <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                            <i class="mdi mdi-close-circle"></i>
                        </a>
                    </div>
                </div>

                <ul class="list-inline ml-auto mb-0">

                    <!-- notification-->

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                            <i class="mdi mdi-magnify noti-icon"></i>
                        </a>
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-bell-outline noti-icon"></i>
                            <span class="badge badge-pill noti-icon-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg dropdown-menu-animated">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5>Notification (3)</h5>
                            </div>

                            <div class="slimscroll-noti">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details"><b>Your order is placed</b><span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-danger"><i class="mdi mdi-message-text-outline"></i></div>
                                    <p class="notify-details"><b>New Message received</b><span class="text-muted">You have 87 unread messages</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info"><i class="mdi mdi-filter-outline"></i></div>
                                    <p class="notify-details"><b>Your item is shipped</b><span class="text-muted">It is a long established fact that a reader will</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-message-text-outline"></i></div>
                                    <p class="notify-details"><b>New Message received</b><span class="text-muted">You have 87 unread messages</span></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning"><i class="mdi mdi-cart-outline"></i></div>
                                    <p class="notify-details"><b>Your order is placed</b><span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>
                                </a>

                            </div>


                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item notify-all">
                                View All
                            </a>

                        </div>
                    </li>
                    <!-- User-->
                    <li class="list-inline-item dropdown notification-list nav-user">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="assets/images/users/avatar-6.jpg" alt="user" class="rounded-circle">
                            <span class="d-none d-md-inline-block ml-1">{{Auth::user()->first_name}} <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                            <a class="dropdown-item" href="/user/{{Auth::user()->id}}"><i class="dripicons-user text-muted"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="dripicons-wallet text-muted"></i> My Wallet</a>
                            <a class="dropdown-item" href="#"><span class="badge badge-success float-right m-t-5">5</span><i class="dripicons-gear text-muted"></i> Settings</a>
                            <a class="dropdown-item" href="/password/confirm"><i class="dripicons-lock text-muted"></i> Lock screen</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i class="dripicons-exit text-muted"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                </ul>

            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <!-- MENU Start -->
    <div class="navbar-custom">
        <div class="container-fluid">

            <div id="navigation">

                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    <li class="has-submenu">
                        <a href="/dashboard"><i class="dripicons-home"></i> Dashboard</a>
                    </li>

                    <li class="has-submenu">
                        <a href="/services"><i class="dripicons-suitcase"></i> Services</a>
                    </li>

                    <li class="has-submenu">
                        <a href="/roles"><i class="dripicons-lifting"></i> Roles</a>
                    </li>

                    <li class="has-submenu">
                        <a href="/admin"><i class="dripicons-user-group"></i> Admin</a>
                    </li>

                    <li class="has-submenu">
                        <a href="/users"><i class="dripicons-user"></i> User</a>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-hourglass"></i> History <i class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu">

                            <li>
                                <a href="{{route('user.transactions')}}">User Transactions</a>
                            </li>
                            <li>
                                <a href="{{route('company.wallet')}}">Company Wallet History</a>
                            </li>

                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-suitcase"></i> Reports <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="/new_account">New Account</a></li>
                                    <li><a href="/account_ledger">Customer Account Ledger</a></li>
                                    <li><a href="/report_service_charge">Service Charge</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>

                                    <li><a href="/report_audit_trail">Audit Trail</a></li>
                                    <li><a href="/report_deposit">Customer Deposit Monitoring</a></li>
                                    <li><a href="ui-tabs-accordions.html">Company Wallet Ledger</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>

                                    <li><a href="ui-modals.html">Customer Bank Transfer</a></li>
                                    <li><a href="ui-images.html">Customer ATM payment</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-help"></i> Posting <i class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu">
                            <li class="has-submenu">
                                <a href="#">Reversal</a>
                                <ul class="submenu">
                                    <li><a href="/reversal">Post Reversal</a></li>
                                    <li><a href="icons-ion.html">Reversal List</a></li>
                                </ul>
                            </li>
                            @can('fund_wallet')
                                <li>
                                    <a href="/fundwallet">Fund Wallet</a>
                                </li>
                            @endcan
                            <li class="has-submenu">
                                <a href="#">Charge Customer </a>
                                <ul class="submenu">
                                    <li><a href="/chargecustomer">Direct Posting</a></li>
                                    <li><a href="charts-chartist.html">Posting List</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Transaction </a>
                                <ul class="submenu">
                                    <li><a href="/postairtimetransaction">Post Airtime Transaction</a></li>
                                    <li><a href="charts-chartist.html">Transaction List</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Recharge Card </a>
                                <ul class="submenu">
                                    <li><a href="/rechargecard">Post Recharge Card</a></li>
                                    <li><a href="charts-chartist.html">Recharge Card List</a></li>
                                </ul>
                            </li>


                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-help"></i> Email View <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu">

                            <li>
                                <a href="/fundwalletmail">Fund Wallet</a>
                            </li>
                            <li>
                                <a href="/newaccountmail">Account Creation</a>
                            </li>
                            <li>
                                <a href="/newmessagemail">Message</a>
                            </li>
                            <li>
                                <a href="/newtransactionmail">Transaction</a>
                            </li>

                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-basket"></i> Subscription <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu">
                            <li><a href="/billing">Make subscription</a></li>
                            <li><a href="/subscriptions">Subscriptions</a></li>
                            <li><a href="/smspayment">Make SMS payment</a></li>
                            <li><a href="/smspayments">SMS payments</a></li>
                            <li><a href="/smstransactions">SMS Transactions</a></li>
                        </ul>
                    </li>

                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
</header>
