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

if (!isset($_GET['id'])) {
    die("ID not provided");
}

$id = $_GET['id'];
$client = $db->getClient($id);

if (!$client) {
    die("Client not found");
}

if (isset($_POST['submit'])) {
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    $db->updateClient($fname, $lname, $email, $phone, $id);
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-2xl shadow-sm p-9 w-96">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Editar Cliente</h2>

        <form method="POST" class="flex flex-col gap-3">
            <input type="text" name="fname" placeholder="Nome" required
                value="<?php echo $client['first_name']; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <input type="text" name="lname" placeholder="Sobrenome" required
                value="<?php echo $client['last_name']; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <input type="text" name="email" placeholder="Email" required
                value="<?php echo $client['email']; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <input type="text" name="phone" placeholder="Telefone"
                value="<?php echo $client['phone']; ?>"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">

            <button type="submit" name="submit"
                class="bg-blue-700 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-blue-800 mt-1">
                Salvar
            </button>
        </form>
    </div>

</body>

</html>