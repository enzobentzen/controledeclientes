<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new Database;
$erro = "";

if (isset($_POST['submit'])) {

    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    $confirmPass = htmlspecialchars(trim($_POST['confirm_pass']));

    if (empty($fname) || empty($lname) || empty($email) || empty($pass) || empty($confirmPass)) {
        $erro = "Preencha todos os campos!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido!";
    } else if ($pass !== $confirmPass) {
        $erro = "As senhas não coincidem!";
    } else if (strlen($pass) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres!";
    } else if ($db->getUserByEmail($email)) {
        $erro = "Este email já está cadastrado!";
    } else {
        if ($db->registerUsers($fname, $lname, $email, $pass)) {
            header("Location: login.php?cadastro=sucesso");
            exit;
        } else {
            $erro = "Erro ao cadastrar. Tente novamente!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro - ClientHub</title>
    <link rel="stylesheet" href="../../frontend/style.css">
</head>

<body class="min-h-screen bg-black flex items-center justify-center">

    <div class="bg-white rounded-xl p-8 w-full max-w-sm shadow-lg">
        <h2 class="text-2xl font-bold text-center text-black mb-6">Criar Conta</h2>

        <?php if ($erro): ?>
            <p class="text-red-500 text-sm text-center mb-4"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-semibold text-gray-700">Nome</label>
                <input type="text" name="fname" placeholder="Seu nome"
                    class="border border-gray-300 rounded-lg px-4 py-2 outline-none focus:border-black">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-semibold text-gray-700">Sobrenome</label>
                <input type="text" name="lname" placeholder="Seu sobrenome"
                    class="border border-gray-300 rounded-lg px-4 py-2 outline-none focus:border-black">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" placeholder="seu@email.com"
                    class="border border-gray-300 rounded-lg px-4 py-2 outline-none focus:border-black">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-semibold text-gray-700">Senha</label>
                <input type="password" name="pass" placeholder="••••••••"
                    class="border border-gray-300 rounded-lg px-4 py-2 outline-none focus:border-black">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-semibold text-gray-700">Confirmar Senha</label>
                <input type="password" name="confirm_pass" placeholder="••••••••"
                    class="border border-gray-300 rounded-lg px-4 py-2 outline-none focus:border-black">
            </div>

            <button name="submit"
                class="bg-black text-white font-semibold py-2 rounded-lg hover:bg-gray-800 transition mt-2">
                Cadastrar
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Já tem uma conta?
            <a href="login.php" class="text-black font-semibold hover:underline">Fazer login</a>
        </p>
    </div>

</body>

</html>