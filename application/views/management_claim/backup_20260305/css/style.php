
<style>
    body 
    {
        font-family: 'Poppins';
        /* background-color: #181818; */
        /* background-color: #222; */
        font-weight: 500;
        font-style: normal;
    }

    .filter-nav ul {
        list-style-type: none;
        display: flex;
        padding: 0;
        margin: 0;
        overflow-x: auto;
    }

    .filter-nav li {
        background-color: #272727;
        color: #fff;
        padding: 8px 12px;
        margin-right: 10px;
        border-radius: 8px;
        white-space: nowrap;
        cursor: pointer;
        font-size: 14px;
    }

    #myLink:hover {
        background-color: #B43F3F;
        color:  #fff;
    }

    .filter-nav li:hover {
        background-color: #B43F3F;
        color: #000;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .filter-nav ul::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .filter-nav ul {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    .filter-nav {
        position: sticky;
        top: 0;
        /* background-color: #0f0f0f;  */
        padding: 10px 0;
        z-index: 1; /* Ensure it stays on top of other content */
        transition: background-color 0.7s ease; /* Add transition for smooth fade */
    }

    a {
        color: #fff; /* Set to your desired color */
        text-decoration: none; /* Removes underline */
    }

    a:hover {
        color: #B43F3F;
    }

</style>