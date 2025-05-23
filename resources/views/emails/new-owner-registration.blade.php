@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #1a56db; margin-bottom: 10px; font-size: 24px;">🏥 New Owner Registration Alert</h1>
    <p style="color: #4a5568; margin: 0;">{{ $newOwner->created_at->format('F d, Y - h:i A') }}</p>
</div>

<div style="background-color: #f7fafc; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
    <div style="margin-bottom: 5px; color: #2d3748; font-size: 16px;">Registration Status:</div>
    <div style="display: flex; align-items: center; justify-content: center; padding: 10px;">
        @if($newOwner->status == 0)
            <span style="background-color: #fef3c7; color: #92400e; padding: 8px 16px; border-radius: 9999px; font-weight: 500;">
                🔸 Pending Approval
            </span>
        @else
            <span style="background-color: #def7ec; color: #03543f; padding: 8px 16px; border-radius: 9999px; font-weight: 500;">
                🔹 Active
            </span>
        @endif
    </div>
</div>

<div style="margin-bottom: 25px;">
    <h2 style="color: #2d3748; font-size: 18px; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0;">
        👤 Owner Information
    </h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #718096; width: 140px;">Full Name</td>
            <td style="padding: 8px 0; color: #2d3748; font-weight: 500;">{{ $newOwner->complete_name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #718096;">Email</td>
            <td style="padding: 8px 0; color: #2d3748;">{{ $newOwner->email }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #718096;">Contact</td>
            <td style="padding: 8px 0; color: #2d3748;">+63 {{ $newOwner->contact_no }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #718096;">Gender</td>
            <td style="padding: 8px 0; color: #2d3748;">{{ $newOwner->gender }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #718096;">Civil Status</td>
            <td style="padding: 8px 0; color: #2d3748;">{{ $newOwner->owner->civil_status }}</td>
        </tr>
    </table>
</div>

<div style="margin-bottom: 25px;">
    <h2 style="color: #2d3748; font-size: 18px; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0;">
        📍 Address Details
    </h2>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #718096; width: 140px;">Barangay</td>
            <td style="padding: 8px 0; color: #2d3748; font-weight: 500;">{{ $newOwner->address->barangay->barangay_name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #718096;">Street</td>
            <td style="padding: 8px 0; color: #2d3748;">{{ $newOwner->address->street }}</td>
        </tr>
    </table>
</div>

@component('mail::panel')
<div style="text-align: center; color: #4a5568;">
    <p style="margin-bottom: 15px;">This registration requires your review and approval.</p>
    <p style="margin: 0;">Please access the admin dashboard to process this request.</p>
</div>
@endcomponent

@component('mail::button', ['url' => route('admin-owners'), 'color' => 'success'])
Review Registration
@endcomponent

<div style="margin-top: 30px; text-align: center; color: #718096;">
    <p style="margin-bottom: 10px;">Best regards,<br>{{ config('app.name') }} Team</p>
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
        <small style="color: #a0aec0; font-size: 12px;">
            This is an automated message. Please do not reply to this email.<br>
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </small>
    </div>
</div>
@endcomponent