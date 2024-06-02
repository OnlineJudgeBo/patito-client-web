<?php
$className = "block text-sm md:text-xl text-white hover:text-yellow-400 rounded-md px-3 py-2";
if (isset($_SESSION['user_id'])) {
?>
    <a href="userInfo.php" class="<?php echo $className; ?>">Perfil</a>
    <a href="logout.php" class="<?php echo $className; ?>">Salir</a>
    <?php
    if (
        isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador" ||
        isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente"       ||
        isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar"      ||
        isset($_SESSION["contest_creator"])
    ) {
        echo "<a href='https://jv.umsa.bo/admin/' class='" . $className . "' target='_blank' >Administrar</a>";
    }
} else {
    ?>
    <a href="login.php" class="<?php echo $className; ?>">Iniciar sesión</a>
    <a href="registerpage.php" class="<?php echo $className; ?>">Registrarse</a>
<?php
}
