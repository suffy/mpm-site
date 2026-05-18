<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deltomed Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #2980b9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Section styling */
        .section {
            min-height: 100vh;
            padding: 80px 20px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .section-1 {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .section-2 {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .section-3 {
            background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%);
        }

        .services-grid {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            background: white;
        }

        .services-grid:hover {
            transform: translateY(-5px);
        }

        .service-box {
            padding: 50px 30px;
            min-height: 220px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .service-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-box:hover::before {
            opacity: 1;
        }

        .service-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .service-box a {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
            color: inherit;
        }

        .service-box.web-design {
            background-color: #e3f2fd;
            border-bottom: 4px solid #4a90e2;
        }

        .service-box.development {
            background-color: #fff8e1;
            border-bottom: 4px solid #f39c12;
        }

        .service-box.marketing {
            background-color: #e8f5e9;
            border-bottom: 4px solid #27ae60;
        }

        .service-box.support {
            background-color: #fce4ec;
            border-bottom: 4px solid #e74c3c;
        }

        .service-box.management {
            background-color: #e3f2fd;
            border-bottom: 4px solid #2980b9;
        }

        .service-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
            stroke-width: 1.5;
            transition: transform 0.3s ease;
        }

        .service-box:hover .service-icon {
            transform: scale(1.1);
        }

        .web-design .service-icon {
            color: #2980b9;
        }

        .development .service-icon {
            color: #d35400;
        }

        .marketing .service-icon {
            color: #27ae60;
        }

        .support .service-icon {
            color: #c0392b;
        }

        .management .service-icon {
            color: #16a085;
        }

        .service-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 10px 0;
            transition: color 0.3s ease;
        }

        .service-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 0;
        }

        .web-design .service-title {
            color: #2980b9;
        }

        .development .service-title {
            color: #d35400;
        }

        .marketing .service-title {
            color: #27ae60;
        }

        .support .service-title {
            color: #c0392b;
        }

        .management .service-title {
            color: #16a085;
        }

        .main-title {
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .main-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            margin: 20px 0;
            border-radius: 2px;
        }

        .main-title .highlight {
            color: var(--accent-color);
            font-weight: 800;
        }

        .description {
            color: #666;
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .section-2 .description {
            color: #bdc3c7;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-custom i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .btn-custom:hover i {
            transform: translateX(3px);
        }

        .btn-primary-custom {
            background-color: var(--accent-color);
            color: white;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-primary-custom:hover {
            background-color: #c0392b;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        }

        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
        }

        .btn-secondary-custom:hover {
            background-color: var(--accent-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        .content-section {
            padding: 0 30px;
            position: relative;
        }

        /* Stats section */
        .stats-grid {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .stats-grid:hover {
            transform: translateY(-5px);
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
        }

        .stat-item:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-item a {
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .stat-label {
            font-size: 1rem;
            font-weight: 600;
            color: #bdc3c7;
        }

        .stat-item:hover .stat-label {
            color: white;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            cursor: pointer;
            animation: bounce 2s infinite;
            z-index: 10;
        }

        .scroll-arrow {
            width: 40px;
            height: 40px;
            color: var(--accent-color);
            transition: transform 0.3s ease;
        }

        .scroll-indicator:hover .scroll-arrow {
            transform: translateY(5px);
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-15px);
            }
            60% {
                transform: translateX(-50%) translateY(-7px);
            }
        }

        /* Navigation dots */
        .nav-dots {
            position: fixed;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-dot:hover, .nav-dot.active {
            background-color: var(--accent-color);
            transform: scale(1.3);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-title {
                font-size: 2.2rem;
            }
            
            .service-box {
                padding: 40px 20px;
            }
        }

        @media (max-width: 768px) {
            .section {
                padding: 60px 15px;
            }
            
            .main-title {
                font-size: 2rem;
            }
            
            .content-section {
                padding: 0;
                margin-bottom: 40px;
            }
            
            .service-box {
                padding: 30px 15px;
                min-height: 180px;
            }
            
            .service-icon {
                width: 50px;
                height: 50px;
            }
            
            .service-title {
                font-size: 16px;
            }

            .service-desc {
                font-size: 13px;
            }
            
            .stats-grid {
                padding: 20px;
            }
            
            .nav-dots {
                right: 15px;
            }
        }

        @media (max-width: 576px) {
            .main-title {
                font-size: 1.8rem;
            }
            
            .description {
                font-size: 1rem;
            }
            
            .btn-custom {
                padding: 10px 20px;
                font-size: 13px;
            }

            .service-box {
                min-height: 160px;
            }
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.8s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-in-out forwards;
            opacity: 0;
            transform: translateX(-30px);
        }

        @keyframes slideInLeft {
            to { opacity: 1; transform: translateX(0); }
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-in-out forwards;
            opacity: 0;
            transform: translateX(30px);
        }

        @keyframes slideInRight {
            to { opacity: 1; transform: translateX(0); }
        }

        .delay-1 {
            animation-delay: 0.2s;
        }
        .delay-2 {
            animation-delay: 0.4s;
        }
        .delay-3 {
            animation-delay: 0.6s;
        }

        .service-icon-container {
            position: relative;
        }
        
        .service-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
        
        .service-stats {
            margin-top: 10px;
        }
        
        .service-progress {
            width: 80%;
        }
        
        .badge {
            font-weight: 500;
            padding: 4px 8px;
        }
        
        #quickStatsPanel {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navigation Dots -->
    <div class="nav-dots">
        <div class="nav-dot active" onclick="scrollToSection('gt-section')"></div>
        <div class="nav-dot" onclick="scrollToSection('mt-section')"></div>
        <div class="nav-dot" onclick="scrollToSection('stats-section')"></div>
    </div>