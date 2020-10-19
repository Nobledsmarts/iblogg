<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>
                
                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3 class="page-title"><?=esc($page) ?>s (<?=esc($post_count)?>)</h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <?= view_cell('\App\Libraries\Functions::do_searchForm', ['url' => '/admin/' . $page .'s']) ?>
                        </div>
                    </section>
                    <section class="main-content p-2" align="left">
                        <div class="row d-none">
                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="bg-secondar bg-darkblue p-4 w-100 text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold">
                                            <?=esc(count($posts)); ?>
                                        </h2>
                                        <span> Posts </span>
                                    </div>
                                    <div class="content-center">
                                        <span class="fa fa-pen fa-3x"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="p-4 bg-primary text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold"> 0</h2>
                                        <span>Comments</span>

                                    </div>
                                    <div class="content-center">
                                        <span class="fa fa-comments fa-3x"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="p-4 bg-success text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold"> 0 </h2>
                                        <span> users </span>

                                    </div>
                                    <div class="content-center">
                                        <span class="fa fa-user fa-3x"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col d-non">
                                <?php if(esc($post_count) > esc($posts_limit) ) : ?>
                                    <button onclick="triggerDelete(null, '<?=esc($page) ?>')" class="btn btn-danger d-inline btn-sm">
                                        delete all
                                    </button>
                                <div><br></div>
                                <?php else : ?>
                                <p>&nbsp;</p>
                                <?php endif; ?>

                            </div>
                            <div class="d-non">
                                <a href="/admin/new<?=esc($page)?>">
                                    <button class="btn btn-success d-inline btn-sm text-capitalize">
                                        New <?=esc($page) ?> + 
                                    </button>
                                </a>
                            </div>
                            <!-- <div -->
                            <?php if(! empty($posts) && is_array($posts)) : ?>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                      <thead>
                                        <tr>
                                          <th> Title </th>
                                          <th> Time </th>
                                          <th> Comments </th>
                                          <th> Action </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach($posts as $post): ?>
                                        <tr>
                                          <td>
                                            <?= view_cell('\App\Libraries\Functions::slice_words', ['str' => $post['post_title'], 'word_length' => 80, 'type' => "''"]) ?>
                                            </td>
                                          <td>
                                              <?= view_cell('\App\Libraries\Functions::timeago', ['date' => $post['created_at']]); ?>
                                            </td>
                                          <td> 0 </td>
                                          <td>
                                              <button class="mx-2 btn btn-outline-primary btn-sm" onclick="router.routeTo('/admin/edit<?=esc($page)?>?postid=<?=$post['post_id']?>')">
                                                 Edit
                                                </button>
                                                <button class="btn btn-primary btn-sm mx-2" data-type="<?=esc($page)?>" onclick="triggerDelete(<?= $post['post_id']; ?>, '<?=esc($page) ?>')">
                                                    Del
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        
                                      </tbody>
                                    </table>
                                  </div>
                            </div>
                            <br>
                            <?php if(esc($post_count) > $posts_limit ) : ?>
                            <div class="col-12">
                                <ul class="pagination">
                                    <?php foreach($pagination as $link): ?>
                                        <li class="page-item <?=($link['active'] ? 'active' : 'false')?>">
                                            <a class="page-link" href="/admin/<?=esc($page)?>s?page=<?= ($link['value']) ?>">
                                                <?= ($link['title']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <!-- <= $pager->only(['page', 's'])->links('link') ?> -->
                            <?php endif; ?>
                            <?php else : ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if(isset($is_search)): ?>
                                            No Result found for '<b><?=esc($s) ?></b>'
                                        <?php else : ?>
                                            No <span class="text-capitalize">
                                                <?=esc($page)?>
                                            </span> Yet
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
    <div class="modal post-delete-confirm fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-danger">
                    Do you really want to delete this <?=esc($page) ?>? <br>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="deletePost()">
                        delete
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal post-delete-success fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <span class="text-capitalize">
                        <?=esc($page)?>
                    </span>
                    Deleted successfully !
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>