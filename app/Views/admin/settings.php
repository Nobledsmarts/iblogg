<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>
                
                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3> Settings </h3> 
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <?= $this->include('admin/templates/searchForm'); ?>
                        </div>
                    </section>
                    <section class="main-content p-2" align="left">    
                        <div class="row">
                            <div class="col-12">
                                <form onsubmit="saveSettings()">
                                <div class="form-group">
                                        <label for="settings1">
                                            Site TagLine
                                        </label>
                                        <input type="text" class="form-control" name="settings[site_settings][site_tagline]" value="<?=esc($site_settings->site_tagline) ?>" id="settings1">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings1">
                                            Posts Per Page (user)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[post_settings][user_posts_per_page]" value="<?=esc($post_settings->user_posts_per_page) ?>" id="settings1">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings2">
                                            Posts Per Page (User Search Page)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[post_settings][usersrch_posts_per_page]" value="<?=esc($post_settings->usersrch_posts_per_page) ?>" id="settings2">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings3">
                                            Posts Per Page (Admin)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[post_settings][admin_posts_per_page]" value="<?=esc($post_settings->admin_posts_per_page) ?>" id="settings3">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings4">
                                            Files Per Page (Admin)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[file_settings][files_per_page]" value="<?=esc($file_settings->files_per_page) ?>" id="settings4">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings5">
                                            Comments Per Page (User)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[comment_settings][user_comments_per_page]" value="<?=esc($comment_settings->user_comments_per_page) ?>" id="settings5">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings5">
                                            Comments Per Page (Admin)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[comment_settings][admin_comments_per_page]" value="<?=esc($comment_settings->admin_comments_per_page) ?>" id="settings6">
                                    </div>
                                    <div class="form-group">
                                        <label for="settings6">
                                            User list Per Page (Admin)
                                        </label>
                                        <input type="number" min="3" class="form-control" name="settings[user_settings][admin_user_per_page]" value="<?=esc($user_settings->admin_user_per_page) ?>" id="settings7">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="save" class="btn btn-primary d-block w-100 py-2">
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <br>
                            </div>
                        </div>
                        
                    </section>
                </div>
            </div>
        </div>
    </section>

    <div class="modal settings-handle fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.redirectTo(router.getHash())" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    settings updated successfully !
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="router.redirectTo(router.getHash())">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>