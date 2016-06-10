<?php

//single-file resource
	//O/P
	//Total Download Size: 9715 Bytes 
	//Total requests: 1
	$url = "http://sunadmin.sunumbrella.in/public/uploads/200X200/0_75875000_1465395582.jpg";

//complex resource
	//O/P
	//Total Download Size: 746722 Bytes 
	//Total requests: 47
	//Total CSS file download size: Bytes
	//Total SCRIPT download size:360322 Bytes
	//Total image download size:38077 Bytes

	$url = "http://www.sunumbrellas.in/cart-detail";

	

	$total_download_size = 0;   // var to calculate total download size

	$total_requests = 0; // var to calculate total requests made
	 
	$pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";   // regex pattern to match URL

	// URL validation 

	if ( preg_match($pattern, $url) ) {

		// check if single-file resource or complex resource

		if (!html_valid($url)) {

			// if it is a single-file resource

			$total_download_size = get_file_size($url);

			echo "Total Download Size: $total_download_size Bytes ";

			$total_requests += 1;  

			echo "Total requests: $total_requests" ;

			return;

		} else {

			include('simple_html_dom.php');

			$total_requests += 1; 

			$resource = file_get_html($url);

			// Check for all CSS files

			$total_css_size = 0;

			foreach($resource->find('link') as $value)
			{

				if (strpos($value->href,'.css') !== false) {

				  $size = get_file_size($value->href);

				  $total_css_size += $size;
				   
				  $total_download_size = $total_download_size + $size;
				   	   
				  $total_requests += 1;
				
				}
			    
			}


			// Check for all files:

			$total_script_size = 0;

			foreach($resource->find('script') as $value) {

				if (strpos($value->src,'.js') !== false) {

				  	$size = get_file_size($value->src);

				  	$total_script_size += $size;

				 	$total_download_size = $total_download_size + $size;	
				  	   
				 	$total_requests += 1;

				}
			}

			// Check for all images:

			$total_image_size = 0;

			foreach($resource->find('img') as $value){

				   $size = get_file_size($value->src);

				   $total_image_size += $size;
					
				   $total_download_size = $total_download_size + $size; 	
				   
				   $total_requests += 1;
			}

			echo "Total download size: $total_download_size Bytes" ;

			echo "Total requests: $total_requests";

			echo "Total CSS file download size: $total_css_size Bytes" ;

			echo "Total SCRIPT download size: $total_script_size Bytes" ;

			echo "Total image download size: $total_image_size Bytes" ;
		}

	} else {
	    echo "Invalid URL";
	}


	function get_file_size($url) {

		$headers = get_headers($url, 1);

		//the code below runs if no "Content-Length" header is found:

	    $c = curl_init();
	    curl_setopt_array($c, array(
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HTTPHEADER => array('User-Agent: Mozilla/5.0 
	        (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.3) 
	        Gecko/20090824 Firefox/3.5.3'),
	        ));
	    curl_exec($c);
	    
	    $size = curl_getinfo($c, CURLINFO_SIZE_DOWNLOAD);
	    
	    return $size;
	        
		curl_close($c);

	}


	function html_valid($url){

     	$c = curl_init($url);

	    curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($c, CURLOPT_HEADER, TRUE);
	    curl_setopt($c, CURLOPT_NOBODY, TRUE);

	    $data = curl_exec($c);
     	$contentType = curl_getinfo($c, CURLINFO_CONTENT_TYPE );

	    curl_close($c);

     
	    if (strpos($contentType,'text/html') !== false){
		 	return TRUE; 	
		} 	else {
		   return FALSE;
		}
	}


?>