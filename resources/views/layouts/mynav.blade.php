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
                <!--<div class="search-wrap" id="search-wrap">-->
                <!--    <div class="search-bar">-->
                <!--        <input class="search-input" type="search" placeholder="Search" />-->
                <!--        <a href="#" class="close-search toggle-search" data-target="#search-wrap">-->
                <!--            <i class="mdi mdi-close-circle"></i>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div>-->

                <ul class="list-inline ml-auto mb-0">

                    <!-- notification-->

                    <!--<li class="list-inline-item dropdown notification-list">-->
                    <!--    <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">-->
                    <!--        <i class="mdi mdi-magnify noti-icon"></i>-->
                    <!--    </a>-->
                    <!--</li>-->

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-bell-outline noti-icon"></i>
                            <span class="badge badge-pill noti-icon-badge" id="notification-count">
                             {{(auth()->user()->unreadNotificationsCount() > 99) ? '99+' : auth()->user()->unreadNotificationsCount() }}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg dropdown-menu-animated">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5>Notifications</h5>
                            </div>

                            <div class="slimscroll-notification" style="overflow: auto; height:500px">
                                @foreach(auth()->user()->userNotifications() as $notification)
                                <!-- item-->
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#notificationModal" onclick="showModal('{{$notification->id}}',{{json_encode($notification->data['text'])}})" data-id="{{$notification->id}}">
                                        <p class="notify-details p-2 border-bottom" style="color:#ccc">
                                            <b>{{Str::words($notification->data['text'],15)}}</b>
                                            <span id="text-{{$notification->id}}" class="text-light {{($notification->read_at) ? '':'badge-success p-1 rounded '}}">
                                                {{($notification->read_at) ? '' : 'new'}}</span>
                                        </p>
                                    </a>
                                @endforeach

                                <!-- item-->
                                <!--<a href="javascript:void(0);" class="dropdown-item notify-item">-->
                                <!--    <div class="notify-icon bg-danger"><i class="mdi mdi-message-text-outline"></i></div>-->
                                <!--    <p class="notify-details"><b>New Message received</b><span class="text-muted">You have 87 unread messages</span></p>-->
                                <!--</a>-->

                                <!-- item-->
                                <!--<a href="javascript:void(0);" class="dropdown-item notify-item">-->
                                <!--    <div class="notify-icon bg-info"><i class="mdi mdi-filter-outline"></i></div>-->
                                <!--    <p class="notify-details"><b>Your item is shipped</b><span class="text-muted">It is a long established fact that a reader will</span></p>-->
                                <!--</a>-->

                                <!-- item-->
                                <!--<a href="javascript:void(0);" class="dropdown-item notify-item">-->
                                <!--    <div class="notify-icon bg-success"><i class="mdi mdi-message-text-outline"></i></div>-->
                                <!--    <p class="notify-details"><b>New Message received</b><span class="text-muted">You have 87 unread messages</span></p>-->
                                <!--</a>-->

                                <!-- item-->
                                <!--<a href="javascript:void(0);" class="dropdown-item notify-item">-->
                                <!--    <div class="notify-icon bg-warning"><i class="mdi mdi-cart-outline"></i></div>-->
                                <!--    <p class="notify-details"><b>Your order is placed</b><span class="text-muted">Dummy text of the printing and typesetting industry.</span></p>-->
                                <!--</a>-->

                            </div>


                            <!-- All-->
                            {{-- <a href="javascript:void(0);" class="dropdown-item notify-all">
                                View All
                            </a> --}}

                        </div>
                    </li>
                    <!-- User-->
                    <li class="list-inline-item dropdown notification-list nav-user">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src= "{{URL::to('/')}}/public/images/{{auth()->user()->image }}"alt="profile pics" class="rounded-circle">
                            <span class="d-none d-md-inline-block ml-1">{{Auth::user()->first_name}} <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                            <a class="dropdown-item" href="/user/{{Auth::user()->id}}"><i class="dripicons-user text-muted"></i> Profile</a>
                            <a class="dropdown-item" href="{{route('company.wallet')}}"><i class="dripicons-wallet text-muted"></i> My Wallet</a>
                            <a class="dropdown-item" href="{{ route('admin.payout.create') }}"><i class="dripicons-wallet text-muted"></i> Payout </a>
                            <a class="dropdown-item" href="{{ route('admin.settings.index') }}"><span class="badge badge-success float-right m-t-5">5</span><i class="dripicons-gear text-muted"></i> Settings</a>
                            <a class="dropdown-item" href="{{route('lock.screen')}}"><i class="dripicons-lock text-muted"></i> Lock screen</a>
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
                        <a href="/users"><i class="dripicons-user"></i> Customer</a>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="dripicons-hourglass"></i> History <i class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu">

                            <li>
                                <a href="{{route('user.transactions')}}">Customer Transactions</a>
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
                                    <li><a href="/report_audit_trail">Audit Trail</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <a href="{{route('report.deposit.index')}}">
                                            Customer Deposit Monitoring
                                        </a>
                                    </li>
                                    <li><a href="{{route('transaction.wallet')}}">Company Wallet Ledger</a></li>
                                    <li><a href="{{route('bank.tranfer.deposit')}}">Customer Bank Transfer</a></li>
                                    <li><a href="{{route('atm.deposit')}}">Customer ATM payment</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>

                                    
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
                                    <li><a href="{{route('transaction.reversal.show')}}">Reversal List</a></li>
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
                                    <li><a href="{{route('transaction.posting.list')}}">Posting List</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Transaction </a>
                                <ul class="submenu">
                                    <li><a href="/postairtimetransaction">Post Airtime Transaction</a></li>
                                    <li><a href="{{route('transaction.index')}}">Transaction List</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Recharge Card </a>
                                <ul class="submenu">
                                    <li><a href="/rechargecard">Post Recharge Card</a></li>
                                    <li><a href="{{route('rechargecard.list')}}">Recharge Card List</a></li>
                                </ul>
                            </li>


                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="{{route('email-templates.index')}}"><i class="dripicons-help"></i> Email View <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        {{-- <ul class="submenu">

                            <li>
                                <a href="/fundwalletmail" target="blank">Fund Wallet</a>
                            </li>
                            <li>
                                <a href="/newaccountmail" target="blank">Account Creation</a>
                            </li>
                            <li>
                                <a href="/newmessagemail" target="blank">Message</a>
                            </li>
                            <li>
                                <a href="/newtransactionmail" target="blank">Transaction</a>
                            </li>

                        </ul> --}}
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

@include('notifications')


<script>
    function showModal(id, body){
        $('#notification-body').text(body);
        $.get(`notification-read/${id}`, function(data){
            $('#notification-count').text(parseInt(data));
            $(`#text-${id}`).removeClass('badge-success').text('');
            console.log($('#text-new').text());
        });

    }
</script>


@section('after-styles')
#navigation {
    display: flex;
    justify-content: space-between; /* Distribute space evenly between items */
    align-items: center; /* Center items vertically */
    background-color: #333; /* Optional: Set background color */
    padding: 10px 20px; /* Optional: Add padding */
}

.navigation-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.navigation-menu li {
    margin-right: 20px; /* Add spacing between menu items */
}

/* Adjust menu item styles */
.navigation-menu a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    transition: color 0.3s;
}

.navigation-menu a:hover {
    color: #f0f0f0; /* Change color on hover */
}

/* Media query for smaller screens */
@media screen and (max-width: 768px) {
    #navigation {
        flex-direction: column; /* Stack items vertically on smaller screens */
    }

    .navigation-menu {
        flex-direction: column; /* Stack menu items vertically on smaller screens */
    }

    .navigation-menu li {
        margin-right: 0; /* Remove right margin for stacked items */
        margin-bottom: 10px; /* Add spacing between stacked menu items */
    }
}

@stop
