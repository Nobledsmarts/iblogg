const routes = {
    '/' : {
        matched(resp){
            d.title = 'iBlogg - Home';
            let offset = resp.params.page || 0;
            if(offset == 1) router.routeTo('/');
            fetch('templates/view/user?page=index&offset=' + offset, {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
               return res.text();
            }).then((template) => {
                hideLoader();
                let data = {
                    currentUrl : resp.url.path,
                    s : resp.params.s || ''
                }
                this.view(data, getTemplate(template));
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        ...exist
    },
    '/{page:(post|page)}/{slug:(.+?)+}' : {
        matched(resp, page, slug){
            d.title = (slug.replace(/-/g, ' ').split(':')[0]);
            let offset = resp.params.page || 1;
            this.params = resp.params;
            let fetchUrl = 'templates/view/user?page=' + page + '&slug=' + slug + '&offset=' + offset;
            fetch(fetchUrl, {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
               return res.text();
            }).then((template) => {
                hideLoader();
                this.view({ s : ''}, getTemplate(template));
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        mounted(){
            let cid = this.params.cid;
            let c = this.params.c;
            if(cid) scrollInToView('#cid-' + cid);
            else if(c) scrollInToView('#' + c);
        },
        ...exist
    },
    '/search' : {
        matched(resp){
            // let hasOffset = 'page' in resp.params;
            let hasSearch = 's' in resp.params;
            let offset = resp.params.page || 1;
            let srchStr = hasSearch ? '&s=' + resp.params.s : '';
            d.title = 'iBlogg Search - ' + srchStr;
            if( hasSearch ){
                fetch('templates/view/user?page=searchpage&offset=' + offset + srchStr, {
                    headers : {
                        ...requestHeader,
                    },
                })
                .then((res) => {
                    return res.text();
                }).then((template) => {
                    hideLoader();
                    let data = {
                        currentUrl : resp.url.path,
                        s : resp.params.s || ''
                    }
                    this.view(data, getTemplate(template))
                }).catch((e) => {
                    this.view({}, getErrorTemplate());
                });
            } else {
                this.view(null, '<div class="not-found shadow"> Search query not found! </div>')
            }
        },
        ...exist

    },
    '/admin' : {
        matched(resp){
            d.title = 'iBlogg - admin';
            fetch('templates/view/admin?page=index', {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                this.view({s : resp.params.s || ''}, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        mounted(){
            registerModalEvent();
        },
        ...exist
    },
    '/admin/login' : {
        matched(resp){
            d.title = 'iBlogg - Admin Login';
            fetch('templates/view/admin?page=login', {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                if(res.ok) return res.text();
                throw Error('something went wrong');
            }).then((template) => {
                try {
                    let isLoggedIn = JSON.parse(template);
                    router.routeTo('/admin');
                } catch (e) {
                    hideLoader();
                    this.view({}, getTemplate(template));
                }
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        ...exist
    },
    '/admin/{page:(posts|pages)}' : {
        matched(resp, page){
            d.title = 'iBlogg Admin ' + page;
            doPost.call(this, page, resp);
        },
        mounted(){
            registerModalEvent();
        },
        ...exist
    },
    '/admin/{page:(newpost|newpage)}' : {
        matched(resp, page){
            d.title = 'iBlogg Admin ' + page;
            doNewPost.call(this, page.slice(-4) + 's', resp);
        },
        mounted(){
            registerModalEvent();
            if( window.tinymce ){
                tinyMceInit();
            }
        },
         ...exist
    },
    '/admin/{page:(editpost|editpage)}' : {
        matched(resp, page){
            d.title = 'iBlogg - Admin ' + page;
            doEditPost.call(this, page.slice(-4) + 's', resp);
        },
        mounted(){
            registerModalEvent();
            if( window.tinymce ){
                tinyMceInit();
            }
        },
        ...exist
    },
    '/admin/files' : {
        matched(resp){
            d.title = 'iBlogg - Admin Files';
            let hasOffset = 'page' in resp.params;
            let hasSearch = 's' in resp.params;
            let offset = resp.params.page || 1;
            let srchStr = hasSearch ? '&s=' + resp.params.s : '';
            
            fetch('templates/view/admin?page=files&offset=' + offset + srchStr, {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                let data = {
                    ...resp.params,
                    action : 'Create',
                    isNew : true,
                    s : resp.params.s || '',
                    currentUrl : resp.url.path
                };
                this.view(data, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        mounted(){
            registerModalEvent();
        },
        ...exist
    },
    '/admin/files/new' : {
        matched(resp){
            d.title = 'iBlogg - Admin New File';
            fetch('templates/view/admin?page=newfile', {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                let data = {
                    ...resp.params,
                    action : 'Create',
                    isNew : true,
                    s : resp.params.s || ''
                };
                this.view(data, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        mounted(){
            registerModalEvent();
        },
        ...exist
    },
    '/admin/settings' : {
        matched(resp){
            d.title = 'iBlogg - Admin settings';
            fetch('templates/view/admin?page=settings', {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                this.view(null, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        mounted(){
            registerModalEvent();
        },
        ...exist
    },
    '/admin/comments' : {
        matched(resp){
            d.title = 'iBlogg - Admin comments';
            let hasOffset = 'page' in resp.params;
            let offset = resp.params.page || 1;
            fetch('templates/view/admin?page=comments&offset=' + offset, {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                let data = {
                    backlink : resp.url.from || '/',
                    currentUrl : resp.url.path
                };
                this.view(data, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        ...exist
    },
    '/admin/user' : {
        matched(resp){
            d.title = 'iBlogg - Admin users';
            fetch('templates/view/admin?page=users', {
                headers : {
                    ...requestHeader,
                },
            })
            .then((res) => {
                return res.text();
            }).then((template) => {
                hideLoader();
                let data = {
                    backlink : resp.url.from || '/',
                };
                this.view(data, getTemplate(template))
            }).catch((e) => {
                this.view({}, getErrorTemplate());
            });
        },
        ...exist
    },
    '!' : {
        name : '404',
        matched(resp){
            hideLoader();
            d.title = "iBlogg - 404";
            let data = {
                message : '404 - File Not Found',
                backlink : resp.url.from || '/',
            };
           this.view(data);
        },
        ...exist
    }
}

let router = new Walkify(routes, '.app');

router.setPrefix('@');
router.setVariableBrackets(['{{', '}}']);