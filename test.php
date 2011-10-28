<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("jquery", "1");
	</script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce_src.js"></script>
<script type="text/javascript">

tinyMCE.init({
    mode : "textareas",
    theme : "advanced"
});


function ajaxLoad() {
    var ed = tinyMCE.get('content');

    // Do you ajax call here, window.setTimeout fakes ajax call
    ed.setProgressState(1); // Show progress
   /* window.setTimeout(function() {
        ed.setProgressState(0); // Hide progress
        ed.setContent('HTML content that got passed from server.');
    }, 3000);*/
    $.ajax({
		url: "util/notepadPost.php",
		statusCode: {
			404: function() {
				alert('page not found');
			},
			409: function(data) {
				alert('409 ' + data);
			},
			200: function(data) {
				alert('200 ' + data);
			}
		},
		data: {
			//key1: "value1",
			error : 'true'
		},
		complete: function(jqXHR, textStatus) {
			ed.setProgressState(0);
		}
    });
}

function ajaxSave() {
    var ed = tinyMCE.get('content');

    // Do you ajax call here, window.setTimeout fakes ajax call
    ed.setProgressState(1); // Show progress
    window.setTimeout(function() {
        ed.setProgressState(0); // Hide progress
        alert(ed.getContent());
    }, 3000);
}
</script>

<form method="post" action="somepage">
    <textarea name="content" style="width:100%">
    </textarea>
    <input type = 'button' onclick = 'ajaxSave()' value = 'save'/>
    <input type = 'button' onclick = 'ajaxLoad()' value = 'load'/>
</form>