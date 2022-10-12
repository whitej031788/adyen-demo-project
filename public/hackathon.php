<?php
//
// A very simple PHP example that sends a HTTP POST to a remote site
//

$filters = array_fill(0, 1, null);
 
for($i = 1; $i < $argc; $i++) {
    $filters[$i - 1] = $argv[$i];
}

$voteFor = $filters[0];

if (!$voteFor) {
    $voteFor = $_GET['voteFor'];
}

while (1) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://live.adyentechevent.com/games/registerVote?votefor=" . $voteFor);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'referer: https://live.adyentechevent.com',
        'cookie: token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImphbWllLndoaXRlQGFkeWVuLmNvbSIsInVzZXJJZCI6NjQ4LCJpYXQiOjE2NjU0MDQzMjMsImV4cCI6MTY3MDY2NDMyM30.AzWHhWIXo_OWLvawrMEH5YLdXU7UgRtKgIqgNu9BPRc'
    ));

    $server_output = curl_exec($ch);

    $info = curl_getinfo($ch);

    curl_close($ch);
    echo("Vote registered for " . $voteFor . "\n");
    sleep(1);
}
?>
