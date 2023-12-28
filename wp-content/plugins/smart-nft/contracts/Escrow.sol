// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;
import "./Interface.sol";
import "./Storage_Pattern.sol";

contract Escrow {
  STORAGE internal S; 

  constructor() {}

  modifier restricted {
	require( msg.sender == S.admin, "Access restricted for 3rd party" );
	_;
  }


  function lock_in_escrow( address owner, address _contract, uint256 id ) external payable {
	S.contract_user_id_amount[_contract][owner][id] += msg.value; // add new fund to previous fund
  }

  function relese_escrow (
	address[3] memory _params0, // contract_address, owner_address, erc20Contractaddress 
	uint256[4] memory _params1, // token_id, standard, amount, listingFee
	bool[1] memory _params2     // is_erc_20
  ) external 
  {
	uint256 _price = S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].price * _params1[2] ;

	if( _params2[0] != true ) {
	   require( S.contract_user_id_amount[_params0[0]][msg.sender][_params1[0]] >= _price, "Insuf amnt" ); //Insufficient amount 
	   _price = _price - _params1[3]; // [3] listingFee
	   S.fees += _params1[3]; // add listingFee 
	   S.contract_user_id_amount[_params0[0]][msg.sender][_params1[0]] -= _price;
	}

	uint256 _royality;
	uint256 standard = S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].standard; 
	address _token_creator =  S.con_id_creator[ _params0[0] ][ _params1[0] ];

	//CHECK IF TOKEN HAS ANY ROYALTY AND ITS SECONDARY SALE.
	if( S.con_id_royalty[ _params0[0] ][ _params1[0] ] != 0 && S.con_user_id_secondSale[ _params0[0] ][ _params0[1] ][ _params1[0] ] ) {

	  	 //ROYALTY PAYMENT ( TOTAL ROYALTY )
	  	 _royality = calc_percentage( _price, S.con_id_royalty[ _params0[0] ][ _params1[0] ]  );

	  	  //IF SPLIT PAYMENT IS SET THEN SEND THE ROYALTY IN DIFFERENT ACCOUNT	
	  	  if( S.con_user_id_details[ _params0[0] ][ _token_creator ][ _params1[0] ].has_split_payment == true ) {
	  		 process_split_payment( _params0[0], _token_creator, _params1[0], S.con_user_id_details[ _params0[0] ][ _token_creator ][ _params1[0] ].total_split_payment_accounts, _royality, _params2[0] ? _params0[2] : address(0) );		
	  	  } else {
	  	  	 //SPLIT PAYMENT NOT SET SEND ROYALTY IN SINGLE ACCOUNT
	  		 send_money( _params0[2], _token_creator, _royality, _params2[0] ? 20 : standard);
	  	  }

	  	  //PRICE MONEY PAYMENT ( SUBTRACT ROYALTY FROM PRICE )

	  	  //IF SPLIT PAYMENT IS SET THEN SEND THE PRICE MONEY IN DIFFERENT ACCOUNT	
	  	  if( S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].has_split_payment == true ) {
	  		 process_split_payment( _params0[0], _params0[1], _params1[0], S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].total_split_payment_accounts, ( _price - _royality ), _params2[0] ? _params0[2] : address(0) );
	  	  } else {
	  	  //SPLIT PAYMENT NOT SET SEND PRICE MONEY  IN SINGLE ACCOUNT
	  		 send_money( _params0[2], _params0[1], ( _price - _royality ), _params2[0] ? 20 : standard);
	  	  }

	} else{
	//DONT HAVE ANY ROYALTY, NO NEED TO SUBTRACT ROYALTY FROM PRICE

	  	  //IF SPLIT PAYMENT IS SET THEN SEND THE PRICE MONEY IN DIFFERENT ACCOUNT	
	  	  if( S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].has_split_payment == true ) {
	  		 process_split_payment( _params0[0], _params0[1], _params1[0], S.con_user_id_details[ _params0[0] ][ _params0[1] ][ _params1[0] ].total_split_payment_accounts, _price, _params2[0] ? _params0[2] : address(0) );		
	  	  } else {
	  	  	 //SPLIT PAYMENT NOT SET SEND PRICE MONEY  IN SINGLE ACCOUNT
	  		 send_money( _params0[2], _params0[1], _price, _params2[0] ? 20 : standard );
	  	  }
	}

	// BUY COMPLEATE TRANSFER THE TOKEN
	if( standard == 721 ) {
	  	ERC721_Interface( _params0[0] ).safeTransferFrom( address(this), msg.sender, _params1[0] );
	}

	if( standard == 1155 ) {
	    ERC1155_Interface( _params0[0] ).safeTransferFrom( address(this), msg.sender, _params1[0], _params1[2], "0x0" );
	}

	S.con_user_id_secondSale[ _params0[0] ][ msg.sender ][ _params1[0] ] = true;

  }

  //HELPER FUNCTION
  function process_split_payment ( address con, address user, uint256 token_id, uint256 total_accounts, uint256 money, address erc20Con ) private {
		
		// LOOP THROW ALL ADDRESS AND ROYALTY
		for( uint256 i = 1;  i <= total_accounts; i++  ) {

		  	// CHECK ADDRESS AND PERCENTAGE ARE NOT BLANK	
		  	if(
		  		S.con_user_id_details[ con ][ user ][ token_id ].index_to_split_payment[ i ].addr != address(0) &&
		  		S.con_user_id_details[ con ][ user ][ token_id ].index_to_split_payment[ i ].per != 0 
		  	  )
			  {
		  		//SEND ROYALTY
				uint256 payment = calc_percentage( money, S.con_user_id_details[ con ][ user ][ token_id ].index_to_split_payment[ i ].per );
					send_money(
						erc20Con,
						S.con_user_id_details[ con ][ user ][ token_id ].index_to_split_payment[ i ].addr,
						payment,
						erc20Con == address(0) ? S.con_user_id_details[ con ][ user ][ token_id ].standard : 20
					);
		     }

		}

  }

  function send_money ( address con, address receiver, uint256 amount, uint256 standard ) private {

		//CHECK CONTRACT STANDARD AND SEND MONEY THAT WAY	
		if( standard == 721 || standard == 1155 ) {
		   //SEND MONEY
		   payable( receiver ).transfer( amount );
		}

		if( standard == 20 ) {
		   bool success = ERC20_Interface( con ).transfer( receiver, amount );
		   require( success, "ERC20 T T F." ); // erc20 token transfer fail
		}

	    require( standard == 721 || standard == 1155 || standard == 20, "I S" ); //invalid standard
  }


  function calc_percentage( uint256 _amount, uint256 _percentage ) private pure returns ( uint256 ) {
  	//_percentage is send by multiplying with 100
  	//to get the percentage we devide the percentage with 10000
  	return _amount * _percentage / 10000;
  }

  receive() external payable{} 
}
