<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $title; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets_new/login/images/favicon.png">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets_new/login/css/style.css" rel="stylesheet">

    <script>
        // IMMEDIATE THEME SETTING
        (function() {
            try {
                const getStoredTheme = () => {
                    try {
                        return localStorage.getItem('theme');
                    } catch (e) {
                        return null;
                    }
                };
                
                const getPreferredTheme = () => {
                    const storedTheme = getStoredTheme();
                    if (storedTheme === 'dark' || storedTheme === 'light') {
                        return storedTheme;
                    }
                    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                };
                
                const theme = getPreferredTheme();
                document.documentElement.setAttribute('data-bs-theme', theme);
                
                if (!getStoredTheme()) {
                    try {
                        localStorage.setItem('theme', theme);
                    } catch (e) {}
                }
            } catch (e) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        })();
    </script>

    <style>
        /* Reset CSS */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--bs-body-bg, #000);
            color: var(--bs-body-color, #fff);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: auto;
        }

        .login-form {
            background-color: var(--bs-body-bg, #222);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--bs-border-color, #444);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .login-form h4 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            color: var(--bs-body-color, #fff);
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form input {
            width: 100%;
            padding: 12px;
            border: none;
            border-bottom: 2px solid var(--bs-border-color, #555);
            background: transparent;
            color: var(--bs-body-color, #fff);
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .login-form input:focus {
            outline: none;
            border-bottom-color: var(--bs-primary, #0d6efd);
        }

        .login-form input::placeholder {
            color: var(--bs-secondary-color, #999);
        }

        .login-form button {
            width: 100%;
            background-color: var(--bs-primary, #555);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color: var(--bs-primary-dark, #0b5ed7);
        }

        .login-form button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Password wrapper dengan icon Bootstrap yang lebih simple */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 1.2rem;
            color: var(--bs-secondary-color, #888);
            transition: color 0.2s;
            line-height: 1;
            padding: 5px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--bs-body-color, #fff);
        }

        /* Theme toggle button */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: 1px solid var(--bs-border-color, #444);
            color: var(--bs-body-color, #fff);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            z-index: 1000;
        }

        .theme-toggle:hover {
            background-color: var(--bs-dark-bg-subtle, #333);
            transform: translateY(-2px);
        }

        .theme-toggle i {
            font-size: 16px;
        }

        /* Text utilities */
        .text-center { text-align: center; }
        .w-100 { width: 100%; }
        .mt-3 { margin-top: 15px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-2 { margin-top: 10px; }
    </style>
    
</head>

<body>
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" onclick="toggleTheme()" id="themeToggle">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
        <span id="themeText">Dark Mode</span>
    </button>

    <div class="login-container">
        <div class="login-form">
            <h4>Login Site</h4>

            <?php 
            if($this->session->flashdata('pesan')){ ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            } elseif($this->session->flashdata('pesan_success')){ ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
            ?>

            <?php 
            $attributes = array('class' => 'mt-3 mb-3');
            echo form_open($url, $attributes);
            ?>

            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="username" id="username" required>
            </div>
            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                    <!-- Icon show password menggunakan Bootstrap Icons yang lebih simple -->
                    <span class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="bi bi-eye" id="passwordIcon"></i>
                    </span>
                </div>
            </div>
            <button class="btn" id="btnKirim" onclick="button()">Login</button>
            <button class="btn" id="btnLoading" type="button" disabled style="display: none;">
                ... Please wait ...
            </button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ==================== LOGIN BUTTON FUNCTION ====================
        var btnLoading = document.getElementById('btnLoading');
        var btnKirim = document.getElementById('btnKirim');

        function button() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            
            if (username && password) {
                btnKirim.style.display = 'none';
                btnLoading.style.display = 'block';
            }
        }

        // ==================== SHOW PASSWORD FUNCTION dengan Bootstrap Icons ====================
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ganti icon ke eye-slash (mata tertutup) saat password terlihat
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                // Ganti icon ke eye (mata terbuka) saat password tersembunyi
                passwordIcon.className = 'bi bi-eye';
            }
        }

        // ==================== DARK MODE FUNCTIONS ====================
        
        function updateThemeButton(theme) {
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-moon-fill';
                themeText.textContent = 'Dark Mode';
            } else {
                themeIcon.className = 'bi bi-sun-fill';
                themeText.textContent = 'Light Mode';
            }
        }

        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            try {
                localStorage.setItem('theme', theme);
            } catch (e) {}
            updateThemeButton(theme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            updateThemeButton(currentTheme);
            
            // Set initial password icon
            const passwordIcon = document.getElementById('passwordIcon');
            if (passwordIcon) {
                passwordIcon.className = 'bi bi-eye';
            }
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            try {
                const storedTheme = localStorage.getItem('theme');
                if (!storedTheme) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    setTheme(newTheme);
                }
            } catch (e) {}
        });
    </script>

    <!-- Debugging: Tampilkan theme saat ini (hapus di production) -->
    <div style="position: fixed; bottom: 10px; left: 10px; background: rgba(0,0,0,0.5); color: white; padding: 5px; border-radius: 3px; font-size: 12px; z-index: 9999;">
        Theme: <span id="themeDebug">dark</span>
    </div>
    <script>
        document.getElementById('themeDebug').textContent = document.documentElement.getAttribute('data-bs-theme');
    </script>

</body>
</html>