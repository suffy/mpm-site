<style>
:root {
        --primary: #ff6900;
        --secondary: #fbb375;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container-fluid{
        /* padding-left: 30px; */
    }

    .header {
        text-align: center;
        margin-bottom: 60px;
    }

    .header h1 {
        font-size: 3rem;
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .header p {
        color: rgba(255,255,255,0.8);
        font-size: 1.2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 40px;
        margin-bottom: 40px;
    }

    .form-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #fbb375, #ff6900, #ff6900);
    }

    .form-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 80px rgba(0,0,0,0.15);
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
        background: #ff6900;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .form-subtitle {
        color: #718096;
        margin-bottom: 30px;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* biop Form Style */
    .biop-form .input-group {
        position: relative;
        margin-bottom: 25px;
    }

    .biop-form .input-field {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .biop-form .input-field:focus {
        border-color: #fbb375;
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .biop-form .input-label {
        position: absolute;
        left: 20px;
        top: 16px;
        color: #a0aec0;
        transition: all 0.3s ease;
        pointer-events: none;
        background: white;
        padding: 0 8px;
    }

    .biop-form .input-field:focus + .input-label,
    .biop-form .input-field:not(:placeholder-shown) + .input-label {
        transform: translateY(-32px) scale(0.85);
        color: #fbb375;
        font-weight: 600;
    }

    .biop-form .input-field-sm {
        width: 100%;
        padding: 6px 10px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .biop-form .input-field-sm:focus {
        border-color: #fbb375;
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .biop-form .input-label-sm {
        position: absolute;
        left: 10px;
        top: 6px;
        color: #a0aec0;
        transition: all 0.3s ease;
        pointer-events: none;
        background: white;
        padding: 0 8px;
    }

    .biop-form .input-field-sm:focus + .input-label-sm,
    .biop-form .input-field-sm:not(:placeholder-shown) + .input-label-sm {
        transform: translateY(-32px) scale(0.85);
        color: #fbb375;
        font-weight: 600;
    }

    .biop-form textarea {
        resize: vertical;
        min-height: 80px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        font-size: 0.9em;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        /* background-color: var(--bs-dark-bg-subtle); */
        border-radius: 8px;
        overflow: hidden;
    }

    table thead tr {
        background-color: #ff6900;
        color: white;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #FBF6E9;
    }

    table th,
    table td {
        padding: 12px 15px;
        text-align: left;
        /* border: 0.7px solid #edf2f7; */
        border: 0.7px solid black;
        border-radius: 1px;
        opacity: 0.8;
    }

    td:hover{
        transform: scale(1.1);
        background-color: var(--secondary);
        text-align: center!important;
    }
    
    th:hover{
        transform: scale(1.1);
        background-color: var(--secondary);
        text-align: center!important;
    }

    table.dataTable th,
    table.dataTable td {
    }

    /* Button Styles */
    .btn-primary {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #fbb375 0%, #ff6900 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        width: 100%;
        padding: 16px;
        background: white;
        color: #fbb375;
        border: 2px solid #fbb375;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
    }

    .btn-secondary:hover {
        background: #fbb375;
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary-primary {
        background: linear-gradient(to right, #fbb375, #ff6900);
        color: white;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-status-pending {
        background-color: rgba(248, 150, 30, 0.2);
        color: #f8961e;
    }

    .btn-submit-primary{
        font-size: 16px;
        font-weight: 'bold';
        border-radius: 6px;
        /* border: 1px solid #1d1d1d; */
        border: 0.5px solid var(--bs-dark-bg-subtle);
        border-color: var(--bs-dark-text-emphasis);
        cursor: pointer;
        transition: .5s ease;
        padding: 10px 8px 10px 8px;
    }
    .btn-submit-primary:hover{
        color: #f0f0f0;
        border-color: var(--bs-dark-text-emphasis);
        background-color: var(--primary);
        border: 0.5px solid var(--bs-dark);
    }


    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .registration-form .form-row {
            grid-template-columns: 1fr;
        }
        
        .newsletter-form .email-input {
            flex-direction: column;
        }
        
        .header h1 {
            font-size: 2rem;
        }
    }

    .success-animation {
        text-align: center;
        padding: 40px;
        color: #48bb78;
    }

    .success-animation .checkmark {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 3px solid #48bb78;
        margin: 0 auto 20px;
        position: relative;
        animation: checkmark-bounce 0.6s ease;
    }

    .success-animation .checkmark::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 20px;
        width: 12px;
        height: 20px;
        border: solid #48bb78;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }

    @keyframes checkmark-bounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

</style>