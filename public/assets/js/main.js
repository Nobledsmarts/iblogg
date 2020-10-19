if('serviceWorker' in navigator){
    navigator.serviceWorker
    .register('../assets/sw.js')
    .then((e) => console.log('registered'))
    .catch(e => console.log(e));
}