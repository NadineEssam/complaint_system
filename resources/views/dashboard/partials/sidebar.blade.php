<!-- ======= Sidebar ======= -->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    #sidebar, #sidebar * {
        font-family: 'Cairo', 'Tahoma', sans-serif;
    }

    #sidebar {
        background: #ffffff;
        border-left: 1px solid #eef0f3;
    }

    #sidebar .sidebar-nav {
        padding: 14px 12px;
    }

    /* Section headings */
    #sidebar .nav-heading {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .3px;
        color: #98a2b3;
        text-transform: uppercase;
        margin: 22px 14px 8px;
        padding-top: 14px;
        border-top: 1px solid #f0f2f5;
    }
    #sidebar .nav-heading:first-child {
        margin-top: 4px;
        padding-top: 0;
        border-top: none;
    }

    /* Nav items */
    #sidebar .nav-item {
        margin-bottom: 3px;
    }

    #sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        color: #475467;
        font-size: 15px;
        font-weight: 600;
        transition: background .15s, color .15s;
        position: relative;
    }

    #sidebar .nav-link i {
        font-size: 20px;
        color: #98a2b3;
        flex-shrink: 0;
        transition: color .15s;
    }

    #sidebar .nav-link:hover {
        background: #f5f8ff;
        color: #0d6efd;
    }
    #sidebar .nav-link:hover i {
        color: #0d6efd;
    }

    /* Active state — accent bar + tinted background, matches app's blue */
    #sidebar .nav-item.mm-active .nav-link {
        background: #eaf2ff;
        color: #0d6efd;
        font-weight: 700;
    }
    #sidebar .nav-item.mm-active .nav-link i {
        color: #0d6efd;
    }
    #sidebar .nav-item.mm-active .nav-link::before {
        content: "";
        position: absolute;
        right: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 60%;
        background: #0d6efd;
        border-radius: 4px 0 0 4px;
    }
</style>

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

     <li class="nav-item  @if (request()->segment(1) == '') mm-active @endif  ">
       <a class="nav-link collapsed" href="{{ route('home') }}">
         <i class="bi bi-grid"></i>
         <span>الرئيسية</span>
       </a>
     </li>

        {{-- ===================== إدارة الشكاوى ===================== --}}
        <li class="nav-heading">إدارة الشكاوى</li>

        @if (PerUser('complaints.index'))
        <li class="nav-item {{ request()->segment(1) == 'complaints' ? 'mm-active' : '' }}">
            <a class="nav-link collapsed" href="{{ route('complaints.index') }}">
                <i class="bx bx-list-ul"></i>
                <span>الشكاوى</span>
            </a>
        </li>
        @endif

        {{-- ===================== إدارة الطلبات والخدمات ===================== --}}
        <li class="nav-heading">إدارة الطلبات والخدمات</li>

        @if (PerUser('services.index'))
        <li class="nav-item {{ request()->segment(1) == 'services' ? 'mm-active' : '' }}">
            <a class="nav-link collapsed" href="{{ route('services.index') }}">
                <i class="bx bx-cog"></i>
                <span>أنواع الخدمات</span>
            </a>
        </li>
        @endif

        @if (PerUser('sources.index'))
        <li class="nav-item {{ request()->segment(1) == 'sources' ? 'mm-active' : '' }}">
            <a class="nav-link collapsed" href="{{ route('sources.index') }}">
                <i class="bx bx-sitemap"></i>
                <span>مصادر الشكاوى</span>
            </a>
        </li>
        @endif

        @if (PerUser('close-reason-classify.index'))
        <li class="nav-item {{ request()->segment(1) == 'close-reason-classify' ? 'mm-active' : '' }}">
            <a class="nav-link collapsed" href="{{ route('close-reason-classify.index') }}">
                <i class="bx bx-purchase-tag"></i>
                <span>تصنيفات الأسباب</span>
            </a>
        </li>
        @endif

        {{-- ===================== تقارير النظام ===================== --}}
        <li class="nav-heading">تقارير النظام</li>

        @if (PerUser('reports.index'))
        <li class="nav-item @if (request()->segment(1) == 'reports') mm-active @endif">
            <a class="nav-link collapsed" href="{{ route('reports.index') }}">
                <i class="bx bx-bar-chart-alt-2"></i>
                <span>التقارير</span>
            </a>
        </li>
        @endif

        {{-- ===================== إعدادات النظام ===================== --}}
        <li class="nav-heading">إعدادات النظام</li>
        @if (PerUser('users.index'))
        <li class="nav-item @if (request()->segment(1) == 'users') mm-active @endif">
            <a class="nav-link collapsed" href="{{ route('users.index') }}">
                <i class="bx bx-user"></i>
                <span>المستخدمين</span>
            </a>
        </li>
        @endif
        @if (PerUser('roles.index'))
        <li class="nav-item @if (request()->segment(1) == 'roles') mm-active @endif">
            <a class="nav-link collapsed" href="{{ route('roles.index') }}">
                <i class="bx bx-shield-quarter"></i>
                <span>الأدوار والصلاحيات</span>
            </a>
        </li>
        @endif

        

    </ul>

</aside><!-- End Sidebar -->