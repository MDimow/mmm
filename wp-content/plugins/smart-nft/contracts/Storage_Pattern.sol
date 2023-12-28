// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

// This `Split_payment` struct
// hold a single address and how much percentage to send
// in this address. Say we have set 3 account for split payment
// this struct will hold 1 account address and how much % to send
// to this address.
struct Split_Payment {
	address addr;
	uint per;
}

struct Token_Details {
  address owner;
  uint chain_id;
  uint price;
  uint standard; // 721,1155
  uint total_split_payment_accounts;
  uint amount;
  bool is_listed;
  bool is_exist;
  bool has_split_payment;
  mapping( uint => Split_Payment ) index_to_split_payment;
}

struct STORAGE {
	mapping( address => mapping( address => mapping( uint256 => Token_Details ) ) ) con_user_id_details;
	mapping( address => mapping( uint256 => uint256 ) ) con_id_royalty;
	mapping( address => mapping( uint256 => address ) ) con_id_creator;
	mapping( address => mapping( address => mapping( uint256 => bool ) ) ) con_user_id_secondSale;
}

