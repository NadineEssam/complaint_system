@extends('dashboard.layouts.app')
@push('headScripts')
<style>
    .report-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 35px;
        color: white;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    .report-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .report-hero h3 {
        margin: 0 0 8px 0;
        font-weight: 700;
        font-size: 26px;
    }
    .report-hero p {
        margin: 0;
        opacity: 0.9;
        font-size: 15px;
    }
    .filter-card {
        background: white;
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
    }
    .filter-card h5 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    .icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .icon-primary { background: rgba(102, 126, 234, 0.12); color: #667eea; }
    .icon-success { background: rgba(39, 174, 96, 0.12); color: #27ae60; }
    .icon-warning { background: rgba(230, 126, 34, 0.12); color: #e67e22; }
    .icon-info { background: rgba(52, 152, 219, 0.12); color: #3498db; }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-weight: 600;
        color: #34495e;
        margin-bottom: 10px;
        display: block;
        font-size: 14px;
    }
    .form-control, .form-select {
        width: 100%;
        padding: 13px 16px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
        background: white;
    }
    .btn-action {
        padding: 13px 26px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .btn-preview { background: #3498db; color: white; }
    .btn-excel { background: #27ae60; color: white; }
    .btn-csv { background: #e67e22; color: white; }
    .btn-pdf { background: #e74c3c; color: white; }
    .btn-preview:hover { background: #2980b9; color: white; }
    .btn-excel:hover { background: #219a52; color: white; }
    .btn-csv:hover { background: #d35400; color: white; }
    .btn-pdf:hover { background: #c0392b; color: white; }
    .spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: slideIn 0.4s ease-out forwards; }
    .select-wrapper {
        position: relative;
    }
    .select-wrapper::after {
        content: '';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid #667eea;
        pointer-events: none;
        transition: transform 0.3s ease;
        z-index: 1;
    }
    .select-wrapper .form-select {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 45px;
        cursor: pointer;
        background-image: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    .select-wrapper .form-select:hover {
        border-color: #667eea;
        background-image: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    .select-wrapper .form-select:focus {
        border-color: #667eea;
        background-image: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    }
    .select-wrapper .form-select option {
        padding: 12px 16px;
        background: white;
        color: #34495e;
    }
    .select-wrapper .form-select option:hover,
    .select-wrapper .form-select option:focus {
        background: #667eea;
        color: white;
    }
    .custom-select-container {
        position: relative;
        margin-bottom: 20px;
    }
    .custom-select-container .form-select {
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        padding: 13px 45px 13px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #34495e;
        background-color: #fafafa;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
    }
    .custom-select-container .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        outline: none;
        background-color: white;
    }
    .custom-select-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #667eea;
        font-size: 18px;
        z-index: 2;
    }
    .select-with-icon {
        position: relative;
    }
    .select-with-icon::after {
        display: none;
    }
    /* .select-with-icon .form-select {
        padding-left: 45px;
    } */
</style>
@endpush
@section('content')

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-md-flex align-items-center mb-3">
            <div class="pl-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i> {{ __('الرئيسية') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}"><i class="bx bx-chart-bar"></i> {{ __('التقارير') }}</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="report-hero animate-in">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper icon-primary mr-3" style="background: rgba(255,255,255,0.2); color: white;">
                    <i class='bx bx-file-chart'></i>
                </div>
                <div>
                    <h3>{{ __('استخراج التقارير') }}</h3>
                    <p>{{ __('إنشاء واستخراج التقارير بصيغ متعددة مع فلاتر مخصصة') }}</p>
                </div>
            </div>
        </div>

<form id="report-form" action="" method="POST">
    @csrf

    <div class="filter-card animate-in">
        <h5>
            <div class="icon-wrapper icon-primary"><i class='bx bx-file'></i></div>
            {{ __('اختر نوع التقرير') }}
        </h5>
        <div class="row">
            <div class="col-6">
                <div class="">
                    <label for="report-select">{{ __('التقرير') }}</label>
                    <select class="form-select" id="report-select" name="report_key" onchange="loadFilters(this.value)">
                        <option value="">— {{ __('اختر تقريراً') }} —</option>
                        @foreach ($reports as $report)
                            <option value="{{ $report->key() }}">{{ $report->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

           
        </div>
    </div>

    <div id="filters-container"></div>

    <div id="export-buttons" class="filter-card animate-in" style="display:none;">
        <h5>
            <div class="icon-wrapper icon-success"><i class='bx bx-download'></i></div>
            {{ __('خيارات الاستخراج') }}
        </h5>
        <div class="d-flex flex-wrap gap-3 mt-2">
            {{-- <button class="btn-action btn-preview" type="button" onclick="submitForm('view')">
                <i class='bx bx-show'></i> {{ __('Preview') }}
            </button> --}}
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
        <div class="filter-card">
            <div class="text-center py-4">
                <div class="spinner mx-auto" style="border-color: rgba(102,126,234,0.3); border-top-color: #667eea; margin-bottom: 12px;"></div>
                <p class="text-muted mb-0">{{ __('جاري التحميل...') }}</p>
            </div>
        </div>
    `;

    try {
        const res = await fetch(`/reports/${key}/filters`);
        const filters = await res.json();

        let html = `
            <div class="filter-card animate-in">
                <h5>
                    <div class="icon-wrapper icon-warning"><i class='bx bx-filter-alt'></i></div>
                    {{ __('خيارات تصفية التقرير') }}
                </h5>
        `;

        filters.forEach(f => {
            if (f.type === 'select') {
                let opts = '';
                Object.entries(f.options).forEach(([v, t]) => {
                    const sel = v === f.default ? 'selected' : '';
                    opts += `<option value="${v}" ${sel}>${t}</option>`;
                });
                html += `
                    <div class="col-6">
                        <div class="">
                            <label for="${f.name}">${f.label}</label>
                            <select class="form-select" name="${f.name}" id="${f.name}" ${f.required ? 'required' : ''}>${opts}</select>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-6">
                        <div class="form-group">
                            <label for="${f.name}">${f.label}</label>
                            <input type="${f.type}" name="${f.name}" id="${f.name}" class="form-control" ${f.required ? 'required' : ''}>
                        </div>
                    </div>
                `;
            }
        });

        html += '<div class="row"></div></div>';
        container.innerHTML = html;
        form.action = `/reports/${key}/generate`;
        buttons.style.display = 'block';
    } catch (err) {
        container.innerHTML = `
            <div class="filter-card">
                <div class="alert alert-danger d-flex align-items-center mb-0">
                    <i class='bx bx-error-circle mr-2'></i>
                    {{ __('Failed to load filters. Please try again.') }}
                </div>
            </div>
        `;
    }
}

function submitForm(format) {
    const required = container.querySelectorAll('[required]');
    for (const field of required) {
        if (!field.value) {
            field.focus();
            field.reportValidity();
            return;
        }
    }
    formatInput.value = format;
    form.submit();
}
</script>

@endsection
