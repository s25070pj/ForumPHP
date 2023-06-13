<body>
<header>
    <nav>
        <ul class="navigation">
            <li><a href="panel.php">Strona główna</a></li>
            <li><a href="search.php">Wyszukiwanie</a></li>
            <li><a href="creationoftopic.php">Utwórz nowy temat</a></li>
            <li class="logout-button"><?php
                session_start();
                if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'admin') {
                    echo '<a href="admin.php">Panel administratora</a>';
                }?>
            </li>
        </ul>
        <form method="POST" action="logout.php">
            <input type="submit" value="Wyloguj">

        </form>
    </nav>
</header>

<div class="container">



</div>