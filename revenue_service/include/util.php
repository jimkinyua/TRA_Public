<?php
	
	function generate_salt($length=10){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		
		return $randomString;
	}
	
	function generate_token($user="", $email="")
	{
		$token = $user.$email.generate_salt(10);
		//$token = "test";
		//$token = $user.$email;
		//die($user.$email);
		//die(generate_salt(10));
		//die($token);
		return hash('sha256', $token);
	}
	
	function starts_with($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}

	function ends_with($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
	
	function is_str_contain($string, $keyword)
	{
		if (empty($string) || empty($keyword)) return false;
		$keyword_first_char = $keyword[0];
		$keyword_length = strlen($keyword);
		$string_length = strlen($string);

		// case 1
		if ($string_length < $keyword_length) return false;

		// case 2
		if ($string_length == $keyword_length) {
		  if ($string == $keyword) return true;
		  else return false;
		}

		// case 3
		if ($keyword_length == 1) {
		  for ($i = 0; $i < $string_length; $i++) {

			// Check if keyword's first char == string's first char
			if ($keyword_first_char == $string[$i]) {
			  return true;
			}
		  }
		}

		// case 4
		if ($keyword_length > 1) {
		  for ($i = 0; $i < $string_length; $i++) {
			/*
			the remaining part of the string is equal or greater than the keyword
			*/
			if (($string_length + 1 - $i) >= $keyword_length) {

			  // Check if keyword's first char == string's first char
			  if ($keyword_first_char == $string[$i]) {
				$match = 1;
				for ($j = 1; $j < $keyword_length; $j++) {
				  if (($i + $j < $string_length) && $keyword[$j] == $string[$i + $j]) {
					$match++;
				  }
				  else {
					return false;
				  }
				}

				if ($match == $keyword_length) {
				  return true;
				}

				// end if first match found
			  }

			  // end if remaining part
			}
			else {
			  return false;
			}

			// end for loop
		  }

		  // end case4
		}

		return false;
	}
	
?>