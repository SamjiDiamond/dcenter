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
                            <span class="badge badge-pill noti-icon-badge {{ auth()->user()->unreadNotificationsCount() > 0 ? '' : 'd-none' }}" id="notification-count">
                             {{(auth()->user()->unreadNotificationsCount() > 99) ? '99+' : auth()->user()->unreadNotificationsCount() }}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg dropdown-menu-animated">
                            <!-- item-->
                            <div class="dropdown-item noti-title d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Notifications</h5>
                                <button type="button" id="mark-all-read" data-token="{{ csrf_token() }}" class="btn btn-link btn-sm p-0 text-primary" title="Mark all as read">
                                    Mark all as read
                                </button>
                            </div>

                            <div class="slimscroll-notification" id="notification-feed" style="overflow-y: auto; overflow-x: hidden; height: 320px; scrollbar-width: thin;">
                                @include('partials.notifications-feed')

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
                            <a href="{{ route('notification.index') }}" class="dropdown-item notify-all">
                                View All <i class="mdi mdi-chevron-right float-right"></i>
                            </a>

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
                            <a class="dropdown-item" href="{{ route('account.settings.index') }}"><i class="dripicons-user text-muted"></i> Account Settings</a>
                            <a class="dropdown-item" href="{{ route('team.index') }}"><i class="dripicons-user-group text-muted"></i> Team members</a>
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
                                    <li><a href="{{ route('audit.trail.index') }}">Audit Trail</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <a href="{{route('report.deposit.index')}}" style="font-size:12px;">
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
    function refreshNotificationBadge(count){
        count = parseInt(count) || 0;
        $('#notification-count').text(count > 99 ? '99+' : count);
        if (count <= 0) {
            $('#notification-count').addClass('d-none');
        } else {
            $('#notification-count').removeClass('d-none');
        }
    }

    function showModal(id, body){
        $('#notification-body').text(body);
        $.get(`notification-read/${id}`, function(data){
            refreshNotificationBadge(data);
            $(`#text-${id}`).removeClass('badge-success').text('');
            // The dropdown lists unread only — drop the read item from the feed.
            if ($('#notification-feed').length) {
                $('#notification-feed').load('{{ route('notification.feed') }}');
            }
        });
    }

    // Mark every notification as read from the dropdown header.
    $('#mark-all-read').on('click', function (e) {
        e.preventDefault();
        var token = $('#mark-all-read').data('token') || $('meta[name="csrf-token"]').attr('content');
        $.post('{{ route('notification.read-all') }}', { _token: token }, function (data) {
            if (data && data.status === 1) {
                refreshNotificationBadge(0);
                if ($('#notification-feed').length) {
                    $('#notification-feed').load('{{ route('notification.feed') }}');
                }
                showToast('All notifications marked as read.', 'success');
            }
        }).fail(function (xhr) {
            showToastAjaxError(xhr, 'Could not mark notifications as read.');
        });
    });

    // Live notification polling — refresh the badge and the dropdown feed every 30s.
    setInterval(function () {
        $.get('{{ route('notification.count') }}', function (data) {
            if (!data || typeof data.count === 'undefined') { return; } // e.g. session expired
            refreshNotificationBadge(data.count);
            if ($('#notification-feed').length) {
                $('#notification-feed').load('{{ route('notification.feed') }}');
            }
        });
    }, 30000);
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

/* Notification dropdown scrollbar */
#notification-feed::-webkit-scrollbar {
    width: 6px;
}
#notification-feed::-webkit-scrollbar-track {
    background: transparent;
}
#notification-feed::-webkit-scrollbar-thumb {
    background: rgba(15, 23, 42, 0.25);
    border-radius: 3px;
}
#notification-feed::-webkit-scrollbar-thumb:hover {
    background: rgba(15, 23, 42, 0.4);
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
