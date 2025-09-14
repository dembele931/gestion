<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Boutique</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }
        
        .header {
            padding: 40px 30px 30px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .menu {
            padding: 30px;
        }
        
        .menu-list {
            list-style: none;
        }
        
        .menu-item {
            margin-bottom: 12px;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid #eaeaea;
        }
        
        .menu-link:hover {
            background: #4a6fc7;
            color: white;
            border-color: #4a6fc7;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(74, 111, 199, 0.25);
        }
        
        .menu-icon {
            margin-right: 15px;
            width: 24px;
            text-align: center;
            color: #4a6fc7;
            font-size: 18px;
        }
        
        .menu-link:hover .menu-icon {
            color: white;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-size: 14px;
            background: #f8f9fa;
        }
        
        @media (max-width: 500px) {
            .header {
                padding: 30px 20px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .menu {
                padding: 20px;
            }
            
            .menu-link {
                padding: 14px 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue dans votre gestion de boutique</h1>
            <p>Gérez votre activité simplement et efficacement</p>
        </div>
        
        <div class="menu">
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="produits/liste.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <span>Produits</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="ventes/liste.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span>Ventes</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="dettes/liste.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <span>Dettes</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="rapports.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span>Rapports</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="footer">
            <p>© 2025 Gestion Boutique</p>
        </div>
    </div>
</body>
</html>