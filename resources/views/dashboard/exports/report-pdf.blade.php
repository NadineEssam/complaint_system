<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #1a1a1a;
        }

        h2 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        small {
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        thead th {
            background: #1E3A5F;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        tbody tr:nth-child(even) td {
            background: #f7f8fa;
        }
    </style>
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

</head>

<body>
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="">
                <img src="{{ public_path('logo.png') }}" alt="Logo" style="max-height: 60px;">
            </td>

            <td style="text-align: right;">
                <h2 style="margin: 0; margin-bottom: 3px;">{{ $report->label() }}</h2>
                <small>Generated by: {{ auth()->user()->userID }}  <br> 
                <small>Generated: {{ now()->format('d M Y H:i') }}  <br> 
                    
                    @if( !empty($filters)  )
                        Filters:
                        {{ collect($filters)->join(', ') }}
                    @endif
                </small>
            </td>
          
        </tr>
    </table>
    <div class="table-wrapper">
    <table class="report-table" >
        <thead>
            <tr>
                @foreach ($report->headings() as $heading)
                    <th  style="text-align: right;" >{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $row)
                <tr>
                    @foreach ($report->map($row) as $cell)
                        <td style="text-align: right;">{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($report->headings()) }}">No results found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</body>

</html>
