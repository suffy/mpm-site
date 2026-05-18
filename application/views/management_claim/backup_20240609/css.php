<style>
    
    *{
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        list-style: none;
        scroll-behavior: smooth;
    }

    .container-fluid{
        /* padding-left: 30px; */
    }

    table {
        border-collapse: collapse;
    }
    th{
        font-weight: bold;
        background-color: #f0f0f0;
        border: 0.5px solid #383838;
        color: #383838;
        align-items: center;
        align-content: center;
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #383838;
        font-size: 12px;
    }

    table.dataTable th,
    table.dataTable td {
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #007F73;
        border-radius: 10px;
        border: 1px solid #222831;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #647D87;
    }

    .btn-dark {
        color: #f0f0f0;
        background-color: #31363F;
        border-radius: 10px;
        border: 1px solid #222831;
    }

    .btn-dark:hover {
        color: #f0f0f0;
        background-color: #31363F;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-custom{
        background-color: white;
        color: black;
        border-radius: 5px;
        border: 2px solid red;
        /* margin-left: 1px; */
        /* margin-top: 20px; */
        padding: 2px;
        width: 10px;
        height: 10px;
    }

    .btn-ok{
        background-color: #525CEB;
        color: #f0f0f0;
        padding: 5px 5px 5px 5px;
        border-radius: 5px;
    }

    .btn-no{
        background-color: #647D87;
        color: #f0f0f0;
        padding: 5px 5px 5px 5px;
        border-radius: 5px;
    }

    .btn-ok:hover{
        /* background-color: #f0f0f0; */
        cursor: pointer;   
    }

    .btn-export {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
    }

    .btn-export:hover {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .btn-all {
        color: #f0f0f0;
        background-color: #6895D2;
        border-radius: 10px;
    }

    .btn-all:hover {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #FE7A36;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingprincipal {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-back{
        color: #f0f0f0;
        background-color: #9BB0C1;
        border-radius: 5px;
        border: 1px solid black;
        border-radius: 10px;
    }

    .btn-ikut{
        color: #000;
        background-color: #BED1CF;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-tidakikut{
        color: #fff;
        background-color: #3C3633;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-tidakikut:hover{
        color: black;
        background-color: #747264;
    }

    .btn-loading {
        color: #f0f0f0;
        background-color: #B4B4B8;
        border-radius: 10px;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
        border-radius: 10px;
        border: 2px solid black;
    }

    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        border-top: 3px solid darkslategray;
        border-bottom: 3px solid darkslategray;
        border-left: 3px solid darkslategray;
        border-right: 3px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 3px solid darkslategray;
    }
    
    .btn-submit-black{
        font-size: 16px;
        font-weight: 'bold';
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        padding: 10px 8px 10px 8px;
    }
    .btn-submit-black:hover{
        color: #fff;
        background: #1d1d1d;
    }

    .btn-submit-orange{
        /* padding: .5rem 2rem; */
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        background-color: #E8751A;
        color : #fff;
    }
    .btn-submit-orange:hover{
        color: #fff;
        background: #1d1d1d;
    }
    
    .btn-submit-red{
        /* padding: .5rem 2rem; */
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        background-color: #D20062;
        color : #fff;
    }
    .btn-submit-red:hover{
        color: #fff;
        background: #1d1d1d;
    }

    .btn-submit-blue{
        /* padding: .5rem 2rem; */
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        background-color: #6AD4DD;
        color : #fff;
    }
    .btn-submit-blue:hover{
        color: #fff;
        background: #1d1d1d;
    }

    .btn-submit-grey{
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        background-color: #F0EBE3;
        color : #1d1d1d;
    }
    .btn-submit-grey:hover{
        color: #fff;
        background: #1d1d1d;
    }

    .btn-submit-cream{
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        background-color: #FFF5E0;
        color : #1d1d1d;
    }
    .btn-submit-cream:hover{
        color: #fff;
        background: #B99470;
    }



    .nav-link-new{
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: .5s ease;
        text-decoration: none;
        color : #1d1d1d;
        padding: 5px 0px 5px 0px;
    }
    .nav-link-new:hover{
        color: #fff;
        background: #1d1d1d;
    }

    .btn-delete{
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        color: #f0f0f0;
        background-color: brown;
        padding: 5px 10px 5px 10px;
        
    }

    .btn-delete:hover{
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #1d1d1d;
        cursor: pointer;
        transition: .5s ease;
        color: #f0f0f0;
        background-color: #1d1d1d;
        padding: 5px 10px 5px 10px;
        
    }

    .title-square{
        /* background-color: #007F73; */
        background-color: #FDE49E;
        /* color: #f0f0f0; */
        color: #1d1d1d;
        padding: 10px 10px 10px 10px;
        border-radius: 5px;
        font-weight: 500;
        font-size: 20px;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.12), 1px 1px 2px rgba(0,0,0,0.24);
    }

    .title-square:hover{
        background-color: #1d1d1d;
        color: #f0f0f0;
    }

</style>