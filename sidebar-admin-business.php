    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $_SESSION['username']; ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Kontrolpanel</li>
          <li>
            <a href="super-admin/home.php?bid=<?php echo $_GET['bid']; ?>">
              <i class="fa fa-book"></i> <span>Desk</span>
            </a>
          </li>
          <li>
            <a href="super-admin/ture.php?bid=<?php echo $_GET['bid']; ?>"><i class="fa fa-cab"></i> <span>Travel / Tours</span>
            </a>
          </li>
          <li>
            <a href="super-admin/takstzoner.php?bid=<?php echo $_GET['bid']; ?>">
              <i class="fa fa-bar-chart"></i> <span>Takstzoner</span>
            </a>
          </li>
          <li>
            <a href="business-add.php">
              <i class="fa fa-book"></i> <span>Create Business</span>
            </a>
          </li>
          <li class="active">
            <a href="business-list.php">
              <i class="fa fa-gears"></i> <span>Business</span>
            </a>
          </li>
        </ul>

      </section>
      <!-- /.sidebar -->
    </aside>