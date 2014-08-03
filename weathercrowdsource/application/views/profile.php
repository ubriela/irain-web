<div class="row clearfix">
		<div class="col-md-12 column">
        
			<form role="form" id="upavatar">
            
            <fieldset><legend>iRain - Update location</legend>
                <div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />
 
				<div id="container">
                    <a id="pickfiles" href="javascript:;"><button type="button">Select file</button></a>
                    <a id="uploadfiles" href="javascript:;"><button type="button">Upload file</button></a>
                </div>
                 
                <br />
                <pre id="console"></pre>
                 
                 
                <script type="text/javascript">
                // Custom example logic
                 
                var uploader = new plupload.Uploader({
                    runtimes : 'html5,flash,silverlight,html4',
                     
                    browse_button : 'pickfiles', // you can pass in id...
                    container: document.getElementById('container'), // ... or DOM Element itself
                     
                    url : '<?php echo base_url();?>upload.php',
                     
                    filters : {
                        max_file_size : '10mb',
                        mime_types: [
                            {title : "Image files", extensions : "jpg,gif,png"}
                            
                        ]
                    },
                 
                    // Flash settings
                    flash_swf_url : '<?php echo base_url();?>'+'js1/Moxie.swf',
                 
                    // Silverlight settings
                    silverlight_xap_url : '<?php echo base_url();?>js1/Moxie.xap',
                     
                 
                    init: {
                        PostInit: function() {
                            document.getElementById('filelist').innerHTML = '';
                 
                            document.getElementById('uploadfiles').onclick = function() {
                                uploader.start();
                                return false;
                            };
                        },
                 
                        FilesAdded: function(up, files) {
                            plupload.each(files, function(file) {
                                document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                            });
                        },
                 
                        UploadProgress: function(up, file) {
                            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                        },
                 
                        Error: function(up, err) {
                            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
                        }
                    }
                });
                 
                uploader.init();
                 
                </script>
                <button type="button" class="btn btn-default btnback">Back</button>
			</fieldset>
            </form>
            
		</div>
	</div>