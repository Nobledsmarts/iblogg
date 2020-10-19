<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>
                
                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3> Dashboard </h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <?= $this->include('admin/templates/searchForm'); ?>
                        </div>
                    </section>
                    <section class="main-content p-2" align="left">
                        <div class="row">
                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="bg-secondar bg-darkblue p-4 w-100 text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold">
                                            <?=esc($post_count)?>
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
                                        <h2 class="font-weight-bold">
                                            <?=esc($total_file_count)?>
                                        </h2>
                                        <span>Files</span>

                                    </div>
                                    <div class="content-center">
                                        <span class="fas fa-file fa-3x"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 my-2 m-md-0">
                                <div class="p-4 bg-success text-light rounded shadow-sm d-flex justify-content-between"">
                                    <div class="d-flex flex-column justify-content-around">
                                        <h2 class="font-weight-bold">
                                            <?=esc($total_user_count)?>
                                        </h2>
                                        <span> users </span>

                                    </div>
                                    <div class="content-center">
                                        <span class="fa fa-user fa-3x"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col">
                                <h5> Recent Posts </h5>
                                <br>
                            </div>
                            <div class="">
                                <a href="/admin/newpost">
                                    <button class="btn btn-success d-inline btn-sm">
                                        New Post + 
                                    </button>
                                </a>
                            </div>
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
                                              <button class="mx-2 text-ligh btn btn-outline-primary btn-sm" onclick="router.routeTo('/admin/editpost?postid=<?= $post['post_id']; ?>')">
                                                 Edit
                                                </button>
                                                <button class="btn btn-act btn-sm mx-2" onclick="deletePost('<?= $post['post_id']; ?>')">
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
                            <?php ?>
                            <div class="col-12 d-none">
                                <div class="px-2">
                                    <div class="pagination">
                                        <div class="row" align="center">
                                            <div class="col-12" align="center">
                                                <ul class="pagination">
                                                    <li class="page-item active">
                                                        <a class="page-link">
                                                            1
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link">
                                                            2
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link">
                                                            3
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link">
                                                            4
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link">
                                                            5
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link">
                                                            6
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else : ?>
                            <div class="col-12">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            No Posts yet
                                        </div>
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