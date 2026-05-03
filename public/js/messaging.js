// Polling messagerie toutes les 5 secondes
setInterval(function () {
    fetch('/peerlearn/public/?url=message/poll')
        .then(r => r.json())
        .then(data => { console.log(data); });
}, 5000);
