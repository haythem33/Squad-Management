<!DOCTYPE html>
<html>
<head>
    <title>Match Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .details {
            margin-bottom: 20px;
            background-color: #f7fafc;
            padding: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #edf2f7;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Match Report</h1>

        <div class="details">
            <p><strong>Opponent:</strong> {{ $match->opponent }}</p>
            <p><strong>Date:</strong> {{ $match->match_date->format('d M Y') }}</p>
        </div>

        <h3>Lineup Stats</h3>
        <table>
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Goals</th>
                    <th>Minutes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($match->players as $player)
                <tr>
                    <td>{{ $player->name }}</td>
                    <td>{{ $player->pivot->goals }}</td>
                    <td>{{ $player->pivot->minutes_played }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Squad Management System</p>
        </div>
    </div>
</body>
</html>
