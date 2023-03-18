<div class="topnav" id="navbar">
    <a href="./"><i class="bi bi-house-door-fill"></i> Home</a>

    <a href="./"><i class="bi bi-person-fill"></i> About</a>

    <a href="./"><i class="bi bi-terminal-fill"></i> Projects</a>

    <a href="./"><i class="bi bi-envelope-fill"></i> Contact</a>

    <?php if (!isset($_SESSION['user_login'])) { ?>
    <a href="./" class="a2"><i class="bi bi-box-arrow-in-right"></i> Sing In</a>
    <a href="./" class="a2"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
    <?php } else { ?>
    <a href=""></a>
    <?php }; ?>
    <a href="javascript:void(0);" class="icon" onclick="NavIcon()">
        <i class="bi bi-text-indent-right"></i>
    </a>
</div>

<div style="margin-bottom: 100px;"></div>