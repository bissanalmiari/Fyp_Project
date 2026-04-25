<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Program Comparison Export</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }

        h1, h2, h3 {
            color: #7F64CE;
            margin-bottom: 6px;
        }

        p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th, td {
            border: 1px solid #d7d1ef;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        th:first-child, td:first-child {
            text-align: left;
        }

        th {
            background: #C3BFFA;
            color: #5B45B0;
        }

        .striped:nth-child(even) td {
            background: #F6F4FE;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 14px;
            margin-top: 20px;
        }

        .warning {
            color: #b91c1c;
            margin-top: 6px;
            padding: 8px 10px;
            border: 1px solid #f2b6b6;
            background: #fff5f5;
            border-radius: 8px;
        }

        .small {
            font-size: 10px;
            color: #666;
        }

        .section {
            margin-top: 24px;
        }

        .compat-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
            margin-top: 8px;
        }

        .compat-box {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 12px;
            background: #faf9ff;
        }

        .pts {
            font-weight: bold;
            color: #7F64CE;
        }
    </style>
</head>
<body>
    @php
        $rows = $comparisonData['rows'] ?? [];
        $summaryRow = $comparisonData['summary_row'] ?? null;
        $warningsA = $comparisonData['program_a']['warnings'] ?? [];
        $warningsB = $comparisonData['program_b']['warnings'] ?? [];
        $tiedPrograms = $comparisonData['winner']['tied_programs'] ?? [];

        $formatPoints = function ($value) {
            if (is_null($value)) return '—';
            return rtrim(rtrim(number_format($value, 1), '0'), '.') . ' pts';
        };
    @endphp

    <h1>Program Comparison Report</h1>

    <p><strong>Program A:</strong> {{ $programA->name }}</p>
    <p><strong>Program B:</strong> {{ $programB->name }}</p>

    <div class="section">
        <h2>Comparison Results</h2>

        <table>
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>{{ $programA->name }}</th>
                    <th>{{ $programB->name }}</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr class="striped">
                        <td>
                            @if(!empty($row['group_label']))
                                <strong>{{ $row['group_label'] }}:</strong>
                            @endif
                            {{ $row['label'] }}
                            <div class="small">Max: {{ $row['weight'] }} pts</div>
                        </td>

                        <td>
                            <div class="pts">{{ $formatPoints($row['program_a_points']) }}</div>
                            <div class="small">
                                {{ is_null($row['program_a_percent']) ? '—' : $row['program_a_percent'] . '%' }}
                            </div>
                        </td>

                        <td>
                            <div class="pts">{{ $formatPoints($row['program_b_points']) }}</div>
                            <div class="small">
                                {{ is_null($row['program_b_percent']) ? '—' : $row['program_b_percent'] . '%' }}
                            </div>
                        </td>

                        <td>
                            {{ $row['winner'] ?? '—' }}
                        </td>
                    </tr>
                @endforeach

                @if($summaryRow)
                    <tr>
                        <td><strong>Overall Result</strong></td>
                        <td>
                            <div class="pts">{{ $formatPoints($summaryRow['program_a_points']) }}</div>
                            <div class="small">{{ $summaryRow['program_a_percent'] }}%</div>
                        </td>
                        <td>
                            <div class="pts">{{ $formatPoints($summaryRow['program_b_points']) }}</div>
                            <div class="small">{{ $summaryRow['program_b_percent'] }}%</div>
                        </td>
                        <td><strong>{{ $summaryRow['winner'] }}</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(!empty($warningsA))
        <div class="section">
            <h3>Program A Warnings</h3>
            @foreach($warningsA as $warning)
                <div class="warning">{{ $warning }}</div>
            @endforeach
        </div>
    @endif

    @if(!empty($warningsB))
        <div class="section">
            <h3>Program B Warnings</h3>
            @foreach($warningsB as $warning)
                <div class="warning">{{ $warning }}</div>
            @endforeach
        </div>
    @endif

    <div class="section">
        <h2>Profile Compatibility</h2>

        @if(count($tiedPrograms) > 1)
            <p><strong>Result:</strong> Tie between selected programs</p>

            <table class="compat-grid">
                <tr>
                    @foreach($tiedPrograms as $item)
                        <td class="compat-box">
                            <h3>{{ $item['program_name'] }}</h3>
                            <p><strong>Overall Points:</strong> {{ $formatPoints($item['overall']) }}</p>
                            <p><strong>Academic:</strong> {{ round($item['groups']['academic'] ?? 0) }}%</p>
                            <p><strong>Preferences:</strong> {{ round($item['groups']['preferences'] ?? 0) }}%</p>
                            <p><strong>Relevance:</strong> {{ round($item['groups']['relevance'] ?? 0) }}%</p>
                            <p><strong>Cost:</strong> {{ round($item['groups']['cost'] ?? 0) }}%</p>
                        </td>
                    @endforeach
                </tr>
            </table>
        @else
            <div class="card">
                <p><strong>Best Match:</strong> {{ $comparisonData['winner']['program_name'] }}</p>
                <p><strong>Overall Points:</strong> {{ $formatPoints($comparisonData['winner']['overall'] ?? null) }}</p>
                <p><strong>Academic:</strong> {{ round($comparisonData['winner']['groups']['academic'] ?? 0) }}%</p>
                <p><strong>Preferences:</strong> {{ round($comparisonData['winner']['groups']['preferences'] ?? 0) }}%</p>
                <p><strong>Relevance:</strong> {{ round($comparisonData['winner']['groups']['relevance'] ?? 0) }}%</p>
                <p><strong>Cost:</strong> {{ round($comparisonData['winner']['groups']['cost'] ?? 0) }}%</p>
            </div>
        @endif
    </div>
</body>
</html>