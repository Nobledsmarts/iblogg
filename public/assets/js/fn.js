const requestHeader = {
    'X-Requested-With' : "XMLHttpRequest"
};
const origin = location.origin;
const d = document;
const modalOption = {
    overlay : 'static'
}
const exist = {
    exist(){
        showLoader();
    }
} 
function showLoader(){
    d.getElementsByClassName('loader-container')[0].style.display = 'flex';
    d.getElementsByClassName('app')[0].style.display = 'none';
}
function hideLoader(){
    d.getElementsByClassName('loader-container')[0].style.display = 'none';
    d.getElementsByClassName('app')[0].style.display = 'flex';
}
function getTemplate(template){
    try {
        let errorObject = JSON.parse(template);
        return '<div class="not-found shadow-sm"> <h3 class="text-danger"> ' + errorObject['title'] + '</h3>' +  errorObject['message'] + '</div>';
    } catch (e) {
        return template;
    }
}
function getErrorTemplate(){
    return d.getElementById('error').innerHTML;
}
function searchPosts(page = '/', includeSearch){
    event.preventDefault();
    let form = event.currentTarget;
    let s = form.elements.namedItem('s');
    router.routeTo(page + (includeSearch ? 'search' : '') +  '?s=' + s.value);
}
function triggerDelete(id, type, isAll){
    let targetElem = event.currentTarget;
    if(type == 'file'){
        if( id ){
            window.fileId = id;
            $('.file-delete-confirm').modal('show');
        } else {
            let modal = d.querySelector('.file-delete-confirm').d.querySelector('.modal-body');
            modal.textContent = 'Do you want to delete all files?';
            $('.file-delete-confirm').modal('show')
        }
    } else if(type == 'post' || type == 'page'){
        if( id ){
            window.postId = id;
            window.postType = type;
            $('.post-delete-confirm').modal('show');
        } else {
            window.postType = type;
            let modal = d.querySelector('.post-delete-confirm').querySelector('.modal-body');
            modal.textContent = 'Do you want to delete all ' + type + 's?';
            $('.post-delete-confirm').modal('show')
        }
    } else if(type == 'comment'){
        if(id) {
            window.commentId = id;
            $('.comment-delete-confirm').modal('show');
        } else {
            let modal = d.querySelector('.comment-delete-confirm').querySelector('.modal-body');
            modal.textContent = 'Do you want to delete all comments?';
            $('.comment-delete-confirm').modal('show')
        }
    }
}
 function modalFix(){
     let app = d.getElementsByClassName('app')[0];
     let body = d.getElementsByTagName('body')[0];
     let header = d.getElementsByTagName('header')[0];
     let modal = d.getElementsByClassName('modal')[0];
     let bodyPadding = body.getAttribute('style');
     let nav = d.querySelector('nav.admin-nav');
     return {app, body, header, modal, bodyPadding, nav};
 }
 function registerModalEvent(){
    $('.modal').on("show.bs.modal", function(){
        setTimeout(() => {
            let [app, body, header, modal, bodyPadding, nav] = Object.values(modalFix());
            body.removeAttribute('style');
            header.removeAttribute('style');
            modal.style.padding = '0px';
            app.setAttribute('style', app.style.cssText + bodyPadding);
            nav.removeAttribute('style');
        });
    });
    $('.modal').on("hidden.bs.modal", function(){
        let [app] = Object.values(modalFix());
        app.style.cssText = 'display : flex;';
    });
}
function updateFileInput(){
    let inputFile = event.currentTarget;
    let files = inputFile.files;
    let label = inputFile.nextElementSibling;
    let filenames = [... files].map((file) => {
        return file.name;
    });
    label.classList.add('selected');
    label.innerHTML = (filenames.length > 1 ? filenames.length + " files selected" : filenames);
    let blob = getFileBlob(files);
    setPreview(blob);
}
function getFileBlob(file){
    let type = file[0].type;
    let blob = window.URL.createObjectURL(
        new Blob(file, {type}, true)
    );
    return blob;
}
function setPreview(blob){
    let img = d.getElementById('imgPreview');
    if( img ){
        img.src = blob;
        img.classList.remove('d-none');
    }
}
function adminLogin(){
    event.preventDefault();
    let form = event.currentTarget;
    let action = router.getHash(form.action);
    let login = form.elements.namedItem('login').value;
    let password = form.elements.namedItem('password').value;
    let formError = d.getElementById('formError');

    if(!login.trim() || !password.trim()){
        formError.innerHTML = 'Inputs Cant Be Empty!';
        formError.classList.remove('d-none');
        return;
    }
    fetch(origin + '/api/users/adminlogin/'+ login + '/' + password, {
        headers : {
            ...requestHeader
        }
    })
    .then((res) => {
       if(res.ok) return res.json();
    }).then((data) => {
        try {
            if( !data.data ){
                formError.classList.remove('d-none');
                formError.innerHTML = 'Login or Password Incorrect!';
            } else {
                if(router.getHash() != '/admin/login'){
                    router.route();
                } else{
                    router.routeTo('/admin');
                }
            }
        }
        catch(e) {
            formError.classList.remove('d-none');
            formError.innerHTML = 'Login or Password Incorrect!';
        }
    });
}
function logOut(){
    fetch(origin + '/api/users/logout', {
        headers : {
            'content-Type' : 'application/json',
            ...requestHeader
        }
    }).then((res) => res.json).then((data) => {
        if( !data.isLoggedIn ){
            router.routeTo('/');
        }
    });
}
function handlePost(){
    event.preventDefault();
    let form = event.currentTarget;
    let formError = d.getElementById('formError');
    let formSuccess = d.getElementById('formSuccess');
    let postId = form.elements.namedItem('post_id');
    let isEdit = postId ? true : false;
    let postTitle = form.elements.namedItem('post_title').value.trim();
    let postType = form.elements.namedItem('post_type').value.trim();
    let postBody = !window.tinymce ? form.elements.namedItem('post_body').value.trim() : tinymce.get('post_body').getContent().trim();
    let attachement = form.elements.namedItem('post_thumbnail');
    let thumbnail = attachement.files[0];
    // let hasValidType = hasValidType(thumbnail);
    let submitBtn = form.querySelector('.submit-btn');
    let label = form.querySelector('.custom-file-label');

    if( !postTitle || !postBody){
        formSuccess.classList.add('d-none');
        formError.innerHTML = 'Inputs cant be empty';
        formError.classList.remove('d-none');
        return;
    }
    submitBtn.classList.add('loading');

    let formData = new FormData(form);
    formData.set('post_body', postBody);
    fetch(origin + '/api/posts/handlepost', {
        method : 'POST',
        body : formData,
        headers : {
            ...requestHeader
        }
    }).then((res) => {
       if(res.ok || res.status == 400) return res.json();
       throw new Error('something went wrong');
    }).then((obj) => {
        if('error' in obj){
            submitBtn.classList.remove('loading');
            formError.classList.add('d-none');
            let modal = d.querySelector('.post-error').querySelector('.modal-body');
            modal.textContent = obj.messages.error;
            $('.post-error').modal('show');
            return;
        }
        if( !isEdit ){
            form.reset();
            if( window.tinymce ) tinymce.get('post_body').setContent('');
            label.innerHTML = label.dataset.text;
        }
        submitBtn.classList.remove('loading');
        formError.classList.add('d-none');
        let slug = obj.data.post_slug;
        let act = 'edited' in obj ? 'Edited' : 'Created';
        let link = `<a class="alert-link" href="/${postType}/${slug}">here</a>`;
        formSuccess.innerHTML = `${act}! click ${link} to view your ${postType}`;
        $('.post-handle').modal('show');
        formSuccess.classList.remove('d-none');
    }).catch((e) => {
        formError.classList.add('d-none');
        let modal = d.querySelector('.post-error').querySelector('.modal-body');
        modal.textContent = e.message;
        $('.post-error').modal('show');
    });
}
function handleFiles(){
    event.preventDefault();
    let form = event.currentTarget;
    let fileInput = form.querySelector('#attachments');
    let files = fileInput.files;
    let formError = d.getElementById('formError');
    let formSuccess = d.getElementById('formSuccess');
    let submitBtn = form.querySelector('.submit-btn');
    let label = form.querySelector('.custom-file-label');
    if( !files.length ){
        formSuccess.classList.add('d-none');
        formError.innerHTML = 'Input cant be empty!';
        formError.classList.remove('d-none');
        return;
    }
    if(files.length > 10){
        formSuccess.classList.add('d-none');
        formError.innerHTML = 'Maximum number of files exceeded!';
        formError.classList.remove('d-none');
        return;
    }
    submitBtn.classList.add('loading');
    fetch(origin + '/api/files/handlefiles', {
        method : 'POST',
        body : new FormData(form),
        headers : {
            ...requestHeader
        }
    }).then((res) => {
        if(res.ok) return res.json();
    }).then((data) => {
        form.reset();
        label.innerHTML = label.dataset.text;
        submitBtn.classList.remove('loading');
        formError.classList.add('d-none');
        let link = '<a class="alert-link" href="/admin/files"> here </a>';
        formSuccess.innerHTML = 'Upload successful click ' + link + 'to View Files';
        formSuccess.classList.remove('d-none');

        let successLen = Object.keys(data.messages).length;
        let successMsg = successLen == 1 ? 'File' : successLen + ' Files';
        let modal = d.querySelector('.file-upload-success').querySelector('.modal-body');
        modal.textContent = successMsg + ' uploaded successfully';
        $('.file-upload-success').modal('show');
    });
}
function deletePost(){
    let postId = window.postId || '';
    let postType = window.postType || '';
    // console.log(postType);
    // console.log(postId);
    // return;
    fetch(origin + '/api/posts/delete/' + postType + '/' + postId , {
        method : 'delete',
        headers : {
            ...requestHeader
        }
    }).then((res) => {
           if(res.ok) return res.json();
    }).then((obj) => {
        window.postId = undefined;
        postCount = obj.data.length;
        postType =  toUpperCase(postType);
        let modal = d.querySelector('.post-delete-success').querySelector('.modal-body');
        let msgStart = (postCount && postCount > 1) ? postCount + ' '  + postType + 's' : postType;
        modal.textContent = msgStart + ' deleted successfully !';
        $('.post-delete-success').modal('show');
        window.postType = undefined;
    });
}
function deleteFile(){
    let fileId = window.fileId || '';
    fetch(origin + '/api/files/delete/' + fileId, {
        method : 'delete',
        headers : {
            ...requestHeader
        }
    }).then((res) => {
          if(res.ok) return res.json();
    }).then((obj) => {
        fileCount = obj.data.length;
        window.fileId = undefined;
        let modal = d.querySelector('.file-delete-success').querySelector('.modal-body');
        let successMsg = !fileCount ?  'File ' : fileCount + ' Files ';
        modal.textContent = successMsg  + 'Deleted successfully !';
        $('.file-delete-success').modal('show');
    });
}
function deleteComment(){
    let commentId = window.commentId || '';
    let form = new FormData();
    form.set('comment_id', commentId);
    fetch(origin + '/api/comments/' + commentId, {
        method : 'delete',
        body : (form),
        headers : {
            ...requestHeader
        },
    }).then((res) => {
          if(res.ok)  return res.json();
    }).then((obj) => {
        window.commentId = undefined;
        window.commentType = undefined;
        commentCount = (obj.data).length || '';
        let modal = d.querySelector('.comment-delete-success').querySelector('.modal-body');
        let msgPrefix = commentCount ? commentCount + ' comments' : 'Comment'
        modal.textContent = msgPrefix  + ' comments deleted successfully !';
        $('.comment-delete-success').modal('show');
    });
}
function saveSettings(){
    event.preventDefault();
    let form = event.currentTarget;
    fetch(origin + '/api/settings/save', {
        method : 'POST',
        body : new FormData(form),
        headers : {
            ...requestHeader
        }
    }).then((res) => {
       if(res.ok) return res.json();
    }).then((obj) => {
        $('.settings-handle').modal('show');
    });
}
function tinyMceInit(){
    if(window.tinymce){
        tinymce.init({
            selector: 'textarea#post_body',
            plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
            toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            setup : function(editor) {
                editor.on('init', function(e){
                    let content = d.querySelector('textarea#post_body').value;
                    editor.setContent(content);
                });
            }
        });
    }
}
function doEditPost(postType, resp){
    let postid = resp.params.postid || resp.params.pageid;
    d.title = 'iBlogg - Admin Edit Post';
    fetch('templates/view/admin?page=handlepost&post_id=' + postid + '&post_type=' + postType, {
        headers : {
            ...requestHeader,
        },
    })
    .then((res) => {
        if(res.ok) return res.text();
        throw Error('something went wrong');
    }).then((template) => {
        hideLoader();
        let data = {
            postid,
            action : 'Edit',
            isNew : false,
            s : resp.params.s || '',
        };
        this.view(data, getTemplate(template))
    }).catch((e) => {
        hideLoader();
        this.view({}, getCatchMessage());
    });
}
function doNewPost(postType, resp){
    d.title = 'iBlogg - Admin New post';
    fetch('templates/view/admin?page=handlepost&post_type=' + postType, {
        headers : {
            ...requestHeader,
        },
    })
    .then((res) => {
        if(res.ok) return res.text();
        throw Error('something went wrong');
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
        hideLoader();
        this.view({}, getCatchMessage());
    });
}
function doPost(postType, resp){
    d.title = 'iBlogg - Admin Posts';
    let hasOffset = 'page' in resp.params;
    let hasSearch = 's' in resp.params;
    let offset = resp.params.page || 1;
    let srchStr = hasSearch ? '&s=' + resp.params.s : '';
    fetch('templates/view/admin?page=posts&offset=' + offset + srchStr + '&post_type=' + postType, {
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
}
function postComment(){
    event.preventDefault();
    let formError = d.getElementById('formError');
    let form = event.currentTarget;
    let formData = new FormData(form);
    formData.set('username', formData.get('username').trim());
    let uname = formData.get('username').toLowerCase();
    let comment = formData.get('comment').trim();
    if(!uname.length || !comment.length){
        formError.innerHTML = "Inputs cant be empty!"
        formError.classList.remove('d-none');
        return;
    }
    if(uname.length > 15){
        formError.innerHTML = "username should not exceed 15 characters!";
        formError.classList.remove('d-none');
        return;
    }
    if(comment.length > 500){
        formError.innerHTML = "comment should not exceed 500 characters!";
        formError.classList.remove('d-none');
        return;
    }
    fetch(origin + '/api/comments', {
        method : 'POST',
        body : formData,
        headers : {
            ...requestHeader
        }
    }).then((res) => {
        if(res.ok) return res.text();
        return res.text().then((r) => {
            try {
                return r;
            } catch(e){ 
                return JSON.stringify({
                    messages : {
                        error : 'something went wrong'
                    }
                });
            }
        });
    }).then((msg) => {
        let msgObj = JSON.parse(msg);
        if('error' in msgObj) {
            formError.innerHTML = msgObj['messages']['error'];
            formError.classList.remove('d-none');
            return;
        }
        router.route();
    }).catch((e) => {
        formError.innerHTML = "something went wrong";
        formError.classList.remove('d-none');
    });
}
function scrollInToView(elem){
    let el = d.querySelector(elem);
    el && window.scroll({top : el.offsetTop + 140, behavior : 'smooth'});
    // el && el.scrollIntoView();
}
function toUpperCase(str){
    console.log(str);
    return !str ? str : str[0].toUpperCase() + str.slice(1);
}