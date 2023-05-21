<?php
    echo $editData->content;
?>
<script>paper_id='{{$editData->id}}'</script>
<script>
document.getElementById("memo_text").innerHTML = "{{$editData->memo_text}}";
</script>
