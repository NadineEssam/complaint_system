<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #1a1a1a;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-left {
            vertical-align: middle;
        }

        .header-right {
            text-align: right;
            vertical-align: middle;
        }

        .header-title {
            margin: 0;
            font-size: 16px;
        }

        .header-meta {
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
</head>

<body>
    <table class="header-table">
        <tr>
            <td class="header-left">
                <h2 class="header-title">{{ $report->label() }}</h2>
                <small class="header-meta">Generated: {{ now()->format('d M Y H:i') }} &mdash; Filters:
                    {{ collect($filters)->join(', ') }}</small>
            </td>
            <td class="header-right">
                <img src="{{ public_path('logo.png') }}" alt="Logo" style="max-height: 60px;">
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                @foreach ($report->headings() as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $row)
                <tr>
                    @foreach ($report->map($row) as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($report->headings()) }}">No results found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
