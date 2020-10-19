<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>
                
                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3 class="page-title">comments (<?=esc($comments_count)?>)</h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                        <?= $this->include('admin/templates/searchForm'); ?>
                        </div>
                    </section>
                    <section class="main-content p-2" align="left">
                        <div class="row d-none">
                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="bg-secondar bg-darkblue p-4 w-100 text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold">
                                            <?=esc(count($comments)); ?>
                                        </h2>
                                        <span> comments </span>
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
                                    <button class="btn btn-success d-inline btn-sm text-capitalize" onclick="history.go(-1)">
                                       &laquo; back
                                    </button>
                            </div>
                            <div class="d-non">
                                <?php if(esc($comments_count) > esc($comments_limit) ) : ?>
                                    <button onclick="triggerDelete(null, 'comment')" class="btn btn-danger d-inline btn-sm">
                                        delete all
                                    </button>
                                <div><br></div>
                                <?php else : ?>
                                <p>&nbsp;</p>
                                <?php endif; ?>
                            </div>
                            <!-- <div -->
                            <?php if(! empty($comments) && is_array($comments)) : ?>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                      <thead>
                                        <tr>
                                          <th> Author </th>
                                          <th> Comment </th>
                                          <th> Time </th>
                                          <th> Ip </th>
                                          <th class="w-10"> Action </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach($comments as $comment): ?>
                                        <tr>
                                          <td>
                                              <?=esc($comment['comment_author'])?>
                                            </td>
                                            <td>
                                            <?= view_cell('\App\Libraries\Functions::slice_words', ['str' => esc($comment['comment_body']), 'word_length' => 50, 'type' => "''"]) ?>
                                            </td>
                                            <td>
                                              <?= view_cell('\App\Libraries\Functions::timeago', ['date' => $comment['created_at']]); ?>
                                            </td>
                                            <td>
                                            <?=esc($comment['author_ip'])?>
                                            </td>
                                            <td>
                                            <a class="d-" href="/post/<?= view_cell('\App\Libraries\Functions::comment_page', ['post_id' => $comment['post_id'], 'comment_id' => $comment['comment_id']]); ?>">
                                                <button class="btn btn-outline-light btn-sm">
                                                <span class="fa fa-eye">
                                                </a>
                                                </button>
                                              <button onclick="triggerDelete(<?=$comment['comment_id']?>, 'comment')" class="btn btn-primary btn-sm">delete</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        
                                      </tbody>
                                    </table>
                                  </div>
                            </div>
                            <br>
                            <?php if(esc($comments_count) > $comments_limit ) : ?>
                            <div class="col-12">
                                <ul class="pagination">
                                    <?php foreach($pagination as $link): ?>
                                        <li class="page-item <?=($link['active'] ? 'active' : 'false')?>">
                                            <a class="page-link" href="/admin/comments?page=<?= ($link['value']) ?>">
                                                <?= ($link['title']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                                
                            <?php endif; ?>
                            <?php else : ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                            No <span class="text-capitalize">
                                                comments
                                            </span> Yet
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
    <div class="modal comment-delete-confirm fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-danger">
                    Do you really want to delete this comment? <br>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="deleteComment()">
                        delete
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal comment-delete-success fade" data-backdrop='static'>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert-primary text-primary">
                    <p class="modal-title"> iBlogg </p>
                    <button type="button" onclick="router.route()" data-dismiss="modal" class="close">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <?=esc('$is_page') ? 'Page' : 'comment'; ?> Deleted successfully !
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-primary" onclick="router.route()">
                        close
                    </button>
                </div>
            </div>
        </div>
    </div>