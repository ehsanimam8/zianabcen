<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Official Transcript - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #5d0080; padding-bottom: 10px; }
        .logo { max-width: 150px; margin-bottom: 15px; }
        .org-name { font-size: 24px; font-weight: bold; color: #5d0080; text-transform: uppercase; }
        .title { font-size: 18px; font-weight: bold; letter-spacing: 2px; margin-top: 5px; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { padding: 5px 0; }
        .grades-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grades-table th { background-color: #f8f1fb; color: #5d0080; padding: 10px; text-align: left; font-size: 11px; border-bottom: 1px solid #ddd; }
        .grades-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .term-header { background-color: #efdff6; font-weight: bold; padding: 5px 10px; }
        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        .official-seal { text-align: right; margin-top: 40px; }
        .signature-line { border-top: 1px solid #333; width: 200px; display: inline-block; margin-top: 50px; text-align: center; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $tenant->name ?? 'Zainab Center' }}</div>
        <div class="title">OFFICIAL ACADEMIC TRANSCRIPT</div>
    </div>

    <table class="details-table">
        <tr>
            <td width="20%"><strong>Student Name:</strong></td>
            <td width="30%">{{ $student->name }}</td>
            <td width="20%"><strong>Date Issued:</strong></td>
            <td width="30%">{{ now()->format('F j, Y') }}</td>
        </tr>
        <tr>
            <td><strong>Roll Number:</strong></td>
            <td>{{ $student->roll_number ?? 'N/A' }}</td>
            <td><strong>Document ID:</strong></td>
            <td>TR-{{ strtoupper(\Illuminate\Support\Str::random(8)) }}</td>
        </tr>
    </table>

    @if($grades->isEmpty())
        <p style="text-align: center; font-style: italic; color: #777;">No academic records available for this student.</p>
    @else
        <table class="grades-table">
            <thead>
                <tr>
                    <th width="40%">COURSE TITLE</th>
                    <th width="15%">CREDITS</th>
                    <th width="15%">SCORE (%)</th>
                    <th width="15%">GRADE</th>
                    <th width="15%">COMPLETED</th>
                </tr>
            </thead>
            <tbody>
                @php $currentTerm = ''; @endphp
                
                @foreach($grades as $grade)
                    @php $termName = $grade->enrollment->term->name ?? 'Unknown Term'; @endphp
                    
                    @if($termName !== $currentTerm)
                        <tr>
                            <td colspan="5" class="term-header">{{ $termName }}</td>
                        </tr>
                        @php $currentTerm = $termName; @endphp
                    @endif
                    
                    <tr>
                        <td>{{ $grade->course->code ?? '' }} - {{ $grade->course->name ?? 'Unknown Course' }}</td>
                        <td>{{ $grade->course->credits ?? '3.0' }}</td>
                        <td>{{ $grade->percentage ? $grade->percentage . '%' : 'N/A' }}</td>
                        <td><strong>{{ $grade->letter_grade ?? 'Pass' }}</strong></td>
                        <td>{{ $grade->recorded_at ? $grade->recorded_at->format('M Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Statistics -->
        <table style="width: 50%; float: right; border-collapse: collapse; border: 1px solid #ddd; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; background-color: #f8f1fb; font-weight: bold;">Earned Credits:</td>
                <td style="padding: 10px; text-align: right;">{{ $grades->sum(fn($g) => $g->course->credits ?? 0) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; background-color: #f8f1fb; font-weight: bold;">Average Grade:</td>
                <td style="padding: 10px; text-align: right;">
                    @php
                        $validGrades = $grades->whereNotNull('percentage');
                        $avg = $validGrades->count() > 0 ? $validGrades->avg('percentage') : null;
                    @endphp
                    {{ $avg ? number_format($avg, 1) . '%' : 'N/A' }}
                </td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    @endif

    <div class="official-seal">
        <div class="signature-line">
            Registrar's Signature<br>
            {{ $tenant->name ?? 'Zainab Center' }}
        </div>
    </div>

    <div class="footer">
        This document is an official academic transcript. It contains the permanent academic record of the student named above.<br>
        Issued via Zainab Center Unified Platform • {{ config('app.url') }}
    </div>
</body>
</html>
