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
    $.ajax({
		
    	type: 'POST',
		url: "util/notepadPost.php",
		statusCode: {
			404: function() {
				alert('page not found');
			},
			409: function(jqXHR, textStatus, errorThrown) {
				alert('409 ' + errorThrown);
			},
			200: function(data, textStatus, jqXHR) {
				alert('200 ' + data);
			}
		},
		data: {
			//key1: "value1",
			//iwfiuwhfiuhwaufihawfylawf
			//efwefawfaw
			error : 'true'
		},
		complete: function(jqXHR, textStatus) {
			ed.setProgressState(0);// Hide progress
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


<!-- Blahiniuh

kopkp
ewflpwkefw
fwejfiwjf
wekfowjfowfj
wjf9qf
wjfwhfw
jf
wf]w
jfwfjw
fjw
fjw
jfw -->


<form method="post" action="">
    <textarea name="content" style="width:100%">
    </textarea>
    <input type = 'button' onclick = 'ajaxSave()' value = 'save'/>
    <input type = 'button' onclick = 'ajaxLoad()' value = 'load'/>
</form>

<form method="post" action="util/notepadPost.php">
    <input type = 'text' name='success' />
    <input type = 'submit' name='success' value = 'submit'/>
</form>