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

$erro = "";
$erroTelefone = "";
$fname = $lname = $email = $phone = "";

if (isset($_POST['submit'])) {

    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phoneRaw = preg_replace('/\D/', '', $_POST['phone']);
    $phone = htmlspecialchars($_POST['phone']);
    $user_id = $_SESSION['user_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido!";
    }

    if (strlen($phoneRaw) < 11) {
        $erroTelefone = "Telefone incompleto! Digite o DDD + número (11 dígitos).";
    } elseif (strlen($phoneRaw) > 11) {
        $erroTelefone = "Telefone inválido! Número de dígitos excede o esperado.";
    }

    if (empty($erro) && $db->getClientByEmail($email, $user_id)) {
        $erro = "Email já cadastrado!";
    }

    if (empty($erro) && empty($erroTelefone)) {
        $db->registerClient($fname, $lname, $email, $phoneRaw, $user_id);
        header("Location: list.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-2xl shadow-sm p-9 w-96">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Cadastrar Cliente</h2>

        <?php if ($erro): ?>
            <div class="bg-red-50 text-red-500 text-sm px-4 py-3 rounded-xl mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-3">
            <input type="text" name="fname" placeholder="Nome" required
                value="<?php echo $fname; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <input type="text" name="lname" placeholder="Sobrenome" required
                value="<?php echo $lname; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <div class="flex flex-col gap-1">
                <input type="text" name="email" placeholder="Email" required
                    value="<?php echo $email; ?>"
                    class="border <?php echo $erro ? 'border-red-400' : 'border-slate-200'; ?> rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">
            </div>

            <div class="flex flex-col gap-1">
                <input type="text" name="phone" placeholder="DDD + número (11 dígitos)" required
                    maxlength="11"
                    inputmode="numeric"
                    value="<?php echo $phone; ?>"
                    class="border <?php echo $erroTelefone ? 'border-red-400' : 'border-slate-200'; ?> rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

                <?php if (!empty($erroTelefone)): ?>
                    <span class="text-red-500 text-xs px-1">
                        <?php echo $erroTelefone; ?>
                    </span>
                <?php endif; ?>
            </div>

            <button type="submit" name="submit"
                class="bg-blue-700 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-blue-800 mt-1">
                Cadastrar
            </button>
        </form>
    </div>

</body>

</html>