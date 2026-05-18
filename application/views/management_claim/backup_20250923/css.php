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
      /* background-color: #FBF6E9; */
      background-color: var(--bs-dark-border-subtle);
      /* color: #2d3436; */
      color: var(--bs-dark-text-emphasis);
      text-align: left;
      font-weight: 600;
      border-bottom: 2px solid #FBF6E9;
    }

    table th,
    table td {
      padding: 12px 15px;
      text-align: left;
      /* border: 0.7px solid #edf2f7; */
      border: 0.7px solid var(--bs-dark-border-subtle);
      border-radius: 1px;
      opacity: 0.8;
    }

    /* th{
        font-weight: bold;
        background-color: #FBF6E9;
        border: 0.1px solid #383838;
        color: #383838;
        align-items: center;
        align-content: center;
        font-size: 13px;
        text-align: center;
        font-weight: 500;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #383838;
        font-size: 12px;
    } */

    td:hover{
      transform: scale(1.01);
      /* background-color: #FBF6E9; */
      background-color: var(--bs-dark-border-subtle);
    }

    th:hover{
      transform: scale(1.01);
      /* background-color: #FBF6E9; */
      background-color: var(--bs-dark-bg-subtle);
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
        /* border: 1px solid #1d1d1d; */
        border: 0.5px solid var(--bs-dark-bg-subtle);
        border-color: var(--bs-dark-text-emphasis);
        cursor: pointer;
        transition: .5s ease;
        padding: 10px 8px 10px 8px;
    }
    .btn-submit-black:hover{
        /* color: #fff;
        background: #1d1d1d; */
        color: var(--bs-dark-text-emphasis);
        border-color: var(--bs-dark-text-emphasis);
        background-color: var(--bs-dark-border-subtle);
        border: 0.5px solid var(--bs-dark);
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
        /* color : #1d1d1d; */
        color : var(--bs-dark-text-emphasis);
        padding: 5px 0px 5px 0px;
    }
    .nav-link-new:hover{
        /* color: #fff; */
        color: var(--bs-dark-text-emphasis);
        /* background: #1d1d1d; */
        /* background: var(--bs-dark); */
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
        /* background-color: #000; */
        /* color: #f0f0f0; */
        color: #1d1d1d;
        padding: 10px 10px 10px 10px;
        border-radius: 5px;
        font-weight: 500;
        font-size: 20px;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.12), 1px 1px 2px rgba(0,0,0,0.24);
        border-width: 1px;
        border-style: solid;
        border-color: '#1d1d1d';
        border-radius: 5px;
    }

    /* .title-square:hover{
        background-color: #1d1d1d;
        color: #f0f0f0;
    } */

    .pending-scm { 
        background-color: #e6f7ed; 
        color: #1f9254;  
        /* font-weight: 600;
        font-size: 16px; */
    }
    .pending-finance { background-color: #fbe7e8; color: #d11a2a; }
    .pending-rilis-po { background-color: #e3f1fc; color: #2b7cc0; }
    .finish { background-color: #698474; color: #fff; }
    .status {
  padding: 8px 10px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: bold;
  border: none;
}

.delete-button {
  background-color: #ff4d4d;
  color: white;
  border: none;
  padding: 10px 15px;
  font-size: 11px;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.delete-button:hover {
  background-color: #ff3333;
  box-shadow: 0 4px 8px rgba(255, 77, 77, 0.5);
  transform: translateY(-2px);
}

.delete-button:active {
  transform: translateY(0);
  box-shadow: 0 1px 3px rgba(255, 77, 77, 0.4);
}

.delete-button::before {
  content: "\2716"; /* Unicode untuk simbol X */
  margin-right: 8px;
  font-size: 13px;
}

/* Tambahan: Animasi saat tombol dihover */
@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.delete-button:hover {
  animation: pulse 0.8s infinite;
}

.send-email-button {
  background-color: #4CAF50; /* Warna hijau untuk email */
  color: white;
  border: none;
  padding: 10px 15px;
  font-size: 14px;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(76, 175, 80, 0.3);
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.send-email-button:hover {
  background-color: #45a049;
  box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5);
  transform: translateY(-2px);
}

.send-email-button:active {
  transform: translateY(0);
  box-shadow: 0 1px 3px rgba(76, 175, 80, 0.4);
}

.send-email-button::before {
  content: "\2709"; /* Unicode untuk simbol amplop */
  margin-right: 8px;
  font-size: 14px;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.send-email-button:hover {
  animation: pulse 0.8s infinite;
}


  

.main {
  flex: 1;
  padding: 1px 1px 1px 1px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.widget {
  /* background-color: #fff; */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 15px;
  margin-bottom: 15px;
  width: calc(33% - 20px);
  border-radius: 10px;
  transition: transform 0.3s ease;
}

.widget h3 {
  margin-bottom: 10px;
}

.metric {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.metric .value {
  font-size: 14px;
  font-weight: bold;
}

.metric .value {
  transition: transform 0.3s ease;
}

.metric .value:hover {
  transform: scale(1.5);
}

.metric .label:hover {
  transform: scale(1.5);
}

* Warna pastel untuk widget */
.widget-pastel-1 {
  background-color: #d0e3f0; /* Biru muda pucat */
}

.widget-pastel-2 {
  background-color: #c2f0f0; /* Hijau mint */
}

.widget-pastel-3 {
  background-color: #f5d0d3; /* Pink muda */
}

/* Aplikasikan class ke widget */
.widget:nth-child(1) {
  /* background-color: #d0e3f0;  */
  background-color: var(--bs-dark-border-subtle); 
}

.widget:nth-child(1):hover {
  transform: scale(1.05);
}

.widget:nth-child(2) {
  /* background-color: #c2f0f0;  */
  background-color: var(--bs-dark-bg-subtle); 
}

.widget:nth-child(2):hover {
  transform: scale(1.05);
}

.widget:nth-child(3) {
  /* background-color: #f5d0d3; */
  background-color: var(--bs-dark-border-subtle); 
}

.widget:nth-child(3):hover {
  transform: scale(1.05);
}

.product-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.product-list li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}
  
.export-excel-btn {
  display: inline-flex;
  align-items: center;
  padding: 10px 15px;
  background-color: #a8d5ba; /* Warna latar pastel hijau */
  color: #2c3e50; /* Warna teks lebih gelap untuk kontras */
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.export-excel-btn:hover {
  background-color: #8fc1a9; /* Warna hover yang sedikit lebih gelap */
  color: #34495e; /* Warna teks saat hover */
  transform: scale(1.2);
}

.export-excel-btn:before {
  content: "\f1c3";
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  margin-right: 8px;
}

.pastel-orange-btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #FFB347; /* Warna pastel oranye */
  color: #5A4E4E; /* Warna teks coklat gelap untuk kontras */
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pastel-orange-btn:hover {
  background-color: #FFA726; /* Warna oranye sedikit lebih gelap saat hover */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  transform: scale(1.2);  
}

.pastel-orange-btn:active {
  background-color: #FF9800; /* Warna lebih gelap saat ditekan */
  transform: translateY(1px);
}


.pastel-btn {
  display: inline-block;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pastel-btn:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.pastel-btn:active {
  transform: translateY(1px);
}

/* Pastel Mint */
.pastel-mint {
  background-color: #98D8C8;
  color: #2C3E50;
}
.pastel-mint:hover { background-color: #7BCBAC; 
    transform: scale(1.2);  
}
.pastel-mint:active { background-color: #5EBE90; }

/* Pastel Lavender */
.pastel-lavender {
  background-color: #D4BFFF;
  color: #4A4A4A;
}
.pastel-lavender:hover { background-color: #C1A3FF; }
.pastel-lavender:active { background-color: #AE87FF; }

/* Pastel Pink */
.pastel-pink {
  background-color: #FFD1DC;
  color: #5A4E4E;
}
.pastel-pink:hover { background-color: #FFB6C1; }
.pastel-pink:active { background-color: #FF9AA2; }

/* Pastel Yellow */
.pastel-yellow {
  background-color: #FDFD96;
  color: #5A4E4E;
}
.pastel-yellow:hover { background-color: #FCFC83; }
.pastel-yellow:active { background-color: #FAFA70; }

/* Pastel Blue */
.pastel-blue {
  background-color: #AEC6CF;
  color: #2C3E50;
}
.pastel-blue:hover { background-color: #96B4C2; }
.pastel-blue:active { background-color: #7EA2B4; }


.code-block {
    /*background-color: #f8f9fa;*/
    background-color: var(--bs-dark-bg-subtle);
    /* color: #e2e8f0; */
    /* color: var(--bs-dark-text-emphasis); */
    padding: 20px;
    border-radius: 10px;
    overflow-x: auto;
    margin-bottom: 20px;
    font-family: monospace;
    padding: 20px;
    line-height: 1.6;
}

pre {
    margin: 0;
    /* color: black; */
    color: var(--bs-dark-text-emphasis);
    font-size: 17px;
    font-family: Poppins, sans-serif;
}

    .nav-link-new{
        font-size: 14px;
    }
    .nav-link-new:hover{
        transform: scale(1.1);
    }
    label{
        font-weight: bold;
        font-size: 16px;
    }


  /* spk */

  .dashboard {
  display: flex;
  gap: 20px;
  font-family: Arial, sans-serif;
}

.card {
  /* background-color: white; */
  background-color: var(--bs-body-bg);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  flex: 1;
  /* border: 1px solid #697565; */
  border: 2px solid var(--bs-light-bg-subtle);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.title {
  font-size: 14px;
  /* color: #666; */
  color: var(--bs-body-color);
}

.icon {
  font-size: 18px;
}

.main-value {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 5px;
}

.sub-value {
  font-size: 13px;
  color: var(--bs-body-color);
}

.strike{
  text-decoration: line-through;
}

/* end spk */

/* css button bridging */

.btn-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: 0.3s ease;
    user-select: none;
}

.status-closing {
    background-color: #d4edda;  /* Soft green background */
    color: #155724;             /* Darker green text */
    border: 1px solid #c3e6cb;
}

.status-false {
    background-color: #f8d7da;  /* Soft red background */
    color: #721c24;             /* Darker red text */
    border: 1px solid #f5c6cb;
}

.status:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* end button bridging */

/* datatable */

/* Gaya umum tabel */
/* .datatable {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  font-family: 'Arial', sans-serif;
} */

/* Header tabel */
/* .datatable thead {
  background-color: #3498db;
  color: white;
} */

/* .datatable th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
} */

/* Baris tabel */
/* .datatable tbody tr {
  background-color: #ffffff;
  transition: background-color 0.3s ease;
}

.datatable tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.datatable tbody tr:hover {
  background-color: #e9f5fe;
} */

/* Sel tabel */
/* .datatable td {
  padding: 12px 15px;
  font-size: 14px;
  color: #333;
} */

/* Pagination */
/* .datatable-pagination {
  display: flex;
  justify-content: flex-end;
  padding: 10px 0;
} */

/* .datatable-pagination button {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 8px 12px;
  margin: 0 5px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
} */

/* .datatable-pagination button:hover {
  background-color: #2980b9;
} */

</style>