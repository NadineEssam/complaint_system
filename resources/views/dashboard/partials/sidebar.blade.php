 <!-- ======= Sidebar ======= -->

 <aside id="sidebar" class="sidebar">

   <ul class="sidebar-nav" id="sidebar-nav">

     <li class="nav-item  @if (request()->segment(1) == 'dashboard') mm-active @endif  ">
       <a class="nav-link collapsed" href="{{ route('dashboard') }}">
         <i class="bi bi-grid"></i>
         <span>الرئيسية</span>
       </a>
     </li>


     <li class="nav-heading"> ادارة الشكاوى </li>

     @if (PerUser('complaints.index'))
     <li class="nav-item {{ request()->segment(1) == 'complaints' ? 'mm-active' : '' }}">
       <a class="nav-link collapsed" href="{{ route('complaints.index') }}">
         <i class="bi bi-card-list"></i>
         <span> الشكاوى </span>
       </a>
     </li>
     @endif
     <!-- UPDATE HEADING -->
     <li class="nav-heading"> ادارة الطلبات والخدمات </li>

     <!-- SERVICES (You already added this) -->
     @if (PerUser('services.index'))
     <li class="nav-item {{ request()->segment(1) == 'services' ? 'mm-active' : '' }}">
       <a class="nav-link collapsed" href="{{ route('services.index') }}">
         <i class="bi bi-gear"></i>
         <span> أنواع الخدمات </span>
       </a>
     </li>
     @endif

     <!-- SOURCES (NEW - Add this) -->
     @if (PerUser('sources.index'))
     <li class="nav-item {{ request()->segment(1) == 'sources' ? 'mm-active' : '' }}">
       <a class="nav-link collapsed" href="{{ route('sources.index') }}">
         <i class="bi bi-diagram-3"></i>
         <span> مصادر الشكاوى </span>
       </a>
     </li>
     @endif

     <!-- CLASSIFICATIONS (NEW - Add this) -->
     @if (PerUser('close-reason-classify.index'))
     <li class="nav-item {{ request()->segment(1) == 'close-reason-classify' ? 'mm-active' : '' }}">
       <a class="nav-link collapsed" href="{{ route('close-reason-classify.index') }}">
         <i class="bi bi-tag"></i>
         <span> تصنيفات الأسباب </span>
       </a>
     </li>
     @endif







     <li class="nav-heading">تقارير النظام</li>

     @if (PerUser('reports.index') )
     <li class="nav-item  @if (request()->segment(1) == 'reports') mm-active @endif  ">
       <a class="nav-link collapsed" href="{{ route('reports.index') }}">
         <i class="bi bi-card-list"></i>
         <span> التقارير </span>
       </a>
     </li>
     @endif


     {{-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>
          <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Accordion</span>
            </a>
          </li>
          <li>
            <a href="components-badges.html">
              <i class="bi bi-circle"></i><span>Badges</span>
            </a>
          </li>
          <li>
            <a href="components-breadcrumbs.html">
              <i class="bi bi-circle"></i><span>Breadcrumbs</span>
            </a>
          </li>
          <li>
            <a href="components-buttons.html">
              <i class="bi bi-circle"></i><span>Buttons</span>
            </a>
          </li>
          <li>
            <a href="components-cards.html">
              <i class="bi bi-circle"></i><span>Cards</span>
            </a>
          </li>
          <li>
            <a href="components-carousel.html">
              <i class="bi bi-circle"></i><span>Carousel</span>
            </a>
          </li>
          <li>
            <a href="components-list-group.html">
              <i class="bi bi-circle"></i><span>List group</span>
            </a>
          </li>
          <li>
            <a href="components-modal.html">
              <i class="bi bi-circle"></i><span>Modal</span>
            </a>
          </li>
          <li>
            <a href="components-tabs.html">
              <i class="bi bi-circle"></i><span>Tabs</span>
            </a>
          </li>
          <li>
            <a href="components-pagination.html">
              <i class="bi bi-circle"></i><span>Pagination</span>
            </a>
          </li>
          <li>
            <a href="components-progress.html">
              <i class="bi bi-circle"></i><span>Progress</span>
            </a>
          </li>
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav --> --}}

     <li class="nav-heading">اعدادات النظام</li>

     @if (PerUser('roles.index') )
     <li class="nav-item  @if (request()->segment(1) == 'roles') mm-active @endif  ">
       <a class="nav-link collapsed" href="{{ route('roles.index') }}">
         <i class="bx bx-shield-quarter"></i>
         <span>الأدوار والصلاحيات </span>
       </a>
     </li>
     @endif

     @if(PerUser('users.index') )
     <li class="nav-item  @if (request()->segment(1) == 'users') mm-active @endif ">
       <a class="nav-link collapsed" href="{{ route('users.index') }}">
         <i class="bi bi-person"></i>
         <span>المستخدمين</span>
       </a>
     </li>
     @endif




   </ul>

 </aside><!-- End Sidebar-->