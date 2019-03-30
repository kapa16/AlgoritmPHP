<?php


$queue = new SplQueue();

?>

<form action="" method="post" id="form">
    <input type="text" name="message" id="">
    <button type="submit">SEND</button>
</form>

<script>
    document.querySelector('#form').addEventListener('submit', (evt) => {
      evt.preventDefault();
      console.log(evt);
      fetch('/task3.php', {
        method: 'post',
        mode: "cors",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({"message": evt.target.message.value})
      })
        .then(response => response);
    });
</script>
