<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../config/database.php";
$db = new Database();
$list = $db->showClients($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meus Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black min-h-screen p-10">

    <div class="max-w-4xl mx-auto">


        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-white">Meus Clientes:</h1>
            <a href="form.php" class="bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-800">
                + Adicionar cliente
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Nome</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Sobrenome</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Email</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Telefone</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $client) { ?>
                        <tr class="border-t border-slate-50 hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo $client["first_name"] ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo $client["last_name"] ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo $client["email"] ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo $client["phone"] ?></td>
                            <td class="px-4 py-3 text-sm">
                                <a href="edit.php?id=<?php echo $client["id"]; ?>" class="text-blue-600 font-medium hover:underline mr-4">Editar</a>
                                <a href="delete.php?id=<?php echo $client["id"]; ?>" class="text-red-500 font-medium hover:underline">Excluir</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>