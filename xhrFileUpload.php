<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/css/uploader.css">
	</head>
	<body>
		<div id="main-container">
			<div class="upload-area">
				<div>
					<div class="custom-upload">
						<button id="dp-upload">Browse</button>
					</div>			
					<form id="uploadfrm" onsubmit="return false;">	
						<div class="clear"></div>			
						<!-- <input type="file" id="upload" name="uploadit"> -->
						<input type="file" id="upload" name="uploadit[]" multiple>
						<input type="submit" value="upload" value"upload">
					</form>
					<div class="upload-stats">
						<div id="prgs-perc" class="prgs-perc-area"></div>
						<div id="prgs" class="prgress-bar" ></div>
						<div id="status" class="status-bar" ></div>					
					</div>
					<div class="clear"></div>
					<div id="list"></div>
				</div>
			</div>
		</div>	
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script>
		var url= "recieve.php";
		$(function(){
			var form_element = document.getElementById('uploadfrm');
			var file_inp_obj = document.getElementById('upload');
		    $("#uploadfrm").submit(function(e) {		        		

		    	//No file selected
		    	if(!file_inp_obj.files.length){
		    		return false;
		    	}

		        var xhr = new XMLHttpRequest();	

		        xhr.addEventListener('progress', function(e) {		            	
		            var done = e.position || e.loaded, total = e.totalSize || e.total;
		        }, false);
		        if ( xhr.upload ) {
		            xhr.upload.onprogress = function(e) {		                	
		                var done = e.position || e.loaded, total = e.totalSize || e.total;
		                $("#prgs-perc").fadeIn().text(Math.floor(done/total*1000)/10+'%');
		                $("#prgs").fadeIn().css("width",Math.floor(done/total*1000)/10+"%");
		            }
		        }
		        xhr.onreadystatechange = function(e) {
		            if ( 4 == this.readyState ) {
		            	document.getElementById("upload").value = "";
		            	$("#status").fadeIn().text('Upload Completed');		
		            	setTimeout(function(){
		            		$("#status,#prgs,#prgs-perc").fadeOut().text('');	
		            		$('#list').fadeOut().html('');			                	
		            	},1500);
		            }
		        };

		        xhr.open('post', url, true);
				var formData = new FormData(form_element);					
				xhr.send(formData);					

		    });//form submit closed

			$("#dp-upload").click(function(){
				$("#upload").click();

				//For browsers like safari
				/*var file_inp_obj = $('#upload')[0];
				var evObj = document.createEvent('MouseEvents');
				evObj.initMouseEvent('click', true, true, window);
				file_inp_obj.dispatchEvent(evObj);	*/

				//file_inp_obj.fireEvent("onclick");			
			});

			document.getElementById('upload').addEventListener('change', handleFileSelect, false);	

		});//DOM ready closed

		function handleFileSelect(evt) {
			$("#list").show();
			var files = evt.target.files; // FileList object			    

			// Loop through the FileList and render image files as thumbnails.
			for (var i = 0, f; f = files[i]; i++) {
				console.log(f);
				// Only process image files. and ignoring file greater than 5 MB as it hangs system 
				if (!f.type.match('image.*') || (f.size/1024)/1024 > 5 ) {
					continue;
				}

				var reader = new FileReader();

				// Closure to capture the file information.
				reader.onload = (function(theFile) {
				return function(e) {					
					// Render thumbnail.
					var div = document.createElement('div');
					div.setAttribute('class','img-cnt');
					if ( e.target.readyState ==2 ) {
						div.innerHTML = ['<img class="thumb" src="', e.target.result,
						                '" title="', escape(theFile.name), '"/>'].join('');
						document.getElementById('list').insertBefore(div, null);
					}
				};
				})(f);

				// Read in the image file as a data URL.
				reader.readAsDataURL(f);
			}
		}
	</script>		
</html>
