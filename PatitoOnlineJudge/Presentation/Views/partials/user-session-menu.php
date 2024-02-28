<?php
$className = "text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2 text-sm font-medium";
if (isset($_SESSION['user_id'])) {
?>
    <a href="modifypage.php" class="<?php echo $className; ?>">Editar Usuario</a>
    <a href="logout.php" class="<?php echo $className; ?>">Salir</a>
<?php
   if (isset($_SESSION["administrator"]) && $_SESSION["administrator"] == "administrator" || isset($_SESSION["contest_creator"])) {
    echo "<a href='/admin' class='".$className."' target='_blank' >Administrar</a>";
   }
} else {
?>
    <a href="login.php" class="<?php echo $className; ?>">Iniciar sesión</a>
    <a href="registerpage.php" class="<?php echo $className; ?>">Registrarse</a>
<?php
}
