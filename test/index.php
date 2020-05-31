<input id="address" name="address" type="text" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.7.1/dist/js/jquery.suggestions.min.js"></script>

<script>
    $("#address").suggestions({
        token: "54fd2f790fa7430a86a27494a726f39ad608d666",
        type: "ADDRESS",
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function(suggestion) {
            console.log(suggestion);
        }
    });
</script>