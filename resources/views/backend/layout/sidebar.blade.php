<div data-simplebar>
    <ul class="app-menu">

        <li class="menu-title">Menu</li>

        <li class="menu-item">
            <a href="{{route('dashboard')}}" class="menu-link waves-effect waves-light">
                <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                <span class="menu-text"> Dashboards </span>

            </a>
        </li>

{{--        <li class="menu-title">General Ledger</li>--}}



        <li class="menu-item">
            <a href="#menuExpages" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                <span class="menu-icon"><i class="bx bx-file"></i></span>
                <span class="menu-text"> Configuration Setup</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuExpages">
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href="{{route('calender.index')}}" class="menu-link">
                            <span class="menu-text">Calender Setup</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="menu-item">
            <a href="#general-ledger" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                <span class="menu-icon"><i class="bx bx-file"></i></span>
                <span class="menu-text"> General Ledger</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="general-ledger">
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href="{{route('journal-voucher.index')}}" class="menu-link">
                            <span class="menu-text">Journal Voucher</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('journal-voucher.list')}}" class="menu-link">
                            <span class="menu-text">Transaction List</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('transaction.index')}}" class="menu-link">
                            <span class="menu-text">Transaction Authorize</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{route('gl-report.index')}}" class="menu-link">
                            <span class="menu-text">Report</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
    </ul>
</div>
