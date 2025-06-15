<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        height: calc(100vh - 95px);
        position: fixed;
        top: 95px;
        left: 0;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        z-index: 999;
    }

    .sidebar .menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar .menu li {
        margin-bottom: 15px;
        border-bottom: none !important;
    }

    .sidebar .menu li a {
        display: flex;
        align-items: center;
        color: white;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none !important;
        border: none !important;
    }

    .sidebar .menu li a i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .sidebar .menu li a:hover {
        background: #3498db;
        transform: translateX(10px);
        text-decoration: none !important;
    }

    .sidebar .menu li.active a {
        background: #3498db;
        font-weight: 600;
    }

    /* Main Content Styles */
    .main-content {
        margin-left: 270px;
        padding: 40px 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
            margin-bottom: 20px;
        }
        
        .main-content {
            margin-left: 0;
            padding: 20px;
        }
    }
</style>
