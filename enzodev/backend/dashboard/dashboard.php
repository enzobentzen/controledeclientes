<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-md p-10 w-80 text-center">
        <h1 class="text-xl font-semibold text-slate-800 mb-1">
            Bem-vindo, <?php echo $_SESSION['user_name']; ?>!
        </h1>
        <p class="text-sm text-slate-400 mb-8">O que deseja fazer?</p>

        <div class="flex flex-col gap-3">
            <a href="../clients/list.php" class="bg-blue-700 text-white py-3 rounded-xl text-sm font-medium hover:bg-blue-800">
                Ver meus clientes
            </a>
            <a href="../clients/form.php" class="bg-slate-100 text-slate-700 py-3 rounded-xl text-sm font-medium hover:bg-slate-200">
                Cadastrar novo cliente
            </a>
            <a href="../auth/logout.php" class="bg-red-50 text-red-500 py-3 rounded-xl text-sm font-medium hover:bg-red-100">
                Sair
            </a>
        </div>
    </div>

</body>

</html>