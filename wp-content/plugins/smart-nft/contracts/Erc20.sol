// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Burnable.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract Smartnft_ERC20 is ERC20, ERC20Burnable, Ownable {

	constructor(string memory _name, string memory _symbol, uint256 amount) ERC20( _name, _symbol ) {
		_mint(msg.sender, amount);
	}

    function mint(address to, uint256 amount) public onlyOwner {
        _mint(to, amount);
    }
}

/*FUNCTIONS
constructor(_name, _symbol)
name()
symbol()
decimals()
totalSupply()
balanceOf(account)
transfer(to, amount)
allowance(owner, spender)
approve(spender, amount)
transferFrom(from, to, amount)
increaseAllowance(spender, addedValue)
decreaseAllowance(spender, subtractedValue)
_transfer(from, to, amount)
_mint(account, amount)
_burn(account, amount)
_approve(owner, spender, amount)
_spendAllowance(owner, spender, amount)
_beforeTokenTransfer(from, to, amount)
_afterTokenTransfer(from, to, amount)*/
