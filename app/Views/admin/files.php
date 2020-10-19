<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>
                
                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3> Files (<?=esc($total_file_count)?>) </h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <form class="" onsubmit="searchPosts('/admin/files', false)">
                                <table cellspacing="0" cellpadding="0" class="w-100">
                                    <tr>
                                        <td align="right" class="bg-light w-100 p-1 nav-form-td rounded-left">
                                            <input type="text" name="s" placeholder="search" class="w-100 bg-light border-0" value="@{{s}}">
                                        </td>
                                        <td align="left" class="btn-light nav-form-td rounded-right">
                                            <button class="btn">
                                                <span class="fa fa-search theme-blue"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </section>
                    <section class="main-content p-2" align="left">    
                        <div class="row">
                            <div class="col-12 d-non">
                                <div class="row">
                                    <?php if(esc($files_count) > esc($files_limit)) : ?>
                                        <div class="col">
                                            <button onclick="triggerDelete(null, 'file')" class="btn btn-danger d-inline btn-sm">
                                                delete all
                                            </button>
                                        <!-- <div> <br> </div> -->
                                            </div>
                                    <?php else : ?>
                                    <p>&nbsp;</p>
                                    <?php endif; ?>
                                    <div class="col" align="right">
                                        <div class="d-non">
                                            <a href="/admin/files/new">
                                                <button class="btn btn-success d-inline btn-sm">
                                                    New File + 
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(! empty($files) && is_array($files)) : ?>
                            <div class="col-12">
                                <div class="row">
                                          <?php foreach($files as $file): ?>
                                        <div class="col-md-4 my-3 my-md-2 trans">
                                                <?= view_cell('\App\Libraries\Functions::display_file', ['file' => $file]); ?>
                                          </div>
                                        <?php endforeach; ?>
                                    </div>
                            </div>
                            <div class="col-12">
                                <br>
                            </div>
                            <?php if(esc($files_count) > $files_limit ) : ?>
                            <div class="col-12">
                                <div class="px-2">
                                    <?= $pager->only(['page', 's'])->links('link') ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php else : ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if(isset($is_search)): ?>
                                            No Result found for '<b><?=esc($s) ?></b>'
                                        <?php else : ?>
                                            No Files Yet
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                    </section>
                </div>
            </div>
        </div>
    </section>
    <div class="modal file-delete-confirm fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-danger">
                    Do you really want to delete this file? <br>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="deleteFile()">
                        delete
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal file-delete-success fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    File Deleted successfully !
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>