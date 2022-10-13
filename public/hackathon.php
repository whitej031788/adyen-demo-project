<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>
            const urlParams = new URLSearchParams(window.location.search);
            let voteFor = urlParams.get('voteFor');

            function voteTime(voteFor) {
                $.ajax('/api/hackathon/' + voteFor,   // request url
                    {
                        success: function (data, status, xhr) {// success callback function
                            $('p').append("<p>" + data.message + "</p>");
                            setTimeout(voteTime(voteFor), 3000);
                    }
                });
            }

            voteTime(voteFor);

        </script>
    </head>
<body>

<h1>Hack Time</h1>
<p></p>
</body>
</html>