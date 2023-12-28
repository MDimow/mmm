// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

import "./Storage_Pattern.sol";

contract Proxy {
	STORAGE S; 
	address public Implementation;
	address public admin;

	 constructor() {
        admin = msg.sender;
    }

    function setImplementation (address _implementation) public {
        require( _implementation != address(0), "address zero" );
        require( msg.sender == admin, "Access restricted for admin");

        Implementation = _implementation;
    }

    function getImplementation() public view returns(address){
        return Implementation;
    }

    function getBalance() public view returns(uint256){
       return address(this).balance;
    }

    function withdrawBalance() public {
      require( msg.sender == admin, "Access restricted for admin");
      payable(admin).transfer(address(this).balance);
    }


    fallback() external payable {
        address _implementation = Implementation;

        assembly {
            let _target := _implementation
            calldatacopy(0x0, 0x0, calldatasize())
            let result := delegatecall(gas(), _target, 0x0, calldatasize(), 0x0, 0)
            returndatacopy(0x0, 0x0, returndatasize())
            switch  result 
            case 0 {
              revert(0, returndatasize())
            } 
            default {
              return (0, returndatasize())
            }
        }
  }

  receive() external payable{} //dummy function does nothing 

}

