<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sovie — Akses Dibatasi (403)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(0, 62, 168, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.03) 0px, transparent 50%),
                linear-gradient(rgba(0, 0, 0, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.01) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 20px 20px, 20px 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        .error-card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 500px;
            text-align: center;
            padding: 48px 32px;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.01);
            animation: cardSlideIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
            overflow: hidden;
        }
        .error-card-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #003ea8, #3b82f6);
        }
        .icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
            margin-bottom: 24px;
        }
        .pulse-circle {
            position: absolute;
            width: 80px;
            height: 80px;
            background-color: rgba(0, 62, 168, 0.08);
            border-radius: 50%;
            animation: pulse-ring 2.5s infinite ease-in-out;
        }
        .pulse-circle-inner {
            position: absolute;
            width: 60px;
            height: 60px;
            background-color: rgba(59, 130, 246, 0.05);
            border-radius: 50%;
            animation: pulse-ring 2.5s infinite ease-in-out 0.8s;
        }
        .floating-icon {
            font-size: 54px;
            color: #003ea8;
            z-index: 2;
            filter: drop-shadow(0 4px 8px rgba(0, 62, 168, 0.2));
            animation: float-motion 3.5s ease-in-out infinite;
        }
        .error-title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
        }
        .error-desc {
            color: #64748b;
            margin: 0 0 28px 0;
            font-size: 14px;
            line-height: 1.6;
        }
        .action-btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            width: 100%;
        }
        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #003ea8 0%, #00225c 100%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid transparent;
            cursor: pointer;
            flex: 1;
            min-width: 160px;
        }
        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
            filter: brightness(1.1);
        }
        .btn-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #ffffff;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            flex: 1;
            min-width: 160px;
        }
        .btn-action-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }
        @keyframes cardSlideIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes float-motion {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.3); opacity: 0.1; }
            100% { transform: scale(0.9); opacity: 0.6; }
        }
        @keyframes float-slow {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -60px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }
    </style>
</head>
<body>
    <!-- Floating Blur Elements for Background -->
    <div style="position: absolute; width: 300px; height: 300px; background: rgba(59, 130, 246, 0.08); filter: blur(100px); border-radius: 50%; top: -10%; left: -10%; z-index: 0; pointer-events: none; animation: float-slow 15s ease-in-out infinite;"></div>
    <div style="position: absolute; width: 400px; height: 400px; background: rgba(0, 62, 168, 0.05); filter: blur(120px); border-radius: 50%; bottom: -10%; right: -10%; z-index: 0; pointer-events: none; animation: float-slow 20s ease-in-out infinite 2s;"></div>

    <div class="error-card-container" style="z-index: 1;">
        <div class="icon-wrapper">
            <div class="pulse-circle"></div>
            <div class="pulse-circle-inner"></div>
            
            <!-- Inline SVG Shield Warning Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="floating-icon">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2.5"/>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2.5"/>
            </svg>
        </div>
        <h2 class="error-title">Akses Dibatasi (RBAC)</h2>
        <p class="error-desc">Maaf, akun Anda tidak memiliki izin yang cukup untuk mengakses halaman ini. Silakan hubungi Super Admin Anda untuk mengubah konfigurasi hak akses akun Anda.</p>
        <div class="action-btn-group">
            <a href="<?php echo e(route('dashboard')); ?>" class="btn-action-primary">
                <i class="ph ph-house"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            <a href="mailto:admin@sovie.com?subject=Permintaan%20Akses%20Halaman%20Sovie" class="btn-action-secondary">
                <i class="ph ph-envelope-simple"></i>
                <span>Hubungi Admin</span>
            </a>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\magang\InsightHubBaru\frontend/resources/views/errors/403.blade.php ENDPATH**/ ?>