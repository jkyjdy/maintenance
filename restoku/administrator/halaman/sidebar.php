<br>
        <b>NAVIGASI</b>
        <hr>
        <a href="index.php">
            <div class="menu">
                Dashboard
            </div>
        </a>
        <a href="makanan.php">
            <div class="menu">
                Menu Makanan
            </div>
        </a>
        <!--Jika login sebagai manager maka dapat melihat laporan penjualan-->
        <?php 
            if ($_SESSION['role'] == 'Manager'){
        ?>
        <a href="laporan.php">
            <div class="menu">
                Laporan Penjualan
            </div>
        </a>
        <?php
            }
        ?>
        <!--jika login sebagai admin bisa melihat menu manajemen akun-->
        <?php 
            if ($_SESSION['role'] == 'Admin'){
        ?>
        <a href="akun.php">
            <div class="menu">
                Management Akun
            </div>
        </a>
        <?php
            }
        ?>