<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
        }

        .page-title span {
            color: #2563eb;
        }

        .slot-section {
            margin-bottom: 20px;
            background-color: #f9fafb;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
        }

        .slot-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        th, td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        th {
            background-color: #dbeafe;
            font-weight: bold;
            color: #000000;
        }

        .download-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #10b981;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Title -->
        <h2 class="page-title">
            Registrations for <span>{{ $eventName }}</span> ({{ ucfirst($mode) }})
        </h2>

        <!-- Slot Sections -->
        <div>
            @foreach($slots as $index => $slot)
                <div class="slot-section">
                    <h3 class="slot-title">
                        Slot {{ $index + 1 }}
                    </h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slot as $i => $reg)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $reg->name }}</td>
                                    <td>{{ $reg->email }}</td>
                                    <td>{{ $reg->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <!-- Download PDF Button -->
        @if (!request()->is('admin/export/pdf'))
    <div class="text-center mt-10">
    <a href="{{ route('admin.export.pdf', ['event_id' => $eventId, 'mode' => $mode, 'slot_size' => request('slot_size')]) }}" 
   style="
        display: inline-block;
        padding: 12px 20px;
        background-color: #10b981;
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease-in-out;
   "
   onmouseover="this.style.backgroundColor='#059669'"
   onmouseout="this.style.backgroundColor='#10b981'">
    📄 Download PDF
</a>

    </div>
@endif
</body>
</html>