<h3>Node.js</h3>
<script>
    $(function() {

        var socket = new YiiNodeSocket();
        socket.debug(true);
        socket.on('updateBoard', function(data) {
            console.log(data.boardData);
            $('ul#recive').append('<li>'+data.boardId+'-'+data.boardData+'</li>')
        });
    });
</script>
<div>
    <ul id="recive">
        
    </ul>
</div>
