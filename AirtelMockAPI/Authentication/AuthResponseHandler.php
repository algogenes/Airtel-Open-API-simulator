<?php

class AuthResponse{

	function return_response($token, $expTime){
		return json_encode(array(
			"access_token"=> $token,
        "expires_in"=> $expTime,
          "token_type"=> "bearer"
      )
		);

		
}
}

?>