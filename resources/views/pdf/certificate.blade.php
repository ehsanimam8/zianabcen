<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; text-align: center; margin: 0; padding: 0; background-color: #fafafa; }
        .certificate-container { width: 90%; max-width: 1000px; margin: 40px auto; padding: 60px; background-color: white; border: 15px solid #5d0080; outline: 3px solid #ca9bdd; outline-offset: -10px; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { margin-bottom: 50px; }
        .org-name { font-size: 36px; font-weight: bold; color: #5d0080; text-transform: uppercase; letter-spacing: 4px; }
        .cert-title { font-size: 48px; font-weight: 300; margin: 30px 0; color: #333; font-family: 'Georgia', serif; font-style: italic; }
        .presents { font-size: 16px; color: #777; text-transform: uppercase; letter-spacing: 2px; }
        .student-name { font-size: 56px; font-weight: bold; color: #5d0080; border-bottom: 2px solid #ddd; display: inline-block; padding: 0 40px 10px; margin: 30px 0; }
        .reason { font-size: 18px; color: #555; line-height: 1.6; margin: 0 auto 50px; }
        .entity-name { font-size: 28px; font-weight: bold; color: #333; margin-top: 15px; display: block; }
        .signatures { margin-top: 80px; width: 100%; display: table; }
        .signature-block { display: table-cell; width: 50%; text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 250px; margin: 0 auto; padding-top: 5px; font-size: 14px; font-weight: bold; }
        .date { font-size: 14px; color: #777; margin-top: 30px; }
        .cert-id { position: absolute; bottom: 20px; right: 30px; font-size: 10px; color: #bbb; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="certificate-container">
        
        <div class="header">
            <div class="org-name">{{ $tenant->name ?? 'Zainab Center' }}</div>
        </div>

        <div class="presents">Hereby Presents This</div>
        
        <div class="cert-title">Certificate of Completion</div>
        
        <div class="presents">To</div>

        <div class="student-name">{{ $student->name }}</div>

        <div class="reason">
            For successfully completing all requirements of the {{ $type === 'program' ? 'Program' : 'Course' }}:
            <span class="entity-name">{{ $entity->name }}</span>
        </div>

        <div class="signatures">
            <div class="signature-block">
                <div class="date">{{ now()->format('F jS, Y') }}</div>
                <div class="signature-line">Date Issued</div>
            </div>
            <div class="signature-block">
                <br><br>
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
        
        <div class="cert-id">
            No: CERT-{{ date('Y') }}-{{ strtoupper(\Illuminate\Support\Str::random(10)) }}<br>
            Student ID: {{ $student->roll_number ?? 'N/A' }}
        </div>
    </div>
</body>
</html>
