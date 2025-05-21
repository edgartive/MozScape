<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <title>Painel do Administrador</title>
</head>

<body>
    <div class="admin-container">
        <h1>Painel do Administrador</h1>

        <ul class="admin-list">
            <li class="admin-item">
                <i class="fas fa-users"></i>
                <span>Lista de Fotógrafos</span>
                <a href="fotografos.PHP"> <button class="action-button"
                        onclick="navigateTo('photographersList')">Acessar</button></a>
            </li>
            <li class="admin-item">
                <i class="fas fa-file-upload"></i>
                <span>Gerenciar Uploads</span><a href="admin_pedidos.php">
                    <button class="action-button" onclick="navigateTo('admin_pedidos.php')">Acessar</button></a>
            </li>
            <li class="admin-item">
                <i class="fas fa-user-check"></i>
                <span>Pedidos de Uploaders</span><a href="ver_pedidos.php">
                    <button class="action-button" onclick="navigateTo('requestsManagement')">Acessar</button> </a>
            </li>

            <li class="admin-item">
                <i class="fas fa-chart-bar"></i>
                <span>Relatórios & Estatísticas</span>
                <button class="action-button" onclick="navigateTo('reportsStatistics')">Acessar</button>
            </li>
            <li class="admin-item">
                <i class="fas fa-cogs"></i>
                <span>Configurações do Site</span>
                <button class="action-button" onclick="navigateTo('siteSettings')">Acessar</button>
            </li>
            <li class="admin-item">
                <i class="fas fa-envelope"></i>
                <span>Caixa de Mensagens</span>
                <button class="action-button" onclick="navigateTo('messagesBox')">Acessar</button>
            </li>
        </ul>
    </div>

    <script>
    </script>
</body>

</html>