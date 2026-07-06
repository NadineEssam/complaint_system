@extends('dashboard.layouts.app')
@push('headScripts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ================= GLOBAL ================= */
    .reports-page, .reports-page * {
        font-family: 'Cairo', 'Tahoma', sans-serif;
        font-size: 13px;
    }

    .reports-page {
        background: #f7f8fa;
        padding: 24px;
        border-radius: 16px;
    }

    /* ================= BREADCRUMB ================= */
    .reports-page .breadcrumb {
        margin-bottom: 0;
        background: transparent;
        padding: 0;
    }

    .reports-page .breadcrumb-item,
    .reports-page .breadcrumb-item a {
        font-size: 13px;
        color: #98a2b3;
        text-decoration: none;
    }

    .reports-page .breadcrumb-item.active {
        color: #1f2937;
        font-weight: 700;
    }

    /* ================= CARD ================= */
    .reports-page .main-card {
        border: 1px solid #eef0f3 !important;
        border-radius: 14px !important;
        background: #fff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    /* ================= CARD HEADER ================= */
    .reports-page .card-top-header {
        padding: 16px 20px;
        border-bottom: 1px solid #eef0f3;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
    }

    .reports-page .card-top-header h4 {
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reports-page .card-top-header h4 i {
        color: #3b76e0;
        font-size: 16px;
    }

    /* ================= FILTER-STYLE SECTIONS (reused for report/filter blocks) ================= */
    .reports-page .filter-bar {
        background: #f7f8fa;
        border: 1px solid #eef0f3;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }

    .reports-page .filter-bar .filter-title {
        font-size: 13px;
        font-weight: 700;
        color: #3b76e0;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
    }

    .reports-page .filter-bar .filter-title i {
        font-size: 15px;
    }

    .reports-page .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .reports-page .form-control,
    .reports-page .form-select {
        font-size: 13px;
        font-family: 'Cairo', 'Tahoma', sans-serif;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        color: #1f2937;
        padding: 8px 12px;
        background-color: #fff;
        box-shadow: none !important;
        transition: border-color .15s;
        width: 100%;
    }

    .reports-page .form-control:focus,
    .reports-page .form-select:focus {
        border-color: #3b76e0;
        box-shadow: 0 0 0 0.18rem rgba(59,118,224,.13) !important;
        outline: none;
    }

    /* ================= ACTION BUTTONS ================= */
    .reports-page .btn-action {
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        border: none;
        cursor: pointer;
    }

    .reports-page .btn-preview { background: #eaf2fb; color: #3b76e0; border: 1px solid #cfe0f7; }
    .reports-page .btn-excel   { background: #eafaf0; color: #2f9e63; border: 1px solid #c6edd8; }
    .reports-page .btn-csv     { background: #fff6e9; color: #b7791f; border: 1px solid #f5e3c3; }
    .reports-page .btn-pdf     { background: #fdeeee; color: #d64545; border: 1px solid #f6cfcf; }

    .reports-page .btn-preview:hover { background: #d9e9fa; color: #2a5eb8; }
    .reports-page .btn-excel:hover   { background: #d4f5e4; color: #1f7a4b; }
    .reports-page .btn-csv:hover     { background: #fbecd2; color: #94600f; }
    .reports-page .btn-pdf:hover     { background: #fadfdf; color: #b23434; }

    .reports-page .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush
@section('content')

<div class="reports-page" dir="rtl">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">استخراج التقارير</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('reports.index') }}">
                        <i class="bx bx-chart-bar"></i> {{ __('التقارير') }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        <i class="bx bx-home-alt"></i> {{ __('الرئيسية') }}
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    {{-- ================= MAIN CARD ================= --}}
    <div class="main-card">

        {{-- Card Header --}}
        <div class="card-top-header">
            <h4>
                <i class="bx bx-file-chart"></i>
                {{ __('استخراج التقارير') }}
            </h4>
        </div>

        {{-- Body --}}
        <div class="p-3">

            <form id="report-form" action="" method="POST">
                @csrf

                <div class="filter-bar">
                    <div class="filter-title">
                        <i class="bx bx-file"></i>
                        {{ __('اختر نوع التقرير') }}
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label" for="report-select">{{ __('التقرير') }}</label>
                            <select class="form-select" id="report-select" name="report_key" onchange="loadFilters(this.value)">
                                <option value="">— {{ __('اختر تقريراً') }} —</option>
                                @foreach ($reports as $report)
                                    <option value="{{ $report->key() }}">{{ $report->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="filters-container"></div>

                <div id="export-buttons" class="filter-bar" style="display:none;">
                    <div class="filter-title">
                        <i class="bx bx-download"></i>
                        {{ __('خيارات الاستخراج') }}
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button class="btn-action btn-preview" type="button" onclick="submitForm('view')">
                            <i class='bx bx-show'></i> {{ __('عرض') }}
                        </button>
                        <button class="btn-action btn-excel" type="button" onclick="submitForm('xlsx')">
                            <i class='bx bx-file-doc'></i> {{ __('استخراج Excel') }}
                        </button>
                        <button class="btn-action btn-csv" type="button" onclick="submitForm('csv')">
                            <i class='bx bx-spreadsheet'></i> {{ __('استخراج CSV') }}
                        </button>
                        <button class="btn-action btn-pdf" type="button" onclick="submitForm('pdf')">
                            <i class='bx bx-file-pdf'></i> {{ __('استخراج PDF') }}
                        </button>
                    </div>
                </div>

                <input type="hidden" id="format-input" name="format" value="view">
            </form>

        </div>

    </div>

</div>

<script>
const form = document.getElementById('report-form');
const container = document.getElementById('filters-container');
const buttons = document.getElementById('export-buttons');
const formatInput = document.getElementById('format-input');

async function loadFilters(key) {
    container.innerHTML = '';
    buttons.style.display = 'none';
    if (!key) return;

    container.innerHTML = `
        <div class="filter-bar">
            <div class="text-center py-4">
                <div class="spinner mx-auto" style="border-color: rgba(59,118,224,0.3); border-top-color: #3b76e0; margin-bottom: 12px; display:inline-block;"></div>
                <p class="text-muted mb-0">{{ __('جاري التحميل...') }}</p>
            </div>
        </div>
    `;

    try {
        const res = await fetch(`/reports/${key}/filters`);
        const filters = await res.json();

        let html = `
            <div class="filter-bar">
                <div class="filter-title">
                    <i class="bx bx-filter-alt"></i>
                    {{ __('خيارات تصفية التقرير') }}
                </div>
                <div class="row">
        `;

        filters.forEach(f => {
            if (f.type === 'select') {
                let opts = '';
                Object.entries(f.options).forEach(([v, t]) => {
                    const sel = v === f.default ? 'selected' : '';
                    opts += `<option value="${v}" ${sel}>${t}</option>`;
                });
                html += `
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="${f.name}">${f.label}</label>
                        <select class="form-select" name="${f.name}" id="${f.name}" ${f.required ? 'required' : ''}>${opts}</select>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="${f.name}">${f.label}</label>
                        <input type="${f.type}" name="${f.name}" id="${f.name}" class="form-control" ${f.required ? 'required' : ''}>
                    </div>
                `;
            }
        });

        html += '</div></div>';
        container.innerHTML = html;
        form.action = `/reports/${key}/generate`;
        buttons.style.display = 'block';
    } catch (err) {
        container.innerHTML = `
            <div class="filter-bar">
                <div class="alert alert-danger d-flex align-items-center mb-0">
                    <i class='bx bx-error-circle mr-2'></i>
                    {{ __('Failed to load filters. Please try again.') }}
                </div>
            </div>
        `;
    }
}

function submitForm(format) {
    if (!form.action || form.action === window.location.href) {
        alert('{{ __("يرجى اختيار نوع التقرير أولاً") }}');
        return;
    }
    const required = container.querySelectorAll('[required]');
    for (const field of required) {
        if (!field.value) {
            field.focus();
            field.reportValidity();
            return;
        }
    }
    formatInput.value = format;
    form.target = '_blank';
    form.submit();
}

function submitFormNewTab(format) {
    formatInput.value = format;

    const newTab = window.open('', '_blank');

    form.target = '_blank';
    form.submit();

    form.target = '_blank';
}
</script>

@endsection