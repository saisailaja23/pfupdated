var featherEditor = new Aviary.Feather({
    apiKey: 'c86dc82efbb0a490',
    apiVersion: 2,
    theme: 'light',
    openType: 'lightbox',
    // tools: 'all',
    tools: ['crop'],
    onSave: function(imageID, newURL) {
        var img = document.getElementById(imageID);
        img.src = newURL;
    },
    postUrl: 'processors/crop/process.php'
});

function launchEditor(id, src) {
    featherEditor.launch({
        image: id,
        url: src
    });
    return false;
}

var edit = document.getElementById('edit');
edit.addEventListener('click', function(e) {
    e.preventDefault();
    featherEditor.launch({
        image: 'editableimage1',
        url: 'http://parentfinder.com/modules/boonex/photos/data/files/24048.jpg'
    });

});
// window.load = function() {
//     featherEditor.launch({
//         image: 'editableimage1',
//         url: 'http://devlocal.parentfinder.com/modules/boonex/photos/data/files/24048.jpg'
//     });
// }