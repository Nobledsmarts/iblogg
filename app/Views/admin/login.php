    <body class="d-flex justify-content-center align-items-center flex-column">
        <div class="container bg-light login-container shadow-sm">
            <div class="row">
                <div class="col-md-12 p-5">
                    <div class="content-center py-2">
                        <img src="assets/img/user.jpg" class="bg-white p-1 img-fluid rounded-circle" style="width: 150px; height: 150px;">
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-md-6 col-12">
                            <form onsubmit="adminLogin()" class="content-center px-md-5" style="flex-direction: column;">
                                <h5>Admin Login</h5>
                                <?php if(isset($message)) : ?>
                                    <div class="alert w-100 text-center alert-danger mb-0" id="formError">
                                        <?= esc($message) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert w-100 text-center alert-danger d-none mb-0" id="formError">
                                    </div>
                                <?php endif; ?>
                                <div class="form-row w-100 mt-0">
                                    <label for="username"> login </label>
                                    <input type="text" class="form-control" id="username" name="login" placeholder="Enter your username">
                                </div>
                                <div class="form-row w-100">
                                    <label for="password"> password </label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                                </div>
                                    <input type="submit" class="my-3 btn-block btn btn-primary" value="submit">
                            </form>
                            <div class="mt-2" align="center">
                               <a href="/">
                                    &laquo;&laquo; Home
                                </a>
                               &nbsp; | &nbsp;
                               <b>
                                   &copy; iBlogg 2020
                                </b>
                            </div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
            </div>
        </div>