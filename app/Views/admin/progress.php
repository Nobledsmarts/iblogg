<section class="w-100">
        <div class="container-fluid w-100" style="display: flex; justify-content: center;">
            <div class="row w-100">
                <!-- <div class="col-md-3 bg-danger"></div> -->
                <?= $this->include('admin/templates/sidebar') ?>

                <div class="col-12 col-md-9 ml-sm-auto w-100">
                    <section class="row my-3 p-2" align="left">
                        <div class="col">
                            <h3> <?= esc($page) ?> </h3>
                        </div>
                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <form class="" onsubmit="searchPosts('admin/files', false)">
                                <table cellspacing="0" cellpadding="0" class="w-100">
                                    <tr>
                                        <td align="right" class="bg-light w-100 p-1 nav-form-td rounded-left">
                                            <input type="text" name="s" placeholder="search" class="w-100 bg-light border-0" value="@{{s}}">
                                        </td>
                                        <td align="left" class="btn-light nav-form-td rounded-right">
                                            <button class="btn">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </section>
                    <section class="main-content p-2" align="">
                        <div class="row">
                            <div class="col-12 content-center flex-column" style="min-height: 30vh;">
                                <div class="alert w-100 content-center alert-info shadow-sm">
                                    <div>
                                        Currently Not Available
                                        <br>
                                    </div>
                                </div>
                                <div>
                                    <a href="@{{backlink}}" class="alert alert-link">
                                        Go back 
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="px-2">
                            
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>