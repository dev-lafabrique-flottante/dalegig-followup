<div class="horizontal-menu">
  <nav class="navbar top-navbar col-lg-12 col-12 p-0" style="padding-top:0px;height:45px">
    <div class="container-fluid" style="background: linear-gradient(142deg, rgba(224,224,224,1) 0%, rgba(0,0,0,1) 0%, rgba(119,51,147,1) 100%);padding:0px">
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
        <ul class="navbar-nav navbar-nav-left">   
        </ul>
        <!-- LOGO -->  
        <div class="text-left navbar-brand-wrapper d-flex align-items-left" style="position:absolute;left:45px">
            <a class="navbar-brand brand-logo"><img src="https://www.dalegig.com/assets/images/dale-gig-gray-p.png" alt="logo"/> <font color=white>FOLLOW UP MANAGER <font size=2> Acompanhamento humanizado de propostas de shows</font></font></a>
            <a class="navbar-brand brand-logo-mini" href="people_zone.php"><img src="images/logo_artbypro_white.png" style='height:60px' alt="logo"/></a>
        </div>
        <!-- END LOGO -->
        <!-- User account -->  
        <ul class="navbar-nav navbar-nav-right">
            <!-- notifications -->    
          <!--li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell mx-0"></i>
                <span class="count bg-danger">2</span>
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;  
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-information mx-0"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Just now
                    </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-settings mx-0"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Private message
                    </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-account-box mx-0"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      2 days ago
                    </p>
                </div>
              </a>
            </div>
          </li-->
          &nbsp;&nbsp;&nbsp;
          <!-- END notifications -->    
          <!-- LANGUAGE SWITCH -->
          <!--li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <span class="nav-profile-name" style="color:white">PT</span>
              </a>
              &nbsp;&nbsp;&nbsp;&nbsp;    
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <a class="dropdown-item">
                    <i class="mdi mdi-flag-outline text-primary"></i>
                    EN
                  </a>
                  <a class="dropdown-item">
                    <i class="mdi mdi-flag-outline text-primary"></i>
                    ES
                  </a>
                  <a class="dropdown-item">
                    <i class="mdi mdi-flag-outline text-primary"></i>
                    FR
                  </a>
              </div>
          </li-->    
          
          <!-- END LANGUAGE SWITCH -->   
          <!-- TUTORIAL -->
          <?php
            if(!empty($_SESSION['manager'])){
                echo "<a href='https://youtu.be/6zpilsLwZmc' target=_blank class='btn btn-outline-warning' style='position:absolute;top:6px;right:300px'>Tutorial</a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
          ?>
          <!-- END TUTORIAL -->    
          <!-- END chat notifications --> 
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <span class="nav-profile-name" style="color:white">Wilton</span>
                <span class="online-status"></span>
              </a>
              &nbsp;&nbsp;&nbsp;&nbsp;    
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <!--a class="dropdown-item">
                    <i class="mdi mdi-settings text-primary"></i>
                    Settings
                  </a-->
                  <a href='https://dalegig.com' class="dropdown-item">
                    <i class="mdi mdi-logout text-primary"></i>
                    Logout
                  </a>
              </div>
            </li>
            &nbsp;&nbsp;&nbsp;&nbsp;
        </ul>
        <!-- END user account -->  
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </div>
  </nav>
  <!--nav class="bottom-navbar">
    <div class="container">
        <ul class="nav page-navigation">
          <li class="nav-item">
                <a class="nav-link" href="people_zone.php">
                  <i class="mdi mdi-access-point-network menu-icon"></i>
                  <span class="menu-title">Geral</span>
                </a>
          </li>
          <li class="nav-item">
              <a href="deveres.php" class="nav-link">
                <i class="mdi mdi-google-circles-communities menu-icon"></i>
                <span class="menu-title">Dever de casa</span>
                <i class="menu-arrow"></i>
              </a>
          </li>  
          <li class="nav-item">
              <a href="chat.php" class="nav-link">
                <i class="mdi mdi-chat menu-icon"></i>
                <span class="menu-title">Mensagens</span>
                <i class="menu-arrow"></i>
              </a>
          </li>
          <li class="nav-item">
              <a href="calendario.php" class="nav-link">
                <i class="mdi mdi-bookmark-music menu-icon"></i>
                <span class="menu-title">Agendar sessão<br>Calendário</span> 
                <i class="menu-arrow"></i>
              </a>
          </li>  
          <!--li class="nav-item">
              <a href="profile.php" class="nav-link">
                <i class="mdi mdi-google-circles-communities menu-icon"></i>
                <span class="menu-title">Eventos</span> 
                <i class="menu-arrow"></i>
                
              <!--/a>
          </li-->
        </ul>
    </div>
  </nav--> 
</div>