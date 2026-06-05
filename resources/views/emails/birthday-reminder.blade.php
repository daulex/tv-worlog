<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Birthdays</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; padding: 24px;">
        <tr>
            <td>
                <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 24px 0;">Upcoming Birthdays</h1>

                @if(empty($birthdays))
                    <p style="color: #6b7280; font-size: 14px;">No upcoming birthdays in the next year.</p>
                @else
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e5e7eb; overflow: hidden;">
                        @foreach($birthdays as $birthday)
                            <tr>
                                <td style="padding: 16px 24px; @if(!$loop->last) border-bottom: 1px solid #e5e7eb; @endif">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <p style="font-weight: 600; color: #111827; margin: 0; font-size: 16px;">
                                                    <a href="{{ $url ?? '#' }}/people/{{ $birthday['id'] }}" style="color: #111827; text-decoration: underline;">{{ $birthday['name'] }}</a>
                                                </p>
                                                <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 14px;">{{ $birthday['date_of_birth'] }}</p>
                                            </td>
                                            <td style="text-align: right; vertical-align: top;">
                                                <p style="margin: 0; font-size: 14px; color:
                                                    @if($birthday['days_text'] === 'today') #16a34a; font-weight: 600;
                                                    @elseif($birthday['days_text'] === 'tomorrow') #2563eb; font-weight: 600;
                                                    @else #6b7280;
                                                    @endif">
                                                    {{ $birthday['days_text'] }}
                                                </p>
                                                <p style="margin: 4px 0 0 0; font-size: 14px; font-weight: 500; color: #111827;">
                                                    {{ $birthday['age'] }} years old
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
