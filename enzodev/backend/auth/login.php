<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php");
}

require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new Database;
$erro = "";

if (isset($_POST['submit'])) {

    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    if (empty($email) || empty($pass)) {
        $erro = "Preencha todos os campos!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido!";
    } else {
        if ($db->loginUser($email, $pass)) {
            $userInfo = $db->getUserByEmail($email);
            $_SESSION['user_id'] = $userInfo['id'];
            $_SESSION['user_email'] = $userInfo['email'];
            $_SESSION['user_name'] = $userInfo['first_name'];
            header("Location: ../dashboard/dashboard.php");
            exit;
        } else {
            $erro = "Email ou senha incorretos!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - ClientHub</title>
    <link rel="stylesheet" href="../../frontend/style.css">
</head>

<body class="min-h-screen bg-black flex items-center justify-center">

    <div class="bg-white rounded-xl p-8 w-full max-w-sm shadow-lg">
        <h2 class="text-2xl font-bold text-center text-black mb-6">Login</h2>

        <?php if ($erro): ?>
            <p class="text-red-500 text-sm text-center mb-4"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">
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

            <button name="submit"
                class="bg-black text-white font-semibold py-2 rounded-lg hover:bg-gray-800 transition mt-2">
                Entrar
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Não tem uma conta?
            <a href="register.php" class="text-black font-semibold hover:underline">Cadastre-se</a>
        </p>
    </div>

</body>

</html>