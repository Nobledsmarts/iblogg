<header class="sticky-top">
    <nav class="admin-nav nav dashboard-nav navbar navbar-expand-sm p-0 shadow-sm">
        <div class="container-fluid p-0">
            <div class="row w-100 m-0" align="center">
                <div class="col-9 col-md-3 p-0 nav-brand-cov px-md-0">
                    <div class="navbar-header px-2 h-100 d-flex justify-content-flex-start justify-content-md-center ">    
                        <div class="navbar-brand h-100">
                            <a href="/">
                                <btn class="btn outline btn-light theme-blue px-4 px-md-5  rounded pill">
                                   <b> iBlogg </b>
                                   <i class="theme-blue fa fa-home">
                                   </i>
                                </btn>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-3 col-md-9 nav-items-cont px-0" align="center">
                    <ul class="list-inline d-md-none list-unstyled d-flex w-100 h-100 v-content-center justify-content-end">
                        <li class="mx-3 content-center">
                            <div class="navbar-toggler-icon" data-toggle="collapse" data-target="#sidebarMenu">
                                <i class="fa fa-bars"></i>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-inline list-unstyled w-100 d-none d-md-flex h-100 v-content-center" style="justify-content: space-between;">
                        <li class="v-content-center h-100" align="right">
                            <li class="v-content-center h-100" align="right">
                                <div class="d-sm-flex h-100 justify-content-sm-end align-items-sm-center collapse navbar-collapse" id="navcollapse">
                                    <ul class="navbar-nav navbar-right">
                                        <li class="nav-item mr-md-4 v-content-center">
                                            <div class="content-center nav-sign-out-btn rounded-circle bg-light shadow-sm" data-toggle="modal" data-target=".logout-confirm">
                                                <i class="fa fa-sign-out-alt theme-blue"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </li>
                    </ul>
                </div> 
            </div>
        </div>
    </nav>
</header>
<div class="modal logout-confirm fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-danger">
                    Do you really want to log out? <br>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="logOut()">
                        logout
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>