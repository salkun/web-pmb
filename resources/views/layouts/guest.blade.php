<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-dark: #011E41;
            --primary-blue: #0082CB;
            --primary-light: #87D1E6;
            --accent-color: #10B981;
        }

        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: var(--primary-dark);
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2rem 1rem;
        }

        /* Animated Background Elements */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 20s infinite alternate;
        }

        .shape-1 {
            width: 500px;
            height: 500px;
            background: var(--primary-blue);
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            width: 400px;
            height: 400px;
            background: var(--primary-light);
            bottom: -50px;
            left: -50px;
            animation-delay: -5s;
        }

        .shape-3 {
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            top: 40%;
            left: 10%;
            opacity: 0.15;
            animation-delay: -10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(50px, 50px) rotate(15deg); }
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            height: 250px;
            object-fit: contain;
            margin-bottom: 0.75rem;
        }

        .auth-logo h2 {
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .auth-logo p {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .form-label {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group-custom i {
            position: absolute;
            left: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: all 0.3s;
            z-index: 5;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s;
            color: var(--primary-dark);
        }

        .form-control-custom:focus {
            background: white;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(0, 130, 203, 0.1);
            outline: none;
        }

        .form-control-custom:focus + i {
            color: var(--primary-blue);
        }

        .btn-auth {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(0, 130, 203, 0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(0, 130, 203, 0.4);
            filter: brightness(1.1);
            color: white;
            text-decoration: none;
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .footer-links {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* SweetAlert Custom */
        .swal2-popup {
            border-radius: 20px !important;
            font-family: 'Inter', sans-serif !important;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <!-- Animated Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="auth-container">
        <div class="auth-card fade-in">
            <div class="auth-logo">
                <div style="background: var(--primary-dark); padding: ; border-radius: 16px; margin-bottom: 1rem;">
                    <img src="{{ asset('logo pmb putih.png') }}" alt="Logo PMB">
                </div>
            </div>

            @yield('content')

            <div class="footer-links">
                &copy; {{ date('Y') }} PMB Teknik Radiologi Pencitraan. All rights reserved.
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: "{{ session('error') }}",
            confirmButtonColor: '#0082CB'
        });
    @endif
</script>

@stack('scripts')
</body>
</html>
