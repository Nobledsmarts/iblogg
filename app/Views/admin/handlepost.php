<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>

                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3 class="page-title">
                            <?= (esc($is_edit) ? 'Edit' : 'Create') ?> 
                            <?= esc($page); ?>
                            </h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <?= $this->include('admin/templates/searchForm'); ?>
                        </div>
                    </section>
                    <section class="main-content p-2" align="">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert d-none alert-danger" id="formError">
                                </div>
                                <div class="alert d-none alert-success" id="formSuccess">
                                </div>
                                <?php if(isset($post)) : ?>
                                    <?php if( !empty($post)) : ?>
                                        
                                        <?=view_cell('\App\Libraries\Postform::get', ['is_edit' => esc($is_edit), 'post_type' => $page, 'post_id' => $post_id, 'post' => $post]); ?>
                                    <?php else : ?>
                                        <div class="alert p-3 alert-danger">
                                            This <?= esc($page); ?>  Does not Exist, Or May Have being Deleted
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?=view_cell('\App\Libraries\Postform::get', ['is_edit' => esc($is_edit), 'post_type' => $page,]); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="px-2">
                            
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <div class="modal post-handle fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <span class="text-capitalize"> <?=esc($page); ?></span> <?= esc($is_edit) ? 'edited' : 'created'; ?>  successfully !
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal post-error error fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-light">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
