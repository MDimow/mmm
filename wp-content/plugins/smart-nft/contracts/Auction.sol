// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;
import "./Interface.sol";
import "hardhat/console.sol";

struct SingleAuction {
	// minimum price to start the bid
	uint min_price;
	// bidders is the addresses of all the bidders
	// who perticipate in this auction
	/*address[] bidders;*/
	// beneficiary is the owner of token and start the bid.
	// also will get the money after bid is done
	address beneficiary;
	// auctionStartTime,auctionEndTime are either
	// absolute unix timestamps (seconds since 1970-01-01)
	// or time periods in seconds.
	uint start_time;
	uint end_time;
	// `highestBidder` is the currently higgest bid account address
	address highest_bidder;
	// `highestBid` is the amount of higgest bid money in wei
	uint highest_bid;
	// Set to true at the end, disallows any change.
	// By default initialized to `false`.
	// This `ended` keep track if an auction is end or not.
	bool ended;
    bool started;
	// Allowed withdrawals of previous bids
	// This `pendingReturns` keep tract bidder money
	mapping(address => uint) pending_returns;
}

struct Split_Payment {
	address addr; //address
	uint256 per; //percentage
}

contract Smartnft_Auction {
    string public name;
    string public symbol;
	address public admin;
	mapping( address => mapping( address => mapping( uint256 => SingleAuction ) ) ) _conAdd_userAdd_id_auction;
	mapping( address => mapping( uint256 => uint256 ) ) _conAdd_id_royalty;
	mapping( address => mapping( uint256 => address ) ) _conAdd_id_creator; 
	mapping( address => mapping( address => mapping( uint256 => bool ) ) ) _conAdd_userAdd_id_secondSale;
	mapping( address => mapping( address => mapping( uint256 => mapping( uint256 => Split_Payment ) ) ) ) _conAdd_userAdd_id_index_splitPayment;
	mapping( address => mapping( address => mapping( uint256 => uint256 ) ) ) _conAdd_userAdd_id_totalAddress;
	uint256 public listing_fee;

    constructor( string memory _name, string memory _symbol ) {
       name = _name;
       symbol = _symbol;
	   admin = msg.sender;
    }

	function calc_percentage( uint _amount, uint _percentage ) private pure returns (uint) {
		//_percentage is send by multiplying with 100
		//to get the percentage we devide the percentage with 10000
		return _amount * _percentage / 10000;
	}

	function withdraw_listing_fee() external {
      require( msg.sender == admin, "Access restricted for admin");
	  uint256 _listing_fee = listing_fee;
	  listing_fee = 0;
	  if( !payable( admin ).send( listing_fee ) ) {
			listing_fee = _listing_fee;	
	  }
      
	}

	function get_listing_fee() public view returns( uint256 ) {
		return listing_fee;
	}

	function start_auction (
	 address[] memory _params0, // conAdd,creator,split_payment_addresses_from_index_2
	 uint256[] memory _params1,  //token_id, _min_price, _start_time, _end_time, royalty, standard, _split_payment_percentages_from_index_6
	 bool[] memory _params2 //has_split_payment, has_royalty
	) public payable
     only_owner( _params0[0], _params0[1], _params1[0], _params1[5] )
	 market_need_access( _params0[0], _params0[1] )
    {
        require(
		   _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].started == false,
		   "already exist"
	    );

		_conAdd_userAdd_id_auction[ _params0[0] ][ msg.sender ][ _params1[0] ].min_price = _params1[1];
		_conAdd_userAdd_id_auction[ _params0[0] ][ msg.sender ][ _params1[0] ].beneficiary = msg.sender;
		_conAdd_userAdd_id_auction[ _params0[0] ][ msg.sender ][ _params1[0] ].start_time = _params1[2];
		_conAdd_userAdd_id_auction[ _params0[0] ][ msg.sender ][ _params1[0] ].end_time = _params1[3];
		_conAdd_userAdd_id_auction[ _params0[0] ][ msg.sender ][ _params1[0] ].started = true;

		//if creator,royalty not set before then set it
		if( _conAdd_id_creator[ _params0[0] ][ _params1[0] ] == address(0) ) {
			_conAdd_id_creator[ _params0[0] ][ _params1[0] ] = _params0[1];
			_conAdd_id_royalty[ _params0[0] ][ _params1[0] ] = _params1[4];
		}

		//set split payment if has
		if( _params2[0] ) {
			//set addresses
			for( uint256 i = 0;  i < _params0.length; i++ ) {
				if( i >= 2 ) {
					_conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ msg.sender ][ _params1[0] ][ i - 1 ].addr = _params0[i];
					_conAdd_userAdd_id_totalAddress[ _params0[0] ][ msg.sender ][ _params1[0] ] += 1;
				}
			}

			//set percentage
			for( uint256 i = 0;  i < _params1.length; i++ ) {
				if( i >= 6 ) {
					_conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ msg.sender ][ _params1[0] ][ i-5 ].per = _params1[i];
				}
			}

		}

		//add listing fee
		listing_fee += msg.value;

    }
		
  // End the auction and send the highest bid
  // to the beneficiary.
  function auction_end(
	address[] memory _params0, // conAdd,ownerAdd
    uint256[] memory _params1,  //token_id
	uint256 _standard
  ) public
	market_need_access( _params0[0], _params0[1] )
  {
      // 1. CONDITIONS
      //check if auction exits. if auction not exist then revert
      require(
		_conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].started,
		"not exist"
	  );
      require( 
		block.timestamp >= _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].end_time,
	   	"not yet ended."
	  );
      require( 
		_standard != 721 || _standard != 1155,
	   	"Invalid standard"
	  );


      //check if any bid happen or not
      //if no bidding then dont transer token
      //just end the auction
      if( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid == 0 ) {
          // 2. Effects
          _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].ended  = true;
          _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].started = false;
      }else{
	 	  //BIDDING HAPPEN DO THE REST OF THE THING

          // 2. Effects
          _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].ended  = true;
          _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].started = false;

	 	  // 3. INTERACTION
	 	  //transer the token
	 	  //Everything is right now transfer the token to new owner
		  if( _standard == 721 ) {
			  ERC721_Interface( _params0[0] ).safeTransferFrom(
			    _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].beneficiary,
			    _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bidder,
			    _params1[0]
			  );
		  }

		  if( _standard == 1155 ) {
    		  ERC1155_Interface( _params0[0] ).safeTransferFrom( _params0[1], _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bidder, _params1[0], 1, "0x0" );
		  }

		  address _creator =  _conAdd_id_creator[ _params0[0] ][ _params1[0] ] ;
		  uint256 _total_add = _conAdd_userAdd_id_totalAddress[ _params0[0] ][ _creator ][ _params1[0] ] ; 
		  uint256 payment;
		  uint256 i;


		  //IF ROYALTY IS SET 
	 	  if( _total_add != 0 && _conAdd_userAdd_id_secondSale[ _params0[0] ][ _params0[1] ][ _params1[0] ] ) {

				uint256 royalty = calc_percentage( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid, _conAdd_id_royalty[ _params0[0] ][ _params1[0] ]  );

				//give royalty,price in different account if split payment set.
				if( _conAdd_userAdd_id_totalAddress[ _params0[0] ][ _creator ][ _params1[0] ] != 0 ) {
						//give royalty
						for(  i = 1; i <= _total_add; i++ ) {
		       				payment = calc_percentage( royalty, _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _creator ][ _params1[0] ][i].per );
							payable( _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _creator ][ _params1[0] ][i].addr ).transfer( payment );
						}

						//give price
						for( i = 1; i <= _total_add; i++ ) {
		       				payment = calc_percentage( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid - royalty, _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _creator ][ _params1[0] ][i].per );
							payable( _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _params0[1] ][ _params1[0] ][i].addr ).transfer( payment );
						}
				}else{
				//split payment not set
	 	    		   payable( _creator ).transfer( royalty );
	 	    	//pay the seller by subtracking the royalty
	 	   		       payable( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].beneficiary ).transfer( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid - royalty );
				}

				// make it secondary sale
				_conAdd_userAdd_id_secondSale[ _params0[0] ][ _params0[1] ][ _params1[0] ] = true;

		  //ROYALTY IS NOT SET
	 	  }else{
				//give price in different account if split payment set.
				if( _total_add != 0 ) {
						//give price
						for( i = 1; i <= _total_add; i++ ) {
		       				payment = calc_percentage( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid, _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _creator ][ _params1[0] ][i].per );
							payable( _conAdd_userAdd_id_index_splitPayment[ _params0[0] ][ _params0[1] ][ _params1[0] ][i].addr ).transfer( payment );
						}
				}else{
	 	    	//pay the seller full price in single account
 						payable( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].beneficiary ).transfer( _conAdd_userAdd_id_auction[ _params0[0] ][ _params0[1] ][ _params1[0] ].highest_bid );
				}

				// make it secondary sale
				_conAdd_userAdd_id_secondSale[ _params0[0] ][ _params0[1] ][ _params1[0] ] = true;
	 	  }
     }
  }

  // Bid on the auction with the value sent
  // together with this transaction.
  // The value will only be refunded if the
  // auction is not won.
  function bid_on_auction(
    address _contract_add,
	address _user_add, // _user_add is the user started the auction
    uint256 _token_id
  ) public payable {
      //check if auction exits. if auction not exist then revert
      require(
		 _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].started,
		 "not exist"
	  );

      //check if minimum price requirement is full filled
      require(
		 _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].min_price <= msg.value,
	   	 "Bid price need to be greater or equal to minimum price."
	  );

      //check owner will not participate on this auction
      require(
		 _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].beneficiary != msg.sender,
		 "Owner can't participate in the auction" 
	  );

      // Revert the call if the bidding
      // period is over 
      require(
         _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].end_time > block.timestamp,
         "Auction already ended."
      );

	  uint256 _highest_bid = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bid;
	  address _highest_bidder = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bidder;

      // If the bid is not higher, send the
      // money back (the failing require
      // will revert all changes in this
      // function execution including
      // it having received the money).
      require(
         msg.value > _highest_bid,
         "There already is a higher bid."
      );

      //highest_bid != 0 means
      if ( _highest_bid != 0 ) {
          // Sending back the money by simply using
          // highestBidder.send(highestBid) is a security risk
          // because it could execute an untrusted contract.
          // It is always safer to let the recipients
          // withdraw their money themselves.
          // `+=` is super important if same user bid multiple time and highestBid change miltiple time
          // then multiple bidding looser get multiple biding loose money beck
          // address       bid         highestBid          return(add-money)
          //  1           10          10                  1-0
          //  2           20          20                  1-10
          //  1           30          30                  2-20
          //  2           40          40                  1-10 + 30 = 40
          _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].pending_returns[ _highest_bidder ] += _highest_bid;
      }

      _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bidder = msg.sender;
      _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bid = msg.value;
  }

  // Withdraw a bid that was overbid.
  function withdraw_bid(
	 address _contract_add, 
	 address _user_add, // _user_add is the user started the auction
	 uint256 _token_id 
  ) public {
      //heighter bidder cant withdraw money
      /*require(*/
		/*_conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bidder != msg.sender,*/
		/*"Highest Bidder cant withdraw money."*/
	  /*);*/

      uint amount = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].pending_returns[ msg.sender ];
      //May be caller dont bid but want to withdraw
      require( amount > 0, "Nothing to withdraw" );

      if ( amount > 0 ) {
          // It is important to set this to zero because the recipient
          // can call this function again as part of the receiving call
          // before `send` returns.
          _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].pending_returns[ msg.sender ] = 0;

          if ( !payable( msg.sender ).send( amount ) ) {
              //No need to call revert here, just reset the amount owing
          	  _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].pending_returns[ msg.sender ] = amount;
          }
      }
  }

  function auction_info( 
	 address _contract_add, 
	 address _user_add, // _user_add is the user started the auction
	 uint256 _token_id 
  ) public  view returns ( uint min_price, address beneficiary, uint end_time, address highest_bidder, uint highest_bid, bool started, bool ended  ) {
		min_price = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].min_price;
		beneficiary = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].beneficiary;
		end_time = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].end_time;
		highest_bidder = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bidder;
		highest_bid = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].highest_bid;
		started = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].started;
		ended = _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].ended;
  }

  function recipient_balance(
	 address _contract_add, 
	 address _user_add, // _user_add is the user started the auction
	 address _recipient_add,
	 uint256 _token_id
  ) public view returns ( uint256 withdrawable_balance ) {
		return _conAdd_userAdd_id_auction[ _contract_add ][ _user_add ][ _token_id ].pending_returns[ _recipient_add ];
  }

  modifier only_owner ( address _contract_add, address _user_add, uint256 _token_id, uint256 _standard ) {
	  if( _standard == 721 ) {
			require( 
			  ERC721_Interface( _contract_add ).ownerOf( _token_id ) == msg.sender,
			  "Only owner"
			);
	  }

	  if( _standard == 1155 ) {
		   require(  
			  ERC1155_Interface( _contract_add ).balanceOf( _user_add, _token_id ) > 0,
			  "Only owner"
		   );
	  }

	  require( _standard == 721 || _standard == 1155, "Invalid standard" );

	  _;
  } 
  
  modifier market_need_access ( address _contract_add, address _user_add ) {
	  require(
	  	  Common_Interface( _contract_add ).isApprovedForAll( _user_add, address(this) ),
		  "Market need access"
	  );

	  _;
  } 


}
