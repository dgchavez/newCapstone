<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>User Credentials</title>
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
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .credentials {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .value {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>User Credentials</h2>
            <p>Valencia Veterinarians Office</p>
        </div>
        
        <p>Hello <strong>{{ $userName }}</strong>,</p>
        <p>Your account has been created successfully. Below are your login credentials:</p>
        
        <div class="credentials">
            <div class="label">{{ $isEmail ? 'Email' : 'Username' }}:</div>
            <div class="value">{{ $username }}</div>
            
            <div class="label">Password:</div>
            <div class="value">{{ $password }}</div>
        </div>
        
        <p>For security reasons, we recommend changing your password after your first login.</p>
        
        <div class="footer">
            <p>Generated on {{ date('F d, Y') }}</p>
            <p>© {{ date('Y') }} Valencia Veterinarians Office. All rights reserved.</p>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:init', () => {
            // Print credentials handler
            Livewire.on('print-credentials', () => {
                const printContents = document.getElementById('credentials-printable').innerHTML;
                const originalContents = document.body.innerHTML;
                
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                
                // Re-initialize Livewire after printing
                Livewire.rescan();
            });
            
            // Download PDF handler
            Livewire.on('download-pdf', (url) => {
                // Create a temporary anchor element
                const link = document.createElement('a');
                link.href = url;
                link.download = 'user_credentials.pdf';
                link.target = '_blank';
                
                // Trigger the download
                document.body.appendChild(link);
                link.click();
                
                // Clean up
                setTimeout(() => {
                    document.body.removeChild(link);
                    // After some time, delete the file from server
                    fetch(url, { method: 'DELETE' });
                }, 100);
            });
        });
    </script>
</body>
</html>