<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'bg-light active' : '' }}" id="dashboardNav">
                    <i class="mdi mdi-home"></i><span>Dashboard</span>
                </a>
            </li>
            <!-- Super Admin && Admin -->
            @if(in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                <li class="menu-title mt-2" data-key="t-menu">Configuration</li>
                <li>
                    <a href="{{ route('user.index') }}" class="{{ request()->is('user*') ? 'bg-light active' : '' }}" id="userNav">
                        <i class="mdi mdi-account-supervisor"></i>
                        <span>Manage User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dropdown.index') }}" class="{{ request()->is('dropdown*') ? 'bg-light active' : '' }}" id="dropdownNav">
                        <i class="mdi mdi-package-down"></i>
                        <span>Manage Dropdown</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grading.index') }}" class="{{ request()->is('grading*') ? 'bg-light active' : '' }}" id="gradNav">
                        <i class="mdi mdi-percent-outline"></i>
                        <span>Grading</span>
                    </a>
                </li>
                @if(Auth::user()->role == 'Super Admin')
                <li>
                    <a href="{{ route('rule.index') }}" class="{{ request()->is('rule*') ? 'bg-light active' : '' }}" id="ruleNav">
                        <i class="mdi mdi-cog-box"></i>
                        <span>Manage Rule</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('scheduler.index') }}" class="{{ request()->is('scheduler*') ? 'bg-light active' : '' }}" id="schedulerNav">
                        <i class="mdi mdi-bell-alert-outline"></i>
                        <span>List Scheduler</span>
                    </a>
                </li>
                @endif

                <li class="menu-title mt-2" data-key="t-menu">Master Data</li>
                <li>
                    <a href="{{ route('jaringan.index') }}" class="{{ request()->is('jaringan*') ? 'bg-light active' : '' }}" id="jaringanNav">
                        <i class="mdi mdi-office-building"></i>
                        <span>Master Jaringan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employee.index') }}" class="{{ request()->is('employee*') ? 'bg-light active' : '' }}" id="empNav">
                        <i class="mdi mdi-account-group"></i>
                        <span>Master Employee</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('department.index') }}" class="{{ request()->is('department*') ? 'bg-light active' : '' }}" id="deptNav">
                        <i class="mdi mdi-graph-outline"></i>
                        <span>Master Dept</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('position.index') }}" class="{{ request()->is('position*') ? 'bg-light active' : '' }}" id="positionNav">
                        <i class="mdi mdi-lan"></i>
                        <span>Master Position</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Master Checklist</li>
                <li>
                    <a href="{{ route('parentchecklist.typechecklist') }}" class="{{ request()->is('parentchecklist*') ? 'bg-light active' : '' }}" id="parentCheckNav">
                        <i class="mdi mdi-clipboard-check-multiple"></i>
                        <span>Master Parent Checklist</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('checklist.typechecklist') }}" class="{{ request()->is('checklist*') ? 'bg-light active' : '' }}" id="checklistNav">
                        <i class="mdi mdi-check-network"></i>
                        <span>Master Checklist</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mapchecklist.index') }}" class="{{ request()->is('mapchecklist*') ? 'bg-light active' : '' }}" id="mappingCheckNav">
                        <i class="mdi mdi-checkbox-multiple-outline"></i>
                        <span>Master Mapping Checklist</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Assign Checklist Audit</li>
                <li>
                    <a href="{{ route('periodname.index') }}" class="{{ request()->is('periodname*') ? 'bg-light active' : '' }}" id="periodNameNav">
                        <i class="mdi mdi-clipboard-text-clock"></i>
                        <span>Master Period Name</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('periodchecklist.index') }}" class="{{ request()->is('periodchecklist*') ? 'bg-light active' : '' }}" id="periodNav">
                        <i class="mdi mdi-check-underline-circle"></i>
                        <span>Period Checklist</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Review Menu</li>
                <li>
                    <a href="{{ route('review.periodList') }}" class="{{ request()->is('review*') ? 'bg-light active' : '' }}" id="reviewNav">
                        <i class="mdi mdi-message-draw"></i>
                        <span>Review Checklist</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Logs</li>
                <li>
                    <a href="{{ route('auditlog') }}" class="{{ request()->is('auditlog*') ? 'bg-light active' : '' }}" id="auditlogNav">
                        <i class="mdi mdi-chart-donut"></i>
                        <span>Audit Logs</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ route('assessor.listjaringan') }}">
                        <i class="mdi mdi-check-underline-circle"></i>
                        <span>Checklist</span>
                    </a>
                </li>
                <li class="menu-title" data-key="t-menu">Auditor Menu</li>
                <li>
                    <a href="{{ route('auditor.periodList') }}">
                        <i class="mdi mdi-file-check"></i>
                        <span>Assigned Checklist</span>
                    </a>
                </li>
                <li class="menu-title" data-key="t-menu">Assessor Menu</li>
                <li>
                    <a href="{{ route('assessor.listperiod.assigned') }}">
                        <i class="mdi mdi-message-draw"></i>
                        <span>Review Checklist</span>
                    </a>
                </li> --}}
            @endif

            <!-- PIC Dealers -->
            @if(Auth::user()->role == 'PIC Dealers')
                <li class="menu-title mt-2" data-key="t-menu">Master Checklist</li>
                <li>
                    <a href="{{ route('parentchecklist.typechecklist') }}" class="{{ request()->is('parentchecklist*') ? 'bg-light active' : '' }}" id="parentCheckNav">
                        <i class="mdi mdi-clipboard-check-multiple"></i>
                        <span>Master Parent Checklist</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('checklist.typechecklist') }}" class="{{ request()->is('checklist*') ? 'bg-light active' : '' }}" id="checklistNav">
                        <i class="mdi mdi-check-network"></i>
                        <span>Master Checklist</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mapchecklist.index') }}" class="{{ request()->is('mapchecklist*') ? 'bg-light active' : '' }}" id="mappingCheckNav">
                        <i class="mdi mdi-checkbox-multiple-outline"></i>
                        <span>Master Mapping Checklist</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Assign Checklist Audit</li>
                <li>
                    <a href="{{ route('periodname.index') }}" class="{{ request()->is('periodname*') ? 'bg-light active' : '' }}" id="periodNameNav">
                        <i class="mdi mdi-clipboard-text-clock"></i>
                        <span>Master Period Name</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('periodchecklist.index') }}" class="{{ request()->is('periodchecklist*') ? 'bg-light active' : '' }}" id="periodNav">
                        <i class="mdi mdi-check-underline-circle"></i>
                        <span>Period Checklist</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-menu">Review Menu</li>
                <li>
                    <a href="{{ route('review.periodList') }}" class="{{ request()->is('review*') ? 'bg-light active' : '' }}" id="reviewNav">
                        <i class="mdi mdi-message-draw"></i>
                        <span>Review Checklist</span>
                    </a>
                </li>
            @endif
            
            <!-- Internal Auditor Dealer -->
            @if(Auth::user()->role == 'Internal Auditor Dealer')
                <li class="menu-title" data-key="t-menu">Auditor Menu</li>
                <li>
                    <a href="{{ route('auditor.periodList') }}" class="{{ request()->is('auditor*') ? 'bg-light active' : '' }}" id="assignedNav">
                        <i class="mdi mdi-file-check"></i>
                        <span>Assigned Checklist</span>
                    </a>
                </li>
            @endif

            <!-- Assessor Main Dealer && PIC NOS MD -->
            @if(in_array(Auth::user()->role, ['Assessor Main Dealer','PIC NOS MD']))
                <li class="menu-title mt-2" data-key="t-menu">Review Menu</li>
                <li>
                    <a href="{{ route('review.periodList') }}" class="{{ request()->is('review*') ? 'bg-light active' : '' }}" id="reviewNav">
                        <i class="mdi mdi-message-draw"></i>
                        <span>Review Checklist</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!-- Sidebar -->
</div>