<?php
include_once ('templates/meta.html')
?>
<button onclick="start()" style="width: 100%; height: 100%" id="gong">НАЖАТЬ</button>
<audio src="audio/01071.mp3" autoplay="autoplay"></audio>
<script>
    function start(){
        var audio = new Audio(); // Создаём новый элемент Audio
        audio.src = 'audio/01071.mp3'; // Указываем путь к звуку "клика"
        audio.autoplay = true; // Автоматически запускаем
        //this.style('background-color: green');
        setTimeout(()=> {
            this.sound.stop();
        }, 1000);
    }
</script>

