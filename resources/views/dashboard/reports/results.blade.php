@extends('dashboard.layouts.app')

@push('headScripts')
<style>
    .results-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 30px 35px;
        color: white;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    .results-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .results-hero h3 {
        margin: 0 0 6px 0;
        font-weight: 700;
        font-size: 22px;
    }
    .results-hero p {
        margin: 0;
        opacity: 0.85;
        font-size: 13px;
    }
    .icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .results-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .results-card-header {
        padding: 20px 28px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .results-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .badge-count {
        background: rgba(102,126,234,0.12);
        color: #667eea;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .report-table thead th {
        background: #1E3A5F;
        color: #fff;
        padding: 12px 16px;
        text-align: right;
        font-weight: 600;
        font-size: 12px;
        white-space: nowrap;
        position: sticky;
        top: 0;
    }
    .report-table tbody td {
        padding: 10px 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #34495e;
        text-align: right;
        vertical-align: middle;
    }
    .report-table tbody tr:nth-child(even) td {
        background: #f9fafc;
    }
    .report-table tbody tr:hover td {
        background: #eef1fb;
        transition: background 0.15s ease;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
        display: block;
        color: #ccc;
    }
    .empty-state p {
        font-size: 15px;
        margin: 0;
    }
    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 13px;
        text-decoration: none;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .btn-back    { background: #667eea; color: white; }
    .btn-excel   { background: #27ae60; color: white; }
    .btn-csv     { background: #e67e22; color: white; }
    .btn-pdf     { background: #e74c3c; color: white; }
    .btn-back:hover  { background: #556bd6; color: white; }
    .btn-excel:hover { background: #219a52; color: white; }
    .btn-csv:hover   { background: #d35400; color: white; }
    .btn-pdf:hover   { background: #c0392b; color: white; }

    .filters-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    .filter-tag {
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 500;
    }
    @media print {
        .no-print { display: none !important; }
        .results-card { box-shadow: none; border: 1px solid #ddd; }
    }
</style>
@endpush

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-md-flex align-items-center mb-3 no-print">
            <div class="pl-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}"><i class="bx bx-chart-bar"></i> {{ __('التقارير') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i> {{ __('الرئيسية') }}</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Hero --}}
        <div class="results-hero">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-wrapper" style="background: rgba(255,255,255,0.2); color: white;">
                    <i class='bx bx-table'></i>
                </div>
                <div>
                    <h3>{{ $report->label() }}</h3>
                    <p>{{ __('نتائج التقرير') }} &mdash; {{ now()->format('d M Y, H:i') }}</p>
                    @if (!empty($validated))
                        <div class="filters-meta">
                            @foreach ($validated as $key => $val)
                                @if ($val)
                                    <span class="filter-tag">{{ $val }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="results-card no-print">
            <div class="results-card-header">
                <h5>
                    <div class="icon-wrapper" style="background: rgba(102,126,234,0.12); color: #667eea; width:36px; height:36px; font-size:17px;">
                        <i class='bx bx-download'></i>
                    </div>
                    {{ __('تصدير النتائج') }}
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.index') }}" class="btn-action btn-back">
                        <i class='bx bx-arrow-back'></i> {{ __('رجوع') }}
                    </a>

                    <form method="POST" action="{{ route('reports.generate', $report->key()) }}" style="display:contents;">
                        @csrf
                        @foreach ($validated as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <input type="hidden" name="format" value="xlsx">
                        <button type="submit" class="btn-action btn-excel">
                            <i class='bx bx-file-doc'></i> {{ __('Excel') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('reports.generate', $report->key()) }}" style="display:contents;">
                        @csrf
                        @foreach ($validated as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <input type="hidden" name="format" value="csv">
                        <button type="submit" class="btn-action btn-csv">
                            <i class='bx bx-spreadsheet'></i> {{ __('CSV') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('reports.generate', $report->key()) }}" style="display:contents;">
                        @csrf
                        @foreach ($validated as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <input type="hidden" name="format" value="pdf">
                        <button type="submit" class="btn-action btn-pdf">
                            <i class='bx bx-file-pdf'></i> {{ __('PDF') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="results-card">
            <div class="results-card-header">
                <h5>
                    <div class="icon-wrapper" style="background: rgba(39,174,96,0.12); color: #27ae60; width:36px; height:36px; font-size:17px;">
                        <i class='bx bx-list-ul'></i>
                    </div>
                    {{ __('البيانات') }}
                </h5>
                <span class="badge-count">{{ $results->count() }} {{ __('سجل') }}</span>
            </div>

            <div class="table-wrapper">
                @if ($results->isEmpty())
                    <div class="empty-state">
                        <i class='bx bx-search-alt'></i>
                        <p>{{ __('لا توجد نتائج مطابقة للفلاتر المحددة.') }}</p>
                    </div>
                @else
                    <table class="report-table">
                        <thead>
                            <tr>
                                @foreach ($report->headings() as $heading)
                                    <th>{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $row)
                                <tr>
                                    @foreach ($report->map($row) as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection