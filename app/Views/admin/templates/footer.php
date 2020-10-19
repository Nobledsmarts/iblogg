    <footer class="container-fluid d-non  admin-footer sidebar-bg" style="display: initial;">
        <div class="row h-100" align="">
            <div class="col-md-3 d-none d-md-block"></div>
            <div class="col shadow-sm admin-footer-main bg-light content-center text-dark" align="center">
                &copy; iBlogg - 2020
            </div>
        </div>
    </footer>
    <div class="modal logout-confirm fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.redirectTo(router.getHash())" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-danger">
                    Do you really want to delete this file? <br>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="router.redirectTo(router.getHash())">
                        delete
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="router.redirectTo(router.getHash())">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
    