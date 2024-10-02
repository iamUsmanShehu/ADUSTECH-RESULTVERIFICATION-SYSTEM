      <div id="sidebar">
        <div class="sidebar__title">
          <div class="sidebar__img">
            <img src="assets/img/log-bg.png" alt="logo" style="border-radius: 50px;" />
            &nbsp &nbsp &nbsp<h1>ADUSTECH RESULT VERIFICATION SYSTEM</h1>
          </div>
          <i
            onclick="closeSidebar()"
            class="fa fa-times"
            id="sidebarIcon"
            aria-hidden="true"
          ></i>
        </div>

        <div class="sidebar__menu">
          <div class="sidebar__link active_menu_link">
            <i class="fa fa-home"></i>
            <a href="dashboard.php">Dashboard</a>
          </div>
          <!-- <h2>MNG</h2> -->
          <div class="sidebar__link">
            <i class="fa fa-user" aria-hidden="true"></i>
            <a href="profile.php">My Profile</a>
          </div>
          <div class="sidebar__link">
            <i class="fa fa-building-o"></i>
            <a href="admin.php">Upload Result</a>
          </div>

          <div class="sidebar__link">
            <i class="fa fa-building-o"></i>
            <a href="change-password.php">Change Password</a>
          </div>
          <div class="sidebar__logout">
            <i class="fa fa-power-off"></i>
            <a href="logout.php">Log out</a>
          </div>
        </div>
      </div>